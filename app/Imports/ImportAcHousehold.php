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
use App\Models\Cistern;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use Carbon\Carbon;
use Excel; 

class ImportAcHousehold implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Get data from KOBO 
        if($row["select_ac_community"]) { 

            $community = Community::where("english_name", $row["select_ac_community"])->first();
            $household = null;

            if($community) {

                if($row["select_household_name"]) {

                    $household = Household::where('is_archived', 0)
                        ->where('community_id', $community->id)
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
                    $household->additional_phone_number = $row["additional_phone_number"];
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
                    $household->household_status_id = 2;
    
                    if($row["energy_system_type"]) {

                        $energyType = EnergySystemType::where('name', 'like', '%' . $row["energy_system_type"] . '%')->first(); 
                        $household->energy_system_type_id = $energyType->id;
                    }

                    $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['submission_time']);
                    if(date_timestamp_get($reg_date)) {
    
                        $household->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                    }
    
                    $cleanName = preg_replace('/\d/', '', $row["submitted_by"]);  
                    $user = User::where('name', 'like', '%' . $cleanName . '%')->first();
                    if($user) $household->referred_by_id = $user->id;
                    $household->community_id = $community->id;
                    $household->save();
    
                    if($row["select_compound"]) {

                        $compound = Compound::where("english_name", $row["select_compound"])->first();

                        if($compound) {

                            $compoundHousehold = CompoundHousehold::where("is_archived", 0)
                                ->where("household_id", $household->id)
                                ->where("compound_id", $compound->id)
                                ->first();

                            if(!$compoundHousehold) {

                                $newCompoundHousehold = new CompoundHousehold();
                                $newCompoundHousehold->compound_id = $compound->id;
                                $newCompoundHousehold->household_id = $household->id;
                                $newCompoundHousehold->community_id = $household->community_id;
                                $newCompoundHousehold->energy_system_type_id = $household->energy_system_type_id;
                                $newCompoundHousehold->save();

                            }
                        }
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
    }
}
