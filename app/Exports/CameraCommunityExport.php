<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class CameraCommunityExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('camera_communities')
            ->leftJoin('communities', 'camera_communities.community_id', 'communities.id')
            ->leftJoin('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('repositories', 'camera_communities.repository_id', 'repositories.id') 
            ->leftJoin('regions as repository_regions', 'repositories.region_id', 
                'repository_regions.id') 
            ->leftJoin('sub_regions as repository_sub_regions', 'repositories.sub_region_id', 
                'repository_sub_regions.id')
            ->leftJoin('households', 'camera_communities.household_id', 'households.id')
            ->leftJoin('camera_community_types', 'camera_communities.id', 
                'camera_community_types.camera_community_id')
            ->leftJoin('cameras', 'camera_community_types.camera_id', 'cameras.id')
            ->leftJoin('nvr_community_types', 'camera_communities.id', 
                'nvr_community_types.camera_community_id')
            ->leftJoin('nvr_cameras', 'nvr_community_types.nvr_camera_id', 'nvr_cameras.id')
            ->leftJoin('camera_community_donors', 'camera_communities.id', 'camera_community_donors.camera_community_id')
            ->leftJoin('donors', 'camera_community_donors.donor_id', 'donors.id')
            ->where('camera_communities.is_archived', 0)
            ->select([
                DB::raw('IFNULL(communities.english_name, repositories.name) as name'),
                DB::raw('IFNULL(regions.english_name, repository_regions.english_name) as region'),
                DB::raw('IFNULL(sub_regions.english_name, repository_sub_regions.english_name) as sub_region'),
                'communities.number_of_household',
                DB::raw("CASE WHEN camera_communities.comet_internal = 0 THEN 'No' 
                    ELSE 'Yes' END AS comet_internal"),
                'communities.internet_service', 'communities.internet_service_beginning_year', 
                'camera_communities.date',
                'households.english_name as english_name',
                DB::raw('SUM(DISTINCT camera_community_types.number) as camera_number'),
                DB::raw('group_concat(DISTINCT cameras.model) as cameras'),
                DB::raw('group_concat(DISTINCT camera_community_types.number) as camera_numbers'),
                DB::raw('SUM(DISTINCT nvr_community_types.number) as nvr_number'),
                DB::raw('group_concat(DISTINCT nvr_cameras.model) as nvrs'),
                DB::raw('group_concat(DISTINCT nvr_community_types.number) as nvr_numbers'),
                DB::raw('group_concat(DISTINCT CASE WHEN camera_community_donors.is_archived = 0 
                    THEN donors.donor_name END) as donors'),
                'camera_communities.notes'
            ])
            ->groupBy('camera_communities.id')
            ->orderBy('camera_communities.date', 'desc'); 

        if($this->request->sub_region) {

            $query->where("sub_regions.id", $this->request->sub_region)
                ->orWhere("repository_sub_regions.id", $this->request->sub_region);
        }
        if($this->request->community) {

            $query->where("communities.id", $this->request->community);
        } 
        if($this->request->date) {

            $query->where("camera_communities.date", ">=", $this->request->date);
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
        return ["Community", "Region", "Sub Region", "# of Households", "Comet-Me Internaly", "Has Internet?", "Internet Year",
            "Installation Date", "Responsible", "# of Cameras", "Camera Models", "Camera Number", "# of NVRs", "NVR Models", 
            "NVR Number", "Donors", "Notes"];
    }

    public function title(): string
    {
        return 'Installed Cameras';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:Q1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}