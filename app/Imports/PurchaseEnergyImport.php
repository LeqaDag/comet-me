<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\AllEnergyMeter;
use App\Models\AllEnergyPurchaseMeter;
use Carbon\Carbon;

class PurchaseEnergyImport implements ToCollection, WithHeadingRow
{ 
    protected $tpe; 

    function __construct($type) {

        $this->type = $type;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Process first file
        if ($this->type == 1) {

            $this->processFirstFile($row);
        }
        
        // Process second file
        // if ($this->type == 2) {

        //     $this->processSecondFile($row);
        // }
    }

    /**
     * Process data from the first file.
     *
     * @param array $row
     * @return void
     */
    private function processFirstFile(array $row)
    {
        $meterVending = new AllEnergyVendingMeter();

        if($row["meter_no"]) {

            $meterVending->meter_number = $row["meter_no"]; 
            $allEnergyMeter = AllEnergyMeter::where('meter_number', $row["meter_no"])->first();
            
            if($allEnergyMeter) { 
                
                $meterVending->all_energy_meter_id = $allEnergyMeter->id;
                $meterVending->installation_date = $allEnergyMeter->installation_date;
                $meterVending->daily_limit = $allEnergyMeter->daily_limit;
                $meterVending->community_id = $allEnergyMeter->community_id;
                $meterVending->meter_case_id = $allEnergyMeter->meter_case_id;
            }
            
            $meterVending->last_purchase_date = $row["date"];
            $firstDate = Carbon::parse($row["date"]);
            $secondDate = Carbon::now(); 
            $differenceInDays = $secondDate->diffInDays($firstDate);
            $meterVending->days = $differenceInDays;
            $meterVending->save();
        }
    }

    /**
     * Process data from the second file.
     *
     * @param array $row
     * @return void
     */
    private function processSecondFile(array $row)
    {
        $existMeterVending = AllEnergyVendingMeter::where('meter_number', $row["meter_no"])->first();

        if($existMeterVending) {

            $firstDate = Carbon::parse($existMeterVending->last_purchase_date);
            $secondDate = Carbon::now(); 
            

            $differenceInDays = $secondDate->diffInDays($firstDate);
           
            $existMeterVending->days = $differenceInDays;
            $existMeterVending->save();
        }
    }
}
