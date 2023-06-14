<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class SubCommunityExport implements FromCollection, WithHeadings, WithTitle
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
        $data = DB::table('sub_communities')
            ->join('communities', 'sub_communities.community_id', 
                '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                '=', 'community_statuses.id')
            ->where('communities.is_archived', 0)
            ->select('sub_communities.english_name as english_name', 
                'sub_communities.arabic_name as arabic_name', 
                'communities.english_name as community_english_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'community_statuses.name as status');

        if($this->request->community) {

            $data->where("communities.id", $this->request->community);
        }
        if($this->request->region) {

            $data->where("regions.id", $this->request->region);
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
        return ["Sub Community English Name", "Sub Community Arabic Name", "Community", 
            "Region", "Sub Region", "Community Status"];
    }

    public function title(): string
    {
        return 'Sub Communities';
    }
}