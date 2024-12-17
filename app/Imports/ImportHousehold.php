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

        $community = Community::where("english_name", $row["community"])->first();

        if($community) {

            // Get the household from it's english name. 
            $household = Household::where("english_name", $row["old_name"])
                ->where("community_id", $community->id)
                ->first();

            $profession = null;

            if($row["is_surveyed"]) {

                if($row["profession"]) $profession = Profession::where("profession_name", $row["profession"])->first();
                if($row["type"]) $energyType = EnergySystemType::where("name", $row["type"])->first();

                if($household) {

                    $household->arabic_name = $row["arabic_name"];
                    $household->english_name = $row["english_name"];
                    $household->phone_number = $row["phone"];
                    if($profession) $household->profession_id = $profession->id;
                    $household->number_of_people = $row["number_of_people"]; 
                    $household->number_of_male = $row["number_of_male"]; 
                    $household->number_of_female = $row["number_of_female"]; 
                    $household->number_of_children = $row["number_of_children"];
                    $household->number_of_adults = $row["number_of_adults"];  
                    $household->school_students = $row["school_students"];
                    $household->university_students = $row["university_students"]; 
                    $household->demolition_order = $row["demolition_order"]; 
                    $household->size_of_herd = $row["size_of_herd"];
                    $household->demolition_order = $row["demolition_order"];
                    if($energyType) $household->energy_system_type_id = $energyType->id;
                    //$household->notes = $row["notes"]; 
                    $household->is_surveyed = $row["is_surveyed"]; 

                    $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['last_surveyed_date']);
                    if(date_timestamp_get($reg_date)) {

                        $household->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                    }
                    
                    $household->save();

                    $cistern = Cistern::where('household_id', $household->id)->first();

                    if($cistern) {

                        if($row["number_of_cisterns"]) $cistern->number_of_cisterns = $row["number_of_cisterns"];
                        if($row["volume_of_cisterns"]) $cistern->volume_of_cisterns = $row["volume_of_cisterns"];
                        if($row["shared_cisterns"]) $cistern->shared_cisterns = $row["shared_cisterns"];
                        if($row["distance_from_house"]) $cistern->distance_from_house = $row["distance_from_house"];
                        if($row["depth_of_cisterns"]) $cistern->depth_of_cisterns = $row["depth_of_cisterns"];
                        $cistern->save();
                    }

                    $structure = Structure::where('household_id', $household->id)->first();
                    if($structure) {

                        if($row["number_of_structures"]) $structure->number_of_structures = $row["number_of_structures"];
                        if($row["number_of_kitchens"])$structure->number_of_kitchens = $row["number_of_kitchens"];
                        if($row["number_of_animal_shelters"])$structure->number_of_animal_shelters = $row["number_of_animal_shelters"];
                        //if($row["number_of_cave"])$structure->number_of_cave = $row["number_of_cave"];
                        $structure->save();
                    }

                    $communityHousehold = CommunityHousehold::where('household_id', $household->id)->first();
                    if($communityHousehold) {

                        if($row["is_there_house_in_town"]) $communityHousehold->is_there_house_in_town = $row["is_there_house_in_town"];
                        if($row["is_there_izbih"])$communityHousehold->is_there_izbih = $row["is_there_izbih"];
                        if($row["how_long"])$communityHousehold->how_long = $row["how_long"];
                        if($row["length_of_stay"])$communityHousehold->length_of_stay = $row["length_of_stay"];
                        $communityHousehold->save();
                    } 
                }
            }
        }
    }
}
