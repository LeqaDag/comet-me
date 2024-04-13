<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class EnergyCostExport implements WithMultipleSheets, ShouldAutoSize
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
            new EnergyCostInstallation($this->request),
            new EnergyRequestedHousehold($this->request),
            new EnergyCostDonor($this->request),
            new EnergyCostSystem($this->request),
            new EnergyCostSystemComponent($this->request),
            new Energy\Cost\EnergyBattery($this->request),
            new Energy\Cost\EnergyBatteryMount($this->request),
            new Energy\Cost\EnergyPv($this->request),
            new Energy\Cost\EnergyPvMount($this->request),
            new Energy\Cost\EnergyController($this->request),
            new Energy\Cost\EnergyInventor($this->request),
            new Energy\Cost\EnergyLogger($this->request),
            new Energy\Cost\EnergyControlCenter($this->request),
            new Energy\Cost\EnergyRelayDriver($this->request),
            new Energy\Cost\EnergyLoadRelay($this->request),
            new Energy\Cost\EnergyBsp($this->request),
            new Energy\Cost\EnergyBts($this->request),
            new Energy\Cost\EnergyGenerator($this->request),
            new Energy\Cost\EnergyTurbine($this->request),
            new Energy\Cost\EnergyPvMcb($this->request),
            new Energy\Cost\EnergyControllerMcb($this->request),
            new Energy\Cost\EnergyInventerMcb($this->request),
            new Energy\Cost\EnergyAirConditioner($this->request),
            new Energy\Cost\EnergyHouseWiring($this->request),
            new Energy\Cost\EnergyElectricityRoom($this->request),
            new Energy\Cost\EnergyElectricityRoomBos($this->request),
            new Energy\Cost\EnergyGrid($this->request),
            new Energy\Cost\EnergyFbsCabinet($this->request),
            new Energy\Cost\EnergyFbsWiring($this->request),
            new Energy\Cost\EnergyFbsLock($this->request),
            new Energy\Cost\EnergyFbsFan($this->request),
        ];

        return $sheets;
    }
}