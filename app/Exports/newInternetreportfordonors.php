<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Log;

class NewInternetReportForDonors implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $request;

    function __construct($request)
    {
        $this->request = $request;
    }

   public function collection()
    {
        try {
            // Collect basic data
            $baseData = DB::table('communities')
        ->join('regions', 'communities.region_id', '=', 'regions.id')
        ->leftJoin('internet_users', 'communities.id', '=', 'internet_users.community_id')
        ->leftJoin('internet_cluster_communities', 'communities.id', '=', 'internet_cluster_communities.community_id')
        ->leftJoin('internet_clusters', 'internet_cluster_communities.internet_cluster_id', '=', 'internet_clusters.id')
        ->leftJoin('public_structures', 'internet_users.public_structure_id', '=', 'public_structures.id')
        ->where('communities.internet_service', '=', 'yes')
        ->select(
            'communities.id',  // Keep ID for groupBy purposes only
            'communities.english_name AS Community',  // Display name instead of ID
            'regions.english_name AS Region',
            'internet_clusters.name AS Network_Cluster',
            'communities.number_of_household AS Households_in_Community',
            DB::raw('COUNT(DISTINCT internet_users.id) AS Contracts_in_Community'),
            DB::raw('COUNT(DISTINCT public_structures.id) AS Public_Contracts'),
            DB::raw('IF(communities.number_of_household > 0, (COUNT(DISTINCT internet_users.id) / communities.number_of_household) * 100, 0) AS Contract_Holders_Percentage'),
            'communities.number_of_people AS People_in_Community',
            DB::raw('MIN(internet_users.start_date) AS Start_Date_First_Contract'),
            
        )
        ->groupBy('communities.id')  
        ->get();

    
    $householdData2 = DB::table('households')
    ->join('internet_users', 'households.id', '=', 'internet_users.household_id') 
    ->select(
        'households.community_id',
        DB::raw('SUM(COALESCE(households.number_of_people, 0)) AS Total_People_with_Internet_Contracts'), 
        DB::raw('SUM(COALESCE(households.number_of_female, 0)) AS Total_Female_with_Internet_Contracts') 
    )
    ->whereNotNull('internet_users.id')  
    ->groupBy('households.community_id')
    ->get();

            // Collect additional data from households
            $householdData = DB::table('households')
                ->select(
                    'community_id',
                    DB::raw('SUM(COALESCE(number_of_female, 0)) AS Total_Female'),
                    DB::raw('SUM(COALESCE(number_of_male, 0)) AS Male'),
                    DB::raw('SUM(COALESCE(number_of_adults, 0)) AS Adult'),
                    DB::raw('SUM(COALESCE(number_of_children, 0)) AS Children')
                )
                ->groupBy('community_id')
                ->get();

            // Collect additional data from camera_communities
            $cameraData = DB::table('camera_communities')
                ->select(
                    'community_id',
                    DB::raw("IF(community_id IS NOT NULL, 'Yes', 'No') AS Has_Cameras"),
                    DB::raw("IF(date IS NOT NULL, date, '') AS Camera_Start_Date")
                )
                ->groupBy('community_id')
                ->get();

            // Collect additional data from donors
            $donorData = DB::table('internet_user_donors')
                ->join('donors', 'internet_user_donors.donor_id', '=', 'donors.id')
                ->select(
                    'internet_user_donors.community_id',
                    DB::raw("GROUP_CONCAT(DISTINCT donors.donor_name SEPARATOR ', ') AS Internet_Donor")
                )
                ->groupBy('internet_user_donors.community_id')
                ->get();

            // Merge data
            $data = $baseData->map(function ($item) use ($householdData,$householdData2, $cameraData, $donorData) {
                // Merge household data only for people with internet contracts
    $household = $householdData2->where('community_id', $item->id)->first();
                
    // If internet contract found in household, sum number_of_people
    if ($household) {
        $item->Total_People_with_Internet_Contracts = $household->Total_People_with_Internet_Contracts;
        $item->Total_Female_with_Internet_Contracts = $household->Total_Female_with_Internet_Contracts;
    } else {
        $item->Total_People_with_Internet_Contracts = 0;  
        $item->Total_Female_with_Internet_Contracts = 0;  
    }
                // Merge household data
                $household = $householdData->where('community_id', $item->id)->first();
                $item->Total_Female = $household ? $household->Total_Female : 0;
                $item->Male = $household ? $household->Male : 0;
                $item->Adult = $household ? $household->Adult : 0;
                $item->Children = $household ? $household->Children : 0;

    
                // Merge camera data
                $camera = $cameraData->where('community_id', $item->id)->first();
                $item->Camera_Start_Date = $camera ? $camera->Camera_Start_Date : '';
                $item->Has_Cameras = $camera ? $camera->Has_Cameras : 'No';
            

                // Merge donor data
                $donor = $donorData->where('community_id', $item->id)->first();
                $item->Internet_Donor = $donor ? $donor->Internet_Donor : '';

                return $item;
            });
    // Merge data and hide ID in final object
            return $baseData->map(function ($item) {
                unset($item->id);  // Remove ID from final object
                return $item;
            });
            return $data;
        } catch (\Exception $e) {
            Log::error('Error fetching data for Excel export: ' . $e->getMessage());
            return collect(); // Return an empty collection in case of error
        }
    }

    public function headings(): array
    {
        return [
            "Community", "Region", "Network cluster", "# of households in community", "# of contracts in community",
            "Public contracts", "% of contract holders", "# of people in community", "Start Date (first contract in community)","# of people connected to contracts", "# of females in served communities", 
            "Female", "Male", "Adult", "Children","Cameras start date","Has Cameras (yes/no)", "Internet Donor",
              "Notes"
        ];
    }

    public function title(): string
    {
        return 'NEW Internet report for donors';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:R1');
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}