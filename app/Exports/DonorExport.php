<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class DonorExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->select(
                'communities.english_name as english_name', 
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'communities.number_of_people',
                'communities.number_of_household',
                'service_types.service_name',
                'donors.donor_name'
            );

            
        if($this->request->community) {

            $data->where("communities.id", $this->request->community);
        }
        if($this->request->service) {

            $data->where("service_types.id", $this->request->service);
        }
        if($this->request->donor) {

            $data->where("donors.id", $this->request->donor);
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
        return ["Community",  "Region", "Sub Region", "# of People",
            "# of Families", "Service Type", "Donors"];
    }

    public function title(): string
    {
        return 'Donors';
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