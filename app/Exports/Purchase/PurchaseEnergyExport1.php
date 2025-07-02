<?php

namespace App\Exports\Purchase;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class PurchaseEnergyExport1 implements WithMultipleSheets, ShouldAutoSize
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

            new PurchaseSummary($this->request),
            new PurchaseAllMeter($this->request)
        ];

        return $sheets;
    }
}