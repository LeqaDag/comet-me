<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class FbsSharedUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
            ->leftJoin('household_meters', 'all_energy_meters.id', 
                '=', 'household_meters.energy_user_id')
            ->join('households', 'household_meters.household_id', '=', 'households.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meter_donors.all_energy_meter_id',
                '=', 'all_energy_meters.id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id', 'donors.id')
            ->where('fbs_user_incidents.is_archived', 0)
            ->select([
                'households.english_name as household_name', 'household_meters.user_name',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                DB::raw('concat(donors.donor_name) as donors')
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
        return ["Shared User", "Main User", "Community", "Region", "Sub Region", 
            "Donor"];
    }

    public function title(): string
    {
        return 'FBS Incidents/ Shared Household';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:F1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}