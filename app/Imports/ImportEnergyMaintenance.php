<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\AllEnergyMeter;
use App\Models\AllEnergyVendingMeter;
use App\Models\Community;
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\EnergyBattery;
use App\Models\EnergyPv;
use App\Models\EnergyAirConditioner;
use App\Models\EnergyBatteryStatusProcessor;
use App\Models\EnergyBatteryTemperatureSensor;
use App\Models\EnergyChargeController;
use App\Models\EnergyGenerator;
use App\Models\EnergyInverter;
use App\Models\EnergyLoadRelay;
use App\Models\EnergyMcbAc;
use App\Models\EnergyMcbPv;
use App\Models\EnergyMonitoring;
use App\Models\EnergyWindTurbine;
use App\Models\EnergyMcbChargeController;
use App\Models\EnergyMcbInverter;
use App\Models\EnergyRelayDriver;
use App\Models\EnergyRemoteControlCenter;
use App\Models\EnergySystem;
use App\Models\EnergySystemRelayDriver;
use App\Models\EnergySystemBattery;
use App\Models\EnergySystemMonitoring;
use App\Models\EnergySystemPv;
use App\Models\EnergySystemChargeController;
use App\Models\EnergySystemRemoteControlCenter;
use App\Models\EnergySystemWindTurbine;
use App\Models\EnergySystemGenerator;
use App\Models\EnergySystemBatteryStatusProcessor;
use App\Models\EnergySystemBatteryTemperatureSensor;
use App\Models\EnergySystemInverter;
use App\Models\EnergySystemLoadRelay;
use App\Models\EnergySystemMcbPv;
use App\Models\EnergySystemMcbChargeController;
use App\Models\EnergySystemMcbInverter;
use App\Models\EnergySystemAirConditioner;
use App\Models\ElectricityMaintenanceCall;
use App\Models\ElectricityMaintenanceCallUser;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceElectricityAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\MeterCase;
use Carbon\Carbon;
use Excel;

class ImportEnergyMaintenance implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Get all meter numbers from vending file
        // $meterVending = new AllEnergyVendingMeter();
        // $meterVending->meter_number = $row["meter_number"]; 
        // $meterVending->installation_date = $row["date"];
        // $meterVending->last_purchase_date = $row["last_purchase_date"];
        // $meterVending->meter_case_id = $row["meter_case_id"];
        // $meterVending->notes = $row["notes"];
        // $meterVending->save();
        // end vending 



        $energySystem = EnergySystem::where("name", $row["name"])->first();
        $battery = EnergyBattery::where("battery_model", $row["model"])->first();
        $exist = EnergySystemBattery::where("battery_type_id", $battery->id)->first();

        if($exist){

        } else {
            
            $batterySystem = new EnergySystemBattery();
            $batterySystem->battery_type_id = $battery->id;
            $batterySystem->battery_units = $row["units"];
            $batterySystem->energy_system_id = $energySystem->id;
            $batterySystem->save();
        }
        
        // Heeere
        // $household = Household::where("english_name", $row["household"])->first();
        // $public = PublicStructure::where("english_name", $row['public'])->first();

        // $refrigeratorHolder = new RefrigeratorHolder();
        // $refrigeratorHolder->refrigerator_type_id = $row['refrigerator_type_id'];
        // $refrigeratorHolder->payment = $row['payment'];
        // $refrigeratorHolder->receive_number = $row['receive_number'];
        // $refrigeratorHolder->is_paid = $row['is_paid'];
        // $refrigeratorHolder->community_name = $row['community'];

        // if($household) {

        //     $refrigeratorHolder->household_id = $household->id;
        // } 
        // else if($public) {

        //     if($public) $refrigeratorHolder->public_structure_id = $public->id;
        // } 
        
        // if(date_timestamp_get($reg_date)) {
        //     $refrigeratorHolder->date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
        //     $year = explode('-', date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null);
        //     $refrigeratorHolder->year = $year[0];
        // }     
        
        // $refrigeratorHolder->save();

        // return $refrigeratorHolder;
    }
}
