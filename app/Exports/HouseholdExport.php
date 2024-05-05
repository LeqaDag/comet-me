<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class HouseholdExport implements WithMultipleSheets, ShouldAutoSize
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

            new Household\HouseholdSummary($this->request), 
            new Household\AllHousehold($this->request),
            new Household\MissingAllInfo($this->request),
            new Household\DiscrepancyHousehold($this->request),
        ];

        return $sheets;
    }
}