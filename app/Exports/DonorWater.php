<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class DonorWater implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data =  DB::table('all_water_holders')
            ->leftJoin('all_water_holder_donors', 'all_water_holders.id', 
                'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('households', 'all_water_holders.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_water_holders.public_structure_id', 
                'public_structures.id')
            ->leftJoin('donors', 'all_water_holder_donors.donor_id', 'donors.id')
            ->join('communities', function ($join) {
                $join->on('households.community_id', 'communities.id')
                    ->orOn('public_structures.community_id', 'communities.id');
            })
            ->leftJoin('public_structures as total_public_structures', 'communities.id', 
                'total_public_structures.community_id')
            ->select(
                'communities.english_name as community_name',
                'communities.number_of_household',
                DB::raw('COUNT(DISTINCT CASE WHEN households.id IS NOT NULL THEN 
                    all_water_holders.id END) as served_households'),
                DB::raw('COUNT(DISTINCT total_public_structures.id) as total_public_structures'),
                DB::raw('COUNT(DISTINCT CASE WHEN public_structures.id IS NOT NULL 
                    THEN all_water_holders.id END) as served_public_structures'),
                DB::raw('GROUP_CONCAT(DISTINCT donors.donor_name) as donors_list')
            )
            ->groupBy('communities.id', 'communities.english_name', 'donors.id');

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
  
        if($this->request->donor) {

            $donorIds = $this->request->donor;

            $data->where(function ($query) use ($donorIds) {
                foreach ($donorIds as $donorId) {
                    if (is_array($donorId)) {
                        $query->orWhereIn('donors.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('donors.id')
                                ->from('donors')
                                ->whereIn('donors.id', $donorId);
                        });
                    } else {
                        $query->orWhereIn('donors.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('donors.id')
                                ->from('donors')
                                ->where('donors.id', $donorId);
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
        return ["Community", "# of Families", "# of Served Families", 
            "# of Public Structures", "# of Served Public Structures", "Donors"];
    }

    public function title(): string
    {
        return 'Water Donors';
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