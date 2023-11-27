<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class EnergySystemExport implements WithMultipleSheets, ShouldAutoSize
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
            new EnergySystem($this->request),
            new EnergySystemBattery($this->request),
            new EnergySystemPv($this->request),
            new EnergyChargeController($this->request),
            new EnergyInverter($this->request),
            new EnergyRelayDriver($this->request),
            new EnergyLoadRelay($this->request),
            new EnergyBatteryProccessor($this->request),
            new EnergyRcc($this->request),
            new EnergyLogger($this->request),
            new EnergyGenerator($this->request),
            new EnergyWindTurbine($this->request),
            new EnergyMcbPv($this->request),
            new EnergyMcbController($this->request),
            new EnergyMcbInventer($this->request),
            new EnergyAirConditioner($this->request),
        ];

        return $sheets;
    }
}