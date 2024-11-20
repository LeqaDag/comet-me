<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class IncidentsReport implements WithMultipleSheets, ShouldAutoSize
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
            new IncidentSummary($this->request), 
            new IncidentStopWorkOrder($this->request),
            //new IncidentAffectedHouseholds($this->request)
        ];

        return $sheets;
    }
}