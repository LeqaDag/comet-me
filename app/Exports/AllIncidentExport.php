<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class AllIncidentExport implements WithMultipleSheets, ShouldAutoSize
{
    use Exportable;

    protected $request;
 
    function __construct($request) {
        $this->request = $request;
    }

    /**
     * @return array
     */ 
    public function sheets(): array
    {
        $sheets = [
            new MgIncidentSystem($this->request),
            new MgIncidentHouseholdsAffected($this->request),
            new FbsMainUsers($this->request), 
            new FbsSharedUsers($this->request),
            new WaterMainUsers($this->request),
            new WaterSharedUsers($this->request), 
            new NetworkIncidentSystem($this->request),
            new NetworkIncidentAreaAffected($this->request),
            new NetworkIncidentHouseholdsAffected($this->request),
            new InternetUserIncidentExport($this->request),
            new CameraIncidentExport($this->request),
        ];

        return $sheets;
    }
}