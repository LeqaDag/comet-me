<?php

namespace App\Exports;
 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\CommunityDonor; 
use DB;

class CommunityExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles,
    WithEvents
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
        $data = DB::table('communities')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('sub_sub_regions', 'communities.sub_sub_region_id', '=', 'sub_sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                '=', 'community_statuses.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.community_id', 'communities.id')
            ->leftJoin('energy_system_types', 'energy_system_types.id', 
                'all_energy_meters.energy_system_type_id')
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')

            ->leftJoin('community_donors as energy_donors', function ($join) {
                $join->on('communities.id', '=', 'energy_donors.community_id')
                    ->where('energy_donors.service_id', '=', 1); 
            })
            ->leftJoin('donors as energy_donor', 'energy_donors.donor_id', 'energy_donor.id')
        
            ->leftJoin('community_donors as water_donors', function ($join) {
                $join->on('communities.id', '=', 'water_donors.community_id')
                    ->where('water_donors.service_id', '=', 2); 
            })
            ->leftJoin('donors as water_donor', 'water_donors.donor_id', 'water_donor.id')
        
            ->leftJoin('community_donors as internet_donors', function ($join) {
                $join->on('communities.id', '=', 'internet_donors.community_id')
                    ->where('internet_donors.service_id', '=', 3);  
            })
            ->leftJoin('donors as internet_donor', 'internet_donors.donor_id', 'internet_donor.id')
            ->leftJoin('community_representatives', 'community_representatives.community_id', 
                'communities.id')
            ->leftJoin('households as representatives', 'community_representatives.household_id', 
                'representatives.id')
            ->leftJoin('community_roles', 'community_representatives.community_role_id', 
                'community_roles.id')
            ->where('communities.is_archived', 0)
            ->select('communities.english_name as english_name', 
                'communities.arabic_name as arabic_name',
                'regions.english_name as name', 'sub_regions.english_name as subname',
                'sub_sub_regions.english_name as sub_sub_name',
                'number_of_household', 'communities.number_of_people as number_of_people',
                'number_of_compound', 'community_statuses.name as status_name',
                'communities.energy_service', 'communities.energy_service_beginning_year',
                DB::raw('group_concat(DISTINCT energy_system_types.name) as types'),
                DB::raw('group_concat(DISTINCT energy_donor.donor_name) as energy_donors'),
                'communities.water_service', 'communities.water_service_beginning_year',
                DB::raw('group_concat(DISTINCT water_donor.donor_name) as water_donors'),
                'communities.internet_service', 'communities.internet_service_beginning_year', 
                DB::raw('group_concat(DISTINCT internet_donor.donor_name) as internet_donors'),
                DB::raw('group_concat(DISTINCT representatives.english_name) as representatives'),
                DB::raw('group_concat(DISTINCT representatives.phone_number) as phone_numbers'),
                DB::raw('group_concat(DISTINCT community_roles.role) as community_roles')
            )
            ->groupBy('communities.id');


        if($this->request->region) {

            $regionIds = $this->request->region;

            $data->where(function ($query) use ($regionIds) {
                foreach ($regionIds as $regionId) {
                    if (is_array($regionId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($regionId) {
                            $subQuery->select('communities.id')
                                ->from('regions')
                                ->whereIn('communities.region_id', $regionId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($regionId) {
                            $subQuery->select('communities.id')
                                ->from('regions')
                                ->where('communities.region_id', $regionId);
                        });
                    }
                }
            });
        }
        if($this->request->public) {
                 
            $data->leftJoin('public_structures', 'communities.id',
                'public_structures.community_id');

            $publicIds = $this->request->public;

            $data->where(function ($query) use ($publicIds) {
                foreach ($publicIds as $publicId) {
                    if (is_array($publicId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($publicId) {
                            $subQuery->select('public_structures.community_id')
                                ->from('public_structures')
                                ->whereIn('public_structures.public_structure_category_id1', $publicId)
                                ->orWhereIn('public_structures.public_structure_category_id2', $publicId)
                                ->orWhereIn('public_structures.public_structure_category_id3', $publicId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($publicId) {
                            $subQuery->select('public_structures.community_id')
                                ->from('public_structures')
                                ->where('public_structures.public_structure_category_id1', $publicId)
                                ->orWhere('public_structures.public_structure_category_id2', $publicId)
                                ->orWhere('public_structures.public_structure_category_id3', $publicId);
                        });
                    }
                }
            });
        }
        if($this->request->system_type) {

            $systemTypesIds = $this->request->system_type;

            $data->where(function ($query) use ($systemTypesIds) {
                foreach ($systemTypesIds as $systemTypesId) {
                    if (is_array($systemTypesId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($systemTypesId) {
                            $subQuery->select('all_energy_meters.community_id')
                                ->from('all_energy_meters')
                                ->whereIn('all_energy_meters.energy_system_type_id', $systemTypesId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($systemTypesId) {
                            $subQuery->select('all_energy_meters.community_id')
                                ->from('all_energy_meters')
                                ->where('all_energy_meters.energy_system_type_id', $systemTypesId);
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
                        $query->orWhereIn('communities.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('community_donors.community_id')
                                ->from('community_donors')
                                ->whereIn('community_donors.donor_id', $donorId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('community_donors.community_id')
                                ->from('community_donors')
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
        return ["English Name", "Arabic Name", "Region", "Sub Region", "Sub Sub Region",
            "Number of Households", "Number of People",  "Number of Compounds", "Status", 
            "Energy Service", "Energy Service Year", "Energy System Type", "Energy Donors",
            "Water Service", "Water Service Year", "Water Donors",
            "Internet Service", "Internet Service Year", "Internet Donors",
            "Community Representatives", "Phone Numbers", "Role"];
    }


    public function title(): string
    {
        return 'All Communities';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
              
                $event->sheet->getDelegate()->freezePane('A2');  
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:V1');
        
        return [ 
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}