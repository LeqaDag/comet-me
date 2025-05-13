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

class ImportCommunity implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $demolition_date = null;

        if($row["demolition_date"]) {

            $demolition_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['$demolition_date']);
        }

        $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['submission_time']);

        if($row["select_compound"]) {

            $compound = Compound::where("english_name", $row["select_compound"])->first();

            if($compound) {

                $compound->is_bedouin = $row["select_bedouin"]; 
                $compound->is_fallah = $row["select_fallah"];
                $compound->reception = $row["select_reception"];  
                $compound->demolition = $row["select_demolition"];
                $compound->demolition_number = $row["demolition_number"]; 
                $compound->demolition_executed = $row["select_demolition_executed"];
                if($row["demolition_date"]) $compound->last_demolition = date_timestamp_get($demolition_date) ? $demolition_date->format('Y-m-d') : null;
                $compound->demolition_legal_status = $row["demolition_legal"];
                $compound->land_status = $row["land_status"];
                $compound->lawyer = $row["lawyer"];
                $compound->notes = $row["community_notes"];
                $compound->is_surveyed = "yes"; 

                if(date_timestamp_get($reg_date)) {

                    $compound->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                }
                
                $compound->save();

            }
        } else if($row["select_community"]) { 

            $community = Community::where("comet_id", $row["select_community"])->first();

            if($community) {

                $community->is_bedouin = $row["select_bedouin"]; 
                $community->is_fallah = $row["select_fallah"];
                $community->reception = $row["select_reception"];  
                $community->demolition = $row["select_demolition"];
                $community->demolition_number = $row["demolition_number"]; 
                $community->demolition_executed = $row["select_demolition_executed"];
                if($row["demolition_date"]) $community->last_demolition = date_timestamp_get($demolition_date) ? $demolition_date->format('Y-m-d') : null;
                $community->demolition_legal_status = $row["demolition_legal"];
                $community->land_status = $row["land_status"];
                $community->lawyer = $row["lawyer"];
                $community->notes = $row["community_notes"];
                $community->is_surveyed = "yes"; 

                if(date_timestamp_get($reg_date)) {

                    $community->last_surveyed_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                }
                
                $community->save();
            }
        }
    }
}
