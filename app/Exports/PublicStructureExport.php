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
            ->where('public_structures.is_archived', 0)
            ->select('public_structures.english_name as english_name', 
                'public_structures.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'all_energy_meters.meter_number', 'public_structures.notes');

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
            "Meter Number", "Notes"];
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
        $sheet->setAutoFilter('A1:G1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}