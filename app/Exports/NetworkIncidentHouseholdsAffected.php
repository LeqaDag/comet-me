<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class NetworkIncidentHouseholdsAffected implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles
{
    protected $request;

    function __construct($request) {

        $this->request = $request;
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection() 
    { 
        $query = DB::table('internet_network_affected_households')
            ->join('households', 'internet_network_affected_households.household_id', 'households.id')
            ->join('internet_network_incidents', 'internet_network_incidents.id',
                'internet_network_affected_households.internet_network_incident_id') 
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('incidents', 'internet_network_incidents.incident_id', '=', 'incidents.id')
            ->where('internet_network_incidents.is_archived', 0)
            ->select(  
                'households.english_name as household_name',
                'communities.english_name as community_name',
                'regions.english_name as region', 
                'incidents.english_name as incident',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
            );

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->leftJoin('internet_users', 'internet_users.household_id', 'households.id')
                ->leftJoin('internet_user_donors', 'internet_user_donors.internet_user_id',
                    'internet_users.id')
                ->leftJoin('donors', 'internet_user_donors.donor_id', 'donors.id')
                ->where('internet_users.is_archived', 0)
                ->where("internet_user_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("internet_network_incidents.date", ">=", $this->request->date);
        }

        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Household", "Community", "Region", "Incident",
            "# of Male", "# of Female", "# of Adults", "# of Children"];
    }

    public function title(): string
    {
        return 'Network Incidents Households Affected';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:H1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}