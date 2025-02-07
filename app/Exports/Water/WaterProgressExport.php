<?php

namespace App\Exports\Water;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class WaterProgressExport implements WithMultipleSheets, ShouldAutoSize
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

            new WaterProgressSummary($this->request), 
            //new WaterNetworkHolder($this->request),
            ///new WaterSystemHolder($this->request)
        ];

        return $sheets;
    }
}