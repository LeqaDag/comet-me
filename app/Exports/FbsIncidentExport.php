<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class FbsIncidentExport implements WithMultipleSheets, ShouldAutoSize
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
            new FbsMainUsers($this->request),
            new FbsSharedUsers($this->request)
        ];

        return $sheets;
    }
}