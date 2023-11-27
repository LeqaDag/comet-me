<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class MgIncidentHouseholdsAffected implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('mg_affected_households')
            ->join('households', 'mg_affected_households.household_id', 'households.id')
            ->join('mg_incidents', 'mg_affected_households.mg_incident_id', 
                'mg_incidents.id') 
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('energy_systems', 'mg_incidents.energy_system_id', '=', 'energy_systems.id')
            ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
            ->where('mg_affected_households.is_archived', 0)
            ->select(
                'households.english_name as household_name',
                'energy_systems.name as energy_name', 
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

            $query->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
                ->leftJoin('all_energy_meter_donors', 'all_energy_meter_donors.all_energy_meter_id',
                    'all_energy_meters.id')
                ->leftJoin('donors', 'all_energy_meter_donors.donor_id', 'donors.id')
                ->where("all_energy_meter_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("mg_incidents.date", ">=", $this->request->date);
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
        return ["Household", "MG System", "Community", "Region", "Incident",
            "# of Male", "# of Female", "# of Adults", "# of Children"];
    }

    public function title(): string
    {
        return 'Households Affected';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:I1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}