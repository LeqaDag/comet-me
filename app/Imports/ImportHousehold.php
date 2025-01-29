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

            if($community && $row["select_is_live"] != "left") {

                if($row["select_household_name"]) {

                    $household = Household::where('community_id', $community->id)
                        ->where("comet_id", $row["select_household_name"])
                        ->first();
                } else {

                    $last_comet_id = Household::latest('id')->value('comet_id');
                    $household = new Household();
                    $household->comet_id = ++$last_comet_id;
                }

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
    
                    $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                        ->where('household_id', $household->id)
                        ->whereNotNull('meter_number')
                        ->first();
    
                    if($allEnergyMeter) {
    
                        if($row["select_meter_case"] == "High_usage" || $row["select_meter_case"] == "Regular_usage") $allEnergyMeter->meter_case_id = 1;
                        else if($row["select_meter_case"] == "Low_usage") $allEnergyMeter->meter_case_id = 3;
                        else if($row["select_meter_case"] == "Not_used") $allEnergyMeter->meter_case_id = 2;
                        else if($row["select_meter_case"] == "Bypass_meter") $allEnergyMeter->meter_case_id = 10;
                        else if($row["select_meter_case"] == "Left_Comet") $allEnergyMeter->meter_case_id = 11;
                        else if($row["select_meter_case"] == "Not_activated") $allEnergyMeter->meter_case_id = 12;
    
                        if($row["select_meter_case"]) {
    
                            $meterDescription = MeterCaseDescription::where("english_name", $row["select_meter_case_description"])
                                ->first();
    
                            if($meterDescription) $allEnergyMeter->meter_case_description_id = $meterDescription->id;
                        } 
    
                        $allEnergyMeter->save();
                    }
    
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
    
                    $structure = Structure::where('household_id', $household->id)->first();
                    if($structure) {
    
                        if($row["number_of_animal_shelters"])$structure->number_of_animal_shelters = $row["number_of_animal_shelters"];
                        $structure->save();
                    }
    
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

        // Importing from Excel sheet
        // if($row["community"]) {

        //     $community = Community::where("english_name", $row["community"])->first();

        //     if($community) {
    
        //         // Get the household from it's english name. 
        //         $household = Household::where("english_name", $row["old_name"])
        //             ->where("community_id", $community->id)
        //             ->first();
    
        //         $profession = null;
    
        //         if($row["is_surveyed"]) {
    
        //             if($row["profession"]) $profession = Profession::where("profession_name", $row["profession"])->first();
        //             if($row["type"]) $energyType = EnergySystemType::where("name", $row["type"])->first();
    
        //             if($household) {
    
        //                 $household->arabic_name = $row["arabic_name"];
        //                 $household->english_name = $row["english_name"];
        //                 $household->phone_number = $row["phone"];
        //                 if($profession) $household->profession_id = $profession->id;
        //                 $household->number_of_people = $row["number_of_people"]; 
        //                 $household->number_of_male = $row["number_of_male"]; 
        //                 $household->number_of_female = $row["number_of_female"]; 
        //                 $household->number_of_children = $row["number_of_children"];
        //                 $household->number_of_adults = $row["number_of_adults"];  
        //                 $household->school_students = $row["school_students"];
        //                 $household->university_students = $row["university_students"]; 
        //                 $household->demolition_order = $row["demolition_order"]; 
        //                 $household->size_of_herd = $row["size_of_herd"];
        //                 $household->demolition_order = $row["demolition_order"];
        //                 if($energyType) $household->energy_system_type_id = $energyType->id;
        //                 //$household->notes = $row["notes"]; 
        //                 $household->is_surveyed = $row["is_surveyed"]; 
    
        //                 $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['last_surveyed_date']);
        //                 if(date_timestamp_get($reg_date)) {
    
        //                     $household->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
        //                 }
                        
        //                 $household->save();
    
        //                 $cistern = Cistern::where('household_id', $household->id)->first();
    
        //                 if($cistern) {
    
        //                     if($row["number_of_cisterns"]) $cistern->number_of_cisterns = $row["number_of_cisterns"];
        //                     if($row["volume_of_cisterns"]) $cistern->volume_of_cisterns = $row["volume_of_cisterns"];
        //                     if($row["shared_cisterns"]) $cistern->shared_cisterns = $row["shared_cisterns"];
        //                     if($row["distance_from_house"]) $cistern->distance_from_house = $row["distance_from_house"];
        //                     if($row["depth_of_cisterns"]) $cistern->depth_of_cisterns = $row["depth_of_cisterns"];
        //                     $cistern->save();
        //                 }
    
        //                 $structure = Structure::where('household_id', $household->id)->first();
        //                 if($structure) {
    
        //                     if($row["number_of_structures"]) $structure->number_of_structures = $row["number_of_structures"];
        //                     if($row["number_of_kitchens"])$structure->number_of_kitchens = $row["number_of_kitchens"];
        //                     if($row["number_of_animal_shelters"])$structure->number_of_animal_shelters = $row["number_of_animal_shelters"];
        //                     //if($row["number_of_cave"])$structure->number_of_cave = $row["number_of_cave"];
        //                     $structure->save();
        //                 }
    
        //                 $communityHousehold = CommunityHousehold::where('household_id', $household->id)->first();
        //                 if($communityHousehold) {
    
        //                     if($row["is_there_house_in_town"]) $communityHousehold->is_there_house_in_town = $row["is_there_house_in_town"];
        //                     if($row["is_there_izbih"])$communityHousehold->is_there_izbih = $row["is_there_izbih"];
        //                     if($row["how_long"])$communityHousehold->how_long = $row["how_long"];
        //                     if($row["length_of_stay"])$communityHousehold->length_of_stay = $row["length_of_stay"];
        //                     $communityHousehold->save();
        //                 } 
        //             }
        //         }
        //     }
        // }
    }
}
