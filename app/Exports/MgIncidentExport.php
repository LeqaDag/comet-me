<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class MgIncidentExport implements WithMultipleSheets, ShouldAutoSize
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
            new MgIncidentHouseholdsAffected($this->request)
        ];

        return $sheets;
    }
}