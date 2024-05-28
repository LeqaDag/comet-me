<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class PublicStructureExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('public_structures')
            ->join('communities', 'public_structures.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('all_energy_meters', 'public_structures.id', 
                'all_energy_meters.public_structure_id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
                'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors as energy_donor', 'all_energy_meter_donors.donor_id', 
                'energy_donor.id')
            ->leftJoin('all_water_holders', 'public_structures.id', 
                'all_water_holders.public_structure_id')
            ->leftJoin('all_water_holder_donors', 'all_water_holders.id', 
                'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors as water_donor', 'all_water_holder_donors.donor_id', 'water_donor.id')
            ->leftJoin('internet_users', 'public_structures.id', 
                'internet_users.public_structure_id')
            ->leftJoin('internet_user_donors', 'internet_users.id', 
                'internet_user_donors.internet_user_id')
            ->leftJoin('donors as internet_donor', 'internet_user_donors.donor_id', 
                'internet_donor.id')
            ->leftJoin('school_public_structures', 'school_public_structures.public_structure_id', 'public_structures.id')
            ->where('public_structures.is_archived', 0)
            ->select('public_structures.english_name as english_name', 
                'public_structures.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                DB::raw('CASE WHEN all_energy_meters.meter_number IS NOT NULL THEN "Yes" 
                    ELSE "No" END as has_meter'),
                'all_energy_meters.meter_number', 
                DB::raw('group_concat(DISTINCT energy_donor.donor_name) as meter_donor'),
                DB::raw('CASE WHEN all_water_holders.public_structure_id IS NOT NULL THEN "Yes" 
                    ELSE "No" END as has_water'),
                DB::raw('group_concat(DISTINCT water_donor.donor_name) as water_donor'),
                DB::raw('CASE WHEN internet_users.public_structure_id IS NOT NULL THEN "Yes" 
                    ELSE "No" END as has_internet'),
                DB::raw('group_concat(DISTINCT internet_donor.donor_name) as internet_donor'),
                'public_structures.kindergarten_students', 'public_structures.kindergarten_male', 
                'public_structures.kindergarten_female', 
                'school_public_structures.number_of_students', 'school_public_structures.number_of_boys', 
                'school_public_structures.number_of_girls', 'school_public_structures.grade_from',
                'school_public_structures.grade_to',
                'public_structures.notes') 
            ->groupBy('public_structures.id');

        if($this->request->region) { 

            $data->where("regions.id", $this->request->region);
        }
        if($this->request->community) {

            $data->where("communities.id", $this->request->community);
        }
        if($this->request->public) {
            $data->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
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
        return ["English Name", "Arabic Name", "Community", "Region", "Sub Region", 
            "Energy Service", "Meter Number", "Energy Donors", "Water Service", "Water Donors", 
            "Internet Service", "Internet Donors", "Kindergarten Students", "Kindergarten Boys", 
            "Kindergarten Girls", "School Students", "School Boys", "School Girls", 
            "School Grade from", "School Grade to", "Notes"];
    }

    public function title(): string
    {
        return 'All Public Structures';
    }

    /** 
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:U1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}