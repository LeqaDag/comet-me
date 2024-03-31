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
use App\Models\DisplacedHousehold;
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
        // $community = Community::where("english_name", $row["name"])->first();
        // $community->latitude = $row["lat"];
        // $community->longitude = $row["long"];
        // $community->save();

        // Get all meter numbers from vending file
        // $meterVending = new AllEnergyVendingMeter();
        // $meterVending->meter_number = $row["meter_number"]; 
        // $meterVending->installation_date = $row["date"];
        // $meterVending->last_purchase_date = $row["last_purchase_date"];
        // $meterVending->meter_case_id = $row["meter_case_id"];
        // $meterVending->notes = $row["notes"];
        // $meterVending->save();
        // end vending 
       
        // payment report 
        // $energyUser = new EnergyUser();
        // $energyUser->meter_number =  $row["meter_number"];
        // $energyUser->receipt_number =  $row["receipt_number"];
        // $energyUser->kwh =  $row["kwh"];
        // $energyUser->payment_date =  $row["payment_date"];
        // $energyUser->amount =  $row["amount"];

        // $allEnergyMeter = AllEnergyMeter::where("meter_number", $row["meter_number"])->first();

        // if($allEnergyMeter) {

        //     $energyUser->all_energy_meter_id = $allEnergyMeter->id;
        //     $energyUser->community_id = $allEnergyMeter->community_id;
        //     $energyUser->save();
        // }



        // $household = Household::where("english_name", $row["name"])
        //     ->where("is_archived", 0)
        //     ->first();
        // $oldCommunity = Community::where("english_name", $row["old"])->first();
        // $newCommunity = Community::where("english_name", $row["new"])->first();
        // $oldEnergy = EnergySystem::where("name", $row["old_energy"])->first();
        // $newEnergy = EnergySystem::where("name", $row["new_energy"])->first();
        // // //$exist = DisplacedHousehold::where("household_id", $household->id)->first();

    
            
        // $displacedHousehold = new DisplacedHousehold();
        // $displacedHousehold->household_name = $row["name"];
        // $displacedHousehold->old_community_id = $oldCommunity->id;
        // $displacedHousehold->new_community_id = $newCommunity->id;
        // $displacedHousehold->old_energy_system_id = $oldEnergy->id;
        // $displacedHousehold->new_energy_system_id = $newEnergy->id;
        // $displacedHousehold->system_retrieved = "Yes";
        // $displacedHousehold->old_meter_number = $row["old_meter"];
        // $displacedHousehold->area = $row["area"];
        // $displacedHousehold->sub_region_id = $row["region"];
        // $displacedHousehold->save();
        
        
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
