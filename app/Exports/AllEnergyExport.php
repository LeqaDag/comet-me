<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class AllEnergyExport implements WithMultipleSheets, ShouldAutoSize
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
            new EnergyHolders($this->request),
            // new EnergyUsers($this->request),
            // new HouseholdMeters($this->request),
            // new PublicMeters($this->request)
        ];

        return $sheets;
    }
}