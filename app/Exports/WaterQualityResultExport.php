<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class WaterQualityResultExport implements FromCollection, WithHeadings
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
        $data = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->leftJoin('public_structures', 'water_quality_results.public_structure_id', 
                '=', 'public_structures.id')
            ->select('households.english_name as english_name', 
                'public_structures.english_name as public_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'water_quality_results.date', 'water_quality_results.year',  
                'water_quality_results.cfu',
                'water_quality_results.fci', 'water_quality_results.ec',
                'water_quality_results.ph');

        if($this->request->community) {
            $data->where("communities.english_name", $this->request->community);
        } 
        if($this->request->household) {
            $data->where("households.english_name", $this->request->household);
        }
        if($this->request->from_date) {
            $data->where("water_quality_results.date", ">=", $this->request->from_date);
        }
        if($this->request->to_date) {
            $data->where("water_quality_results.date", "<=", $this->request->to_date);
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
        return ["Water Holder", "Public Name", "Community", "Region", "Sub Region",
            "Date", "Year", "CFU",  "FCI", "EC", "PH"];
    }
}