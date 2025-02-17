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
use App\Models\User;
use App\Models\CommunityHousehold;
use App\Models\Cistern;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Helpers\SequenceHelper;
use Carbon\Carbon;
use Excel; 

class ImportHousehold implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Get the community from it's english name. 
        // $community = Community::where("english_name", $row["community"])->first();

        // Get data from KOBO 
        if($row["select_community"]) { 

            $community = Community::where("english_name", $row["select_community"])->first();
            $household = null;
            $newHousehold = null;
            $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['submission_time']);
            
            if($community && ($row["select_is_live"] == "Left" || $row["select_is_live"] == "Move")) {

                if($row["select_household_name"]) {

                    $household = Household::where('community_id', $community->id)
                        ->where("comet_id", $row["select_household_name"])
                        ->first();

                    $householdStatus = HouseholdStatus::where('status', "Left")->first();
                    if($householdStatus) {

                        $household->household_status_id = $householdStatus->id;
                        $household->notes = $row["notes"]; 
                        $household->is_surveyed = "yes"; 
        
                        if(date_timestamp_get($reg_date)) {
        
                            $household->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                        }
        
                        $cleanName = preg_replace('/\d/', '', $row["submitted_by"]);  
                        $user = User::where('name', 'like', '%' . $cleanName . '%')->first();
                        if($user) $household->referred_by_id = $user->id;
                        $household->save();
                    }
                }
            } 

            if($community && $row["select_is_live"] == "Live") {

                if($row["select_household_name"]) {

                    $household = Household::where('community_id', $community->id)
                        ->where("comet_id", $row["select_household_name"])
                        ->first();
                } else if($row["select_household_name"] == null) {

                    $last_comet_id = Household::latest('id')->value('comet_id');
                    $existHousehold = Household::where("is_archived", 0)
                        ->where("english_name", $row["english_name"])
                        ->first();
                    if(!$existHousehold) {

                        $newHousehold = new Household();
                        $newHousehold->comet_id = ++$last_comet_id;
                        $energyType = EnergySystemType::where('name', 'like', '%' . $row["select_system_type"] . '%')->first(); 
                        $newHousehold->energy_system_type_id = $energyType->id;
                        $newHousehold->household_status_id = 5;
                        $newHousehold->request_date = $reg_date ? $reg_date->format('Y-m-d') : null;
                    }
                }

                $household = $newHousehold; 
                $profession = Profession::where("profession_name", $row["select_profession"])->first();
                    
                if($household) {

                    $household->arabic_name = $row["arabic_name"];
                    $household->english_name = $row["english_name"];
                    $household->phone_number = $row["phone_number"];
                    if($profession) $household->profession_id = $profession->id;
                    $household->number_of_people = $row["number_of_male"] + $row["number_of_female"];
                    $household->number_of_male = $row["number_of_male"]; 
                    $household->number_of_female = $row["number_of_female"]; 
                    $household->number_of_children = $row["number_of_children"];
                    $household->number_of_adults = $row["number_of_adults"];  
                    $household->school_students = $row["school_students"];
                    $household->university_students = $row["university_students"]; 
                    $household->demolition_order = $row["demolition_order"]; 
                    $household->size_of_herd = $row["size_of_herd"];
                    $household->notes = $row["notes"]; 
                    $household->is_surveyed = "yes"; 
    
                    $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['submission_time']);
                    if(date_timestamp_get($reg_date)) {
    
                        $household->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                    }
    
                    $cleanName = preg_replace('/\d/', '', $row["submitted_by"]);  
                    $user = User::where('name', 'like', '%' . $cleanName . '%')->first();
                    if($user) $household->referred_by_id = $user->id;
                    $household->community_id = $community->id;
                    $household->save();
    
                    // Update shared household details
                    if($row["select_household_status"]) {
                        // Handle shared household statuses
                        if($row["select_household_status"] == "Shared" || $row["select_household_status"] == "Shared & Requested") {
                            
                            // check if he exist in household_meters table
                            $existHouseholdMeter = HouseholdMeter::where("is_archived", 0)
                                ->where("household_id", $household->id)
                                ->first();
        
                            if($existHouseholdMeter) {
                                if($row["select_main_user"]) {

                                    $energyMeter = AllEnergyMeter::findOrFail($row["select_main_user"]);
                                    $energyUser = Household::findOrFail($energyMeter->household_id);
                                    if($energyMeter) {

                                        $existHouseholdMeterEnergy = HouseholdMeter::where("is_archived", 0)
                                            ->where("household_id", $household->id)
                                            ->where("energy_user_id", $energyMeter->id)
                                            ->first();
                                        if($existHouseholdMeterEnergy) {

                                            $existHouseholdMeterEnergy->energy_user_id = $energyMeter->id;
                                            $existHouseholdMeterEnergy->user_name = $energyUser->english_name;
                                            $existHouseholdMeterEnergy->user_name_arabic = $energyUser->arabic_name;
                                            $existHouseholdMeterEnergy->household_name = $household->english_name;
                                            $existHouseholdMeterEnergy->save();
                                        } else {

                                            $existHouseholdMeter->energy_user_id = $energyMeter->id;
                                            $existHouseholdMeter->user_name = $energyUser->english_name;
                                            $existHouseholdMeter->user_name_arabic = $energyUser->arabic_name;
                                            $existHouseholdMeter->household_name = $household->english_name;
                                            $existHouseholdMeter->save();
                                        }

                                        $sharedAllEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                                            ->where("household_id", $household->id)
                                            ->first();
                                        if($sharedAllEnergyMeter) {

                                            $lastIncrementalNumber = AllEnergyMeter::whereNotNull('fake_meter_number')
                                                ->selectRaw('MAX(CAST(SUBSTRING_INDEX(fake_meter_number, \'s\', -1) AS UNSIGNED)) AS incremental_number')
                                                ->value('incremental_number');

                                            $lastIncrementalNumber = $lastIncrementalNumber + 1; 
                                            $newFakeMeterNumber = SequenceHelper::generateSequence($energyMeter->meter_number, $lastIncrementalNumber);
                                            $sharedAllEnergyMeter->fake_meter_number = $newFakeMeterNumber;
                                            $sharedAllEnergyMeter->save();
                                        }
                                    } 
                                }
                            } else {

                                if($row["select_main_user"]) {

                                    $energyMeter = AllEnergyMeter::findOrFail($row["select_main_user"]);
                                    $energyUser = Household::findOrFail($energyMeter->household_id);
                                    if($energyMeter) {

                                        $newHouseholdMeterEnergy = new HouseholdMeter();
                                        $newHouseholdMeterEnergy->household_id = $household->id;
                                        $newHouseholdMeterEnergy->energy_user_id = $energyMeter->id;
                                        $newHouseholdMeterEnergy->user_name = $energyUser->english_name;
                                        $newHouseholdMeterEnergy->user_name_arabic = $energyUser->arabic_name;
                                        $newHouseholdMeterEnergy->household_name = $household->english_name;
                                        $newHouseholdMeterEnergy->save();

                                        $newSharedAllEnergyMeter = new AllEnergyMeter();
                                        $newSharedAllEnergyMeter->household_id = $household->id;
                                        $newSharedAllEnergyMeter->community_id = $household->community_id;
                                        $newSharedAllEnergyMeter->is_main = "No";
                                        $newSharedAllEnergyMeter->installation_type_id = $energyMeter->installation_type_id;
                                        $newSharedAllEnergyMeter->energy_system_type_id = $energyMeter->energy_system_type_id;
                                        $newSharedAllEnergyMeter->energy_system_id = $energyMeter->energy_system_id;
                                        $lastIncrementalNumber = AllEnergyMeter::whereNotNull('fake_meter_number')
                                            ->selectRaw('MAX(CAST(SUBSTRING_INDEX(fake_meter_number, \'s\', -1) AS UNSIGNED)) AS incremental_number')
                                            ->value('incremental_number');
                                    
                                        $lastIncrementalNumber = $lastIncrementalNumber + 1; 
                                        $newFakeMeterNumber = SequenceHelper::generateSequence($energyMeter->meter_number, $lastIncrementalNumber);
                                        $newSharedAllEnergyMeter->fake_meter_number = $newFakeMeterNumber;
                                        $newSharedAllEnergyMeter->save();
                                    }
                                }
                            }
                        }
                    }

                    if($row["select_system_type"]) {
                        
                        $energyType = EnergySystemType::where('name', 'like', '%' . $row["select_system_type"] . '%')->first(); 
                        $requestedHousehold = Household::findOrFail($household->id);
                        $requestedHousehold->energy_system_type_id = $energyType->id;
                        $requestedHousehold->household_status_id = 5;
                        $requestedHousehold->request_date = $reg_date ? $reg_date->format('Y-m-d') : null;
                        $requestedHousehold->save();
                    }
                    if($row["select_household_status"] == "Shared") {

                        $householdUpdated = Household::findOrFail($household->id);
                        $householdUpdated->household_status_id = 4;
                        $householdUpdated->save();
                    } 

                    // Update the meter case & description
                    $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                        ->where('household_id', $household->id)
                        ->whereNotNull('meter_number')
                        ->first();
                    if($allEnergyMeter) {
    
                        if($row["select_meter_case"] == "High usage" || $row["select_meter_case"] == "Regular usage") $allEnergyMeter->meter_case_id = 1;
                        else if($row["select_meter_case"] == "Low usage") $allEnergyMeter->meter_case_id = 3;
                        else if($row["select_meter_case"] == "Not used") $allEnergyMeter->meter_case_id = 2;
                        else if($row["select_meter_case"] == "Bypass meter") $allEnergyMeter->meter_case_id = 10;
                        else if($row["select_meter_case"] == "Left Comet") $allEnergyMeter->meter_case_id = 11;
                        else if($row["select_meter_case"] == "Not activated") $allEnergyMeter->meter_case_id = 12;
    
                        if($row["select_meter_case"]) {
    
                            $meterDescription = MeterCaseDescription::where("english_name", $row["select_meter_case_description"])
                                ->first();
    
                            if($meterDescription) $allEnergyMeter->meter_case_description_id = $meterDescription->id;
                        } 
    
                        $allEnergyMeter->save();
                    }
    
                    // Cistern
                    $cistern = Cistern::where('household_id', $household->id)->first();
                    if($cistern) {
    
                        if($row["number_of_cisterns"]) $cistern->number_of_cisterns = $row["number_of_cisterns"];
                        if($row["cistern_depth"]) $cistern->volume_of_cisterns = $row["cistern_depth"];
                        if($row["select_shared_cisterns"]) $cistern->shared_cisterns = $row["select_shared_cisterns"];
                        if($row["distance_from_house"]) $cistern->distance_from_house = $row["distance_from_house"];
                        $cistern->save();
                    } else {

                        $newCistern = new Cistern();
                        $newCistern->household_id = $household->id;
                        if($row["number_of_cisterns"]) $newCistern->number_of_cisterns = $row["number_of_cisterns"];
                        if($row["cistern_depth"]) $newCistern->volume_of_cisterns = $row["cistern_depth"];
                        if($row["select_shared_cisterns"]) $newCistern->shared_cisterns = $row["select_shared_cisterns"];
                        if($row["distance_from_house"]) $newCistern->distance_from_house = $row["distance_from_house"];
                        $newCistern->save();
                    }
    
                    // Animal shelters
                    $structure = Structure::where('household_id', $household->id)->first();
                    if($structure) {
    
                        if($row["number_of_animal_shelters"])$structure->number_of_animal_shelters = $row["number_of_animal_shelters"];
                        $structure->save();
                    }
    
                    // Community Household table 
                    $communityHousehold = CommunityHousehold::where('household_id', $household->id)->first();
                    if($communityHousehold) {
    
                        if($row["select_is_there_house_in_town"]) $communityHousehold->is_there_house_in_town = $row["select_is_there_house_in_town"];
                        if($row["select_is_there_izbih"])$communityHousehold->is_there_izbih = $row["select_is_there_izbih"];
                        if($row["how_long"])$communityHousehold->how_long = $row["how_long"];
                        $communityHousehold->save();
                    } else {

                        $newCommunityHousehold = new CommunityHousehold();
                        $newCommunityHousehold->household_id = $household->id;
                        if($row["select_is_there_house_in_town"]) $newCommunityHousehold->is_there_house_in_town = $row["select_is_there_house_in_town"];
                        if($row["select_is_there_izbih"])$newCommunityHousehold->is_there_izbih = $row["select_is_there_izbih"];
                        if($row["how_long"])$newCommunityHousehold->how_long = $row["how_long"];
                        $newCommunityHousehold->save();
                    }
                }
            } 
        }
    }
}
