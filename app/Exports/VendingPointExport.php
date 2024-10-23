<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB; 

class VendingPointExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,
    WithStyles, WithEvents
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
        $data = DB::table('vendors')
            ->join('vendor_user_names', 'vendors.vendor_username_id', 'vendor_user_names.id')
            ->leftJoin('vendor_regions', 'vendors.vendor_region_id', 'vendor_regions.id')
            ->leftJoin('communities', 'vendors.community_id', 'communities.id')
            ->leftJoin('towns', 'vendors.town_id', 'towns.id')
            ->where('vendors.is_archived', 0)
            ->select(
                'vendors.english_name as english_name',
                'vendors.arabic_name as arabic_name',
                'vendor_regions.english_name as region', 'vendors.phone_number',
                'vendors.additional_phone_number',
                DB::raw('IFNULL(communities.english_name, towns.english_name) 
                    as exported_value'),
                'vendor_user_names.name'
            )->groupBy('vendors.id');

        if($this->request->vendor_region) {
            $data->where("vendor_regions.id", $this->request->vendor_region);
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
        return ["Vendor (English)", "Vendor (Arabic)", "Region", "Phone Number",
            "Additional Phone Number", "Place", "Username"];
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
        $sheet->setAutoFilter('A1:G1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Vending Points';
    }
}