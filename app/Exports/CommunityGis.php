<?php

namespace App\Exports;
 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\CommunityDonor; 
use DB;

class CommunityGis implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles,
    WithEvents
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
        $data = DB::table('communities')
            ->leftJoin('displaced_households', 'displaced_households.old_community_id', 
                'communities.id')
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('community_donors as energy_donors', function ($join) {
                $join->on('communities.id', 'energy_donors.community_id')
                    ->where('energy_donors.service_id', 1); 
            })
            ->leftJoin('donors as energy_donor', 'energy_donors.donor_id', 'energy_donor.id')
            ->leftJoin('community_donors as water_donors', function ($join) {
                $join->on('communities.id', 'water_donors.community_id')
                    ->where('water_donors.service_id', 2); 
            })
            ->leftJoin('donors as water_donor', 'water_donors.donor_id', 'water_donor.id')
            ->leftJoin('community_donors as internet_donors', function ($join) {
                $join->on('communities.id', 'internet_donors.community_id')
                    ->where('internet_donors.service_id', 3);  
            })
            ->leftJoin('donors as internet_donor', 'internet_donors.donor_id', 'internet_donor.id')
            ->select(
                'communities.english_name as english_name', 'communities.latitude', 
                'communities.longitude', 'communities.location_gis', 'number_of_household', 
                'communities.energy_service_beginning_year',
                'communities.water_service_beginning_year',
                'communities.internet_service_beginning_year', 
                DB::raw('YEAR(displaced_households.displacement_date) as displacement_year'),
                DB::raw('group_concat(DISTINCT energy_donor.donor_name) as energy_donors'),
                DB::raw('group_concat(DISTINCT water_donor.donor_name) as water_donors'),
                DB::raw('group_concat(DISTINCT internet_donor.donor_name) as internet_donors')
            )
            ->groupBy('communities.id');


        if($this->request->donor) {

            $donorIds = $this->request->donor;
        
            $data->where(function ($query) use ($donorIds) {
                foreach ($donorIds as $donorId) {
                    if (is_array($donorId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('community_donors.community_id')
                                ->from('community_donors')
                                ->whereIn('community_donors.donor_id', $donorId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('community_donors.community_id')
                                ->from('community_donors')
                                ->where('community_donors.donor_id', $donorId);
                        });
                    }
                }
            });
        }

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community Name", "Latitude", "Longitude", "Location", 
            "Number of Households", "Energy Service Year", "Water Service Year", 
            "Internet Service Year", "Displacement year", "Energy Donors", "Water Donors", 
            "Internet Donors"];
    }


    public function title(): string
    {
        return 'Communities With GIS Data';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
              
                $event->sheet->getDelegate()->freezePane('A2');  
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:L1');
        
        return [ 
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}