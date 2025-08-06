<?php

namespace App\Exports\Maintenance;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class AllMaintenanceExport implements WithMultipleSheets, ShouldAutoSize
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

            new MaintenanceSummary($this->request),
            new TicketSummary($this->request),
            new EnergySummary($this->request),
            new WaterSummary($this->request),
            new InternetSummary($this->request),
            new MaintenanceLogs($this->request), 
        ];

        return $sheets;
    }
}