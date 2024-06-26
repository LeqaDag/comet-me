<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ActiveInternetUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'internet_users.household_id', '=', 'households.id')
            ->join('internet_statuses', 'internet_users.internet_status_id', '=', 'internet_statuses.id')
            ->leftJoin('community_donors', 'community_donors.community_id', '=', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('households.internet_system_status', "Served")
            ->where('internet_users.is_archived', 0)
            ->select('households.english_name as english_name', 
                'households.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'internet_users.start_date', 'internet_statuses.name', 
                'internet_users.number_of_contract');

                 
        if($this->request->region) {

            $data->where("regions.english_name", $this->request->region);
        }

        if($this->request->community) {

            $data->where("communities.english_name", $this->request->community);
        }
        if($this->request->start_date) {
            
            $data->where("internet_users.start_date", ">=", $this->request->start_date);
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
        return ["Internet Holder", "Arabic Name", "Community", "Region", "Sub Region", 
            "Start Date", "Internet Status", "Number of Contracts"];
    }

    public function title(): string
    {
        return 'Internet Users';
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
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}