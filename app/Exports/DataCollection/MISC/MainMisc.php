<?php

namespace App\Exports\DataCollection\MISC;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class MainMisc implements WithMultipleSheets, ShouldAutoSize
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