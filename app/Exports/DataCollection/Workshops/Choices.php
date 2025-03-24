<?php

namespace App\Exports\DataCollection\Workshops;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class Choices implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $regions = DB::table('regions')
            ->where('regions.is_archived', 0)
            ->select(
                DB::raw('"region" as list_name'), 
                'regions.english_name as name',
                'regions.arabic_name as label:Arabic (ar)',
                'regions.english_name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $sub_regions = DB::table('sub_regions')
            ->join('regions', 'sub_regions.region_id', 'regions.id')
            ->where('sub_regions.is_archived', 0)
            ->select(
                DB::raw('"sub_region" as list_name'), 
                'sub_regions.english_name as name',
                'sub_regions.arabic_name as label:Arabic (ar)',
                'sub_regions.english_name as label:English (en)',
                'regions.english_name as region',
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $communities = DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->where('communities.is_archived', 0)
            ->select(
                DB::raw('"community" as list_name'), 
                'communities.english_name as name',
                'communities.arabic_name as label:Arabic (ar)',
                'communities.english_name as label:English (en)',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                DB::raw('false as community')
            )
            ->get();

        $workshopTypes = DB::table('workshop_types')
            ->where('workshop_types.is_archived', 0)
            ->select(
                DB::raw('"workshop_type" as list_name'), 
                'workshop_types.unique_name as name',
                'workshop_types.arabic_name as label:Arabic (ar)',
                'workshop_types.english_name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $users = DB::table('users')
            ->where('users.is_archived', 0)
            ->select(
                DB::raw('"lead_by" as list_name'), 
                'users.name as name',
                'users.name as label:Arabic (ar)',
                'users.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $coTrainers = DB::table('users')
            ->where('users.is_archived', 0)
            ->select(
                DB::raw('"co_trainer" as list_name'), 
                'users.name as name',
                'users.name as label:Arabic (ar)',
                'users.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $query = collect($regions)
            ->merge($sub_regions)
            ->merge($communities)
            ->merge($workshopTypes)
            ->merge($users)
            ->merge($coTrainers); 
        
        return $query;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['list_name', 'name', 'label:Arabic (ar)', 'label:English (en)', 'region', 'sub_region', 'community'];
    }


    public function title(): string
    {
        return 'choices';
    }

    public function startCell(): string
    {
        return 'A1';
    } 


    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:H1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}