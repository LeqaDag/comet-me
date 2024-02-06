<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class WaterUserExport implements WithMultipleSheets, ShouldAutoSize
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
            new WaterUserHolder($this->request),
            new WaterCommunityHolder($this->request),
            new WaterCommunityNetwork($this->request)
        ];

        return $sheets;
    }
}