<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class CameraRequestedExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('requested_cameras')
            ->join('communities', 'requested_cameras.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('camera_request_statuses', 'requested_cameras.camera_request_status_id', 'camera_request_statuses.id')
            ->leftJoin('users', 'users.id', 'requested_cameras.user_id')
            ->where('requested_cameras.is_archived', 0)
            ->select([
                'communities.english_name', 'regions.english_name as region', 
                'sub_regions.english_name as sub_region', 
                'communities.internet_service', 'communities.internet_service_beginning_year', 
                'communities.number_of_household',
                'requested_cameras.date', 'camera_request_statuses.name as status', 
                'users.name', 'requested_cameras.referred_by',
                'requested_cameras.notes'
            ])
            ->groupBy('requested_cameras.id')
            ->orderBy('requested_cameras.date', 'desc'); 

        if($this->request->community) {

            $query->where("communities.id", $this->request->community);
        } 
        if($this->request->request_status) {

            $query->where("camera_request_statuses.id", $this->request->request_status);
        }
        if($this->request->date) {

            $query->where("requested_cameras.date", ">=", $this->request->date);
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
        return ["Community", "Region", "Sub Region", "Has Internet?", "Internet Year", "# of Households",
            "Requested Date", "Request Status", "Who took the request", "Reffered By", "Notes"];
    }

    public function title(): string
    {
        return 'Requested Cameras';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:K1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}