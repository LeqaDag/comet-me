<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class DonorSummary implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('community_donors')
            ->join('communities', 'community_donors.community_id', 'communities.id')
            ->join('households', 'communities.id', 'households.community_id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->where('community_donors.is_archived', 0)
            ->where('communities.is_archived', 0)
            ->select(
                'communities.english_name as english_name', 
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'communities.number_of_people',
                'communities.number_of_household',
                DB::raw('COUNT(CASE WHEN service_types.id = 1 AND
                    households.is_archived = 0 AND 
                    households.household_status_id = 4
                    THEN 1 ELSE NULL END) as served_families'),
                DB::raw('COUNT(CASE WHEN service_types.id = 2 AND
                    households.is_archived = 0 AND  
                    households.water_system_status = "Served"
                    THEN 1 ELSE NULL END) as water_served_families'),
                DB::raw('COUNT(CASE WHEN service_types.id = 3 AND
                    households.is_archived = 0 AND 
                    households.internet_system_status = "Served"
                    THEN 1 ELSE NULL END) as internet_served_families'),
                'service_types.service_name',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors')
            ) 
            ->groupBy('communities.id', 'service_types.id');

        if($this->request->community) {

            $communityIds = $this->request->community;

            $data->where(function ($query) use ($communityIds) {
                foreach ($communityIds as $communityId) {
                    if (is_array($communityId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($communityId) {
                            $subQuery->select('communities.id')
                                ->from('communities')
                                ->whereIn('communities.id', $communityId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($communityId) {
                            $subQuery->select('communities.id')
                                ->from('communities')
                                ->where('communities.id', $communityId);
                        });
                    }
                }
            });
        }

        if($this->request->service) {

            $serviceIds = $this->request->service;

            $data->where(function ($query) use ($serviceIds) {
                foreach ($serviceIds as $serviceId) {
                    if (is_array($serviceId)) {
                        $query->orWhereIn('community_donors.id', function ($subQuery) use ($serviceId) {
                            $subQuery->select('community_donors.id')
                                ->from('service_types')
                                ->whereIn('community_donors.service_id', $serviceId);
                        });
                    } else {
                        $query->orWhereIn('community_donors.id', function ($subQuery) use ($serviceId) {
                            $subQuery->select('community_donors.id')
                                ->from('service_types')
                                ->where('community_donors.service_id', $serviceId);
                        });
                    }
                }
            });
        }
        if($this->request->donor) {

            $donorIds = $this->request->donor;

            $data->where(function ($query) use ($donorIds) {
                foreach ($donorIds as $donorId) {
                    if (is_array($donorId)) {
                        $query->orWhereIn('community_donors.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('community_donors.id')
                                ->from('donors')
                                ->whereIn('community_donors.donor_id', $donorId);
                        });
                    } else {
                        $query->orWhereIn('community_donors.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('community_donors.id')
                                ->from('donors')
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
        return ["Community",  "Region", "Sub Region", "# of People", "# of Families", 
            "# of Energy Served Families", "# of Water Served Families",   
            "# of Internet Served Families", "Service Type", "Donors"];
    }

    public function title(): string
    {
        return 'Donor Summary';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:J1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}