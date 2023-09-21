<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class CompoundHouseholdExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('compound_households')
            ->join('communities', 'compound_households.community_id', 
                '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'compound_households.household_id', 
                '=', 'households.id')
            ->join('compounds', 'compound_households.compound_id', 
                '=', 'compounds.id')
            ->where('compound_households.is_archived', 0)
            ->select('households.english_name as household',
                'communities.english_name as community_english_name',
                'compounds.english_name as english_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'households.phone_number', 'number_of_male', 'number_of_female', 
                'number_of_children', "number_of_adults");

        if($this->request->community) {

            $data->where("communities.id", $this->request->community);
        }
        if($this->request->region) {

            $data->where("regions.id", $this->request->region);
        }
        if($this->request->compound) {

            $data->where("compounds.id", $this->request->compound);
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
        return ["Household", "Community", "Compound", "Region", "Sub Region", "Phone Number", 
            "# of Male", "# of Female", "# of Children", "# of Adult"];
    }

    public function title(): string
    {
        return 'Households - Sub Communities';
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