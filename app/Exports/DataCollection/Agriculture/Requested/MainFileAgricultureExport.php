<?php

namespace App\Exports\DataCollection\Agriculture\Requested;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class MainFileAgricultureExport implements WithMultipleSheets, ShouldAutoSize
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
            new Survey($this->request),
            new Choices($this->request)
        ];

        return $sheets;
    }
}