<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\AllEnergyMeter;
use App\Models\Community;
use App\Models\Household;
use App\Models\CommunityStatus;
use App\Models\DisplacedHousehold;
use App\Models\DisplacedCommunity;
use App\Models\DisplacedHouseholdStatus;
use App\Models\SubRegion; 
use App\Models\PublicStructure;
use App\Models\AllEnergyMeterDonor;
use App\Models\AllWaterHolder;
use App\Models\MeterCase;
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

class ImportDisplacedHousehold implements ToModel, WithHeadingRow
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

            if($community) {
        
                if($row["select_household_name"]) {

                    $array = explode(" ", $row["select_household_name"]);

                    foreach ($array as $name) {

                        $household = Household::where('comet_id', $name)->first();
                        $householdStatus = HouseholdStatus::where('status', "Displaced")->first();

                        if($household) {

                            if($householdStatus) {
                        
                                $household->household_status_id = $householdStatus->id;
                                $household->save();
                            }

                            $existDisplacedHousehold = DisplacedHousehold::where("is_archived", 0)
                                ->where("old_community_id", $community->id)
                                ->where("household_id", $household->id)
                                ->first();

                            if(!$existDisplacedHousehold) {

                                $newDisplacedHousehold = new DisplacedHousehold();
                                $newDisplacedHousehold->household_id = $household->id;
                                $newDisplacedHousehold->old_community_id = $community->id; 
                                $newDisplacedHousehold->area = str_replace('Area ', '', $row["select_area"]);
                                $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['displacement_date']);
                                if(date_timestamp_get($reg_date)) {
    
                                    $newDisplacedHousehold->displacement_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                                }
                                $newDisplacedHousehold->system_retrieved = $row["displacement_date"];
                                $newDisplacedHousehold->notes = $row["displacement_notes"];
                                $subRegion = SubRegion::where('english_name', $row["select_new_region"])->first();
                                if($subRegion) $newDisplacedHousehold->sub_region_id = $subRegion->id;

                                if($row["select_system_retrieved"] == "System Retrieved") $newDisplacedHousehold->system_retrieved = "Yes";
                                else $newDisplacedHousehold->system_retrieved = "No";
                                                            
                                $displacedStatus = DisplacedHouseholdStatus::where('name', $row["select_household_status"])->first();
                                $newDisplacedHousehold->displaced_household_status_id = $displacedStatus->id; 

                                $energyUser = AllEnergyMeter::where("is_archived", 0)
                                    ->where("household_id", $household->id)
                                    ->first();
                                if($energyUser) {

                                    $newDisplacedHousehold->old_meter_number = $energyUser->meter_number; 
                                    $newDisplacedHousehold->old_energy_system_id = $energyUser->energy_system_id;

                                    $meterStatus = MeterCase::where('meter_case_name_english', "Displaced")->first();

                                    if($meterStatus) {

                                        $energyUser->meter_case_id = $meterStatus->id;
                                        $energyUser->save();
                                    }
                                }

                                $newDisplacedHousehold->save();
                            }
                        }
                    }
                } 
            }
        }
    }
}
