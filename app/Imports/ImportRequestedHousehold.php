<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\AllEnergyMeter;
use App\Models\Community;
use App\Models\Household;
use App\Models\Profession;
use App\Models\PublicStructure;
use App\Models\AllEnergyMeterDonor;
use App\Models\AllWaterHolder;
use App\Models\CommunityRepresentative;
use App\Models\InternetUser;
use App\Models\EnergySystemType;
use App\Models\MeterCaseDescription;
use App\Models\Compound;
use App\Models\CompoundHousehold;
use App\Models\User;
use App\Models\CommunityHousehold;
use App\Models\PostponedHousehold;
use App\Models\DeletedRequestedHousehold;
use App\Models\Cistern;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\EnergySystem;
use App\Models\EnergySystemCycle;
use App\Models\Region;
use App\Models\Structure;
use Carbon\Carbon;
use Excel; 
use Auth;

class ImportRequestedHousehold implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */ 
    public function model(array $row)
    {
        // Get data from KOBO 
        if($row["select_community"]) { 

            $community = Community::where("english_name", $row["select_community"])->first();
            $household = null;
            $cleanName = preg_replace('/\d/', '', $row["submitted_by"]);  
            $user = User::where('name', 'like', '%' . $cleanName . '%')->first();

            if($community) {

                if($row["select_household_name"]) {

                    $household = Household::where('community_id', $community->id)
                        ->where("comet_id", $row["select_household_name"])
                        ->first();
                } else {

                    $last_comet_id = Household::latest('id')->value('comet_id');
                    $household = new Household();
                    $household->comet_id = ++$last_comet_id;
                    $household->community_id = $community->id;
                    if($row["english_name"]) $household->english_name = $row["english_name"];
                    if($row["arabic_name"]) $household->arabic_name = $row["arabic_name"];
                    $household->profession_id = 1;
                }
                    
                if($user) $household->referred_by_id = $user->id;
                $household->save();

                if($row["select_action_type"] == "Confirmed" && $household) {

                    $status = "Confirmed";
                    $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
                    $lastCycleYear = EnergySystemCycle::latest()->first();

                    $energyType = preg_replace('/\d/', '', $row["energy_system_type"]);  
                    $energySystemType = EnergySystemType::where('name', 'like', '%' . $energyType . '%')->first();

                    if($energySystemType) {
                        
                        if($energySystemType->id == 2) {

                            $household->household_status_id = $statusHousehold->id;
                            $household->energy_system_cycle_id = $lastCycleYear->id; 
                            $household->save();
                        } else {

                            $status = "AC Completed";
                            $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
                            $household->household_status_id = $statusHousehold->id;
                            $household->energy_system_cycle_id = $lastCycleYear->id; 
                            $household->save();

                            $energySystem = EnergySystem::where("is_archived", 0)
                                ->where("community_id", $household->community_id)
                                ->first();

                            $allEnergyMeter = new AllEnergyMeter();
                            $allEnergyMeter->household_id = $household->id;
                            $allEnergyMeter->installation_type_id = 3;
                            $allEnergyMeter->community_id = $household->community_id;
                            $allEnergyMeter->energy_system_cycle_id = $lastCycleYear->id;
                            $allEnergyMeter->energy_system_type_id = $energySystem->energy_system_type_id;
                            $allEnergyMeter->ground_connected = "Yes";
                            $allEnergyMeter->energy_system_id = $energySystem->id;
                            $allEnergyMeter->meter_number = 0;
                            $allEnergyMeter->meter_case_id = 12; 
                            $allEnergyMeter->save();
                        }
                    }
                } else if($row["select_action_type"] == "Delete" && $household) {

                    $householdMeter = AllEnergyMeter::where("is_archived", 0)
                        ->where("household_id", $household->id)
                        ->first();
            
                    if($householdMeter) {
                        
                        $household->household_status_id = 4;
                        $household->save();
                    } else {
            
                        $household->is_archived = 1;
                        $household->save();
                    }
            
                    $existDeleted = DeletedRequestedHousehold::where("is_archived", 0)
                        ->where("household_id", $household->id)
                        ->first();
            
                    if(!$existDeleted) {
            
                        $deletedRequestedHousehold = new DeletedRequestedHousehold();
                        $deletedRequestedHousehold->household_id = $household->id;
                        $deletedRequestedHousehold->reason = $row["delete_reason"];
                        $deletedRequestedHousehold->referred_by = $user->id;
                        $deletedRequestedHousehold->save();
                    }
                } else if($row["select_action_type"] == "Postponed" && $household) {

                    $status = "Postponed";
                    $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();

                    if($statusHousehold) {

                        $household->household_status_id = $statusHousehold->id;
                        $household->save();

                        $user = Auth::guard('user')->user();

                        $postponedHousehold = new PostponedHousehold();
                        $postponedHousehold->household_id = $household->id;
                        $postponedHousehold->reason = $row["postponed_reason"];
                        $postponedHousehold->referred_by = $user->id;
                        $postponedHousehold->save();
                    }
                }
            }
        }
    }
}