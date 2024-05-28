<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Community;
use App\Models\PublicStructure;
use App\Models\AllEnergyMeter;
use App\Models\AllEnergyVendingMeter;
use App\Models\Household;
use Carbon\Carbon;
use Excel;
use DB;

class PurchaseEnergyImport implements ToModel, WithHeadingRow
{ 

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $meterVending = new AllEnergyVendingMeter();
        $meterVending->meter_number = $row["meter_no"]; 
        $allEnergyMeter = AllEnergyMeter::where('meter_number', $row["meter_no"])->first();
        
        if($allEnergyMeter) {
            
            $meterVending->all_energy_meter_id = $allEnergyMeter->id;
            $meterVending->installation_date = $allEnergyMeter->installation_date;
            $meterVending->daily_limit = $allEnergyMeter->daily_limit;
            $meterVending->community_id = $allEnergyMeter->community_id;
            $meterVending->meter_case_id = $allEnergyMeter->meter_case_id;
        }
        $meterVending->last_purchase_date = $row["Last_purchase_date"];
        $meterVending->save();
    }
}
