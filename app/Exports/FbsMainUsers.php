<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class FbsMainUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('fbs_user_incidents')
            ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('all_energy_meters', 'fbs_user_incidents.energy_user_id', 
                '=', 'all_energy_meters.id')
            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_small_infrastructures', 
                'fbs_user_incidents.incident_status_small_infrastructure_id', 
                '=', 'incident_status_small_infrastructures.id')
            ->leftJoin('fbs_incident_equipment', 'fbs_incident_equipment.fbs_user_incident_id', 
                '=', 'fbs_user_incidents.id')
            ->leftJoin('incident_equipment', 'fbs_incident_equipment.incident_equipment_id', 
                '=', 'incident_equipment.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meter_donors.all_energy_meter_id',
                '=', 'all_energy_meters.id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id', 'donors.id')
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('fbs_user_incidents.is_archived', 0)
            ->select([
                'households.english_name as household_name', 'all_energy_meters.is_main',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'communities.number_of_people',
                'incidents.english_name as incident', 
                'fbs_user_incidents.year', 'fbs_user_incidents.date', 
                'incident_status_small_infrastructures.name as fbs_status',
                DB::raw('concat(donors.donor_name) as donors'),
                DB::raw('group_concat(incident_equipment.name) as equipment'),
                'fbs_user_incidents.notes'
            ])
            ->groupBy('fbs_user_incidents.id');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("fbs_user_incidents.date", ">=", $this->request->date);
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
        return ["Energy User", "Main Holder", "Main User", "Community", "Region", "Sub Region", 
            "# of People", "Incident", "Incident Year", "Incident Date", "Status", 
            "Donor", "Equipment Damaged", "Notes"];
    }

    public function title(): string
    {
        return 'FBS Incidents';
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