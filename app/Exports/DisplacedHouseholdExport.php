<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class DisplacedHouseholdExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('displaced_households')
            ->join('communities as old_communities', 'displaced_households.old_community_id', 
                'old_communities.id')
            ->leftJoin('communities as new_communities', 'displaced_households.new_community_id', 
                'new_communities.id')
            ->join('households', 'displaced_households.household_id', 'households.id')
            ->leftJoin('energy_systems as old_energy_systems', 'old_energy_systems.id',
                'displaced_households.old_energy_system_id')
            ->leftJoin('energy_systems as new_energy_systems', 'new_energy_systems.id', 
                'displaced_households.new_energy_system_id')
            ->leftJoin('displaced_household_statuses', 'displaced_household_statuses.id', 
                'displaced_households.displaced_household_status_id')
            ->leftJoin('sub_regions', 'displaced_households.sub_region_id', 'sub_regions.id')
            ->where('displaced_households.is_archived', 0)
            ->select('households.english_name as english_name',  
                'displaced_household_statuses.name',
                'old_communities.english_name as old_community_name',
                'new_communities.english_name as new_community_name',
                'displaced_households.old_meter_number',
                'displaced_households.new_meter_number',
                'old_energy_systems.name as old_system', 
                'new_energy_systems.name as new_system',
                'displaced_households.area',
                'sub_regions.english_name as sub_region',
                'displaced_households.displacement_date',
                'displaced_households.notes',
                'displaced_households.system_retrieved'
            );

            
        if($this->request->area) {

            $data->where("displaced_households.area", $this->request->area);
        }
        if($this->request->sub_region) {

            $data->where("sub_regions.id", $this->request->sub_region);
        }
        if($this->request->community) {

            $data->where("old_communities.id", $this->request->community);
        }
        if($this->request->date) {

            $data->where("displaced_households.displacement_date", ">=",  $this->request->date);
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
        return ["Household", "Status", "Old Community", "New Community", "Old Meter Number", 
            "New Meter Number", "Old Energy System", "New Energy System", "Area", 
            "New Place", "Date of displacement", "Notes", "System Retrieved"];
    }

    public function title(): string
    {
        return 'Displaced Households';
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