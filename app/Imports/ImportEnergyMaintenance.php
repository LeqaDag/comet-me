<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Community;
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\EnergySystem;
use App\Models\ElectricityMaintenanceCall;
use App\Models\ElectricityMaintenanceCallUser;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceElectricityAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
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
       // dd(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']));
        $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']);

        //dd(date_timestamp_get($reg_date));

        //$community = Community::where("english_name", $row['community'])->pluck("id");
       
        $household = Household::where("english_name", $row["household"])->first();
        $public = PublicStructure::where("english_name", $row['public'])->first();

        $refrigeratorHolder = new RefrigeratorHolder();
        $refrigeratorHolder->refrigerator_type_id = $row['refrigerator_type_id'];
        $refrigeratorHolder->payment = $row['payment'];
        $refrigeratorHolder->receive_number = $row['receive_number'];
        $refrigeratorHolder->is_paid = $row['is_paid'];
        $refrigeratorHolder->community_name = $row['community'];

        if($household) {

            $refrigeratorHolder->household_id = $household->id;
        } 
        else if($public) {

            if($public) $refrigeratorHolder->public_structure_id = $public->id;
        } 
        
        if(date_timestamp_get($reg_date)) {
            $refrigeratorHolder->date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
            $year = explode('-', date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null);
            $refrigeratorHolder->year = $year[0];
        }     
        
        $refrigeratorHolder->save();

        return $refrigeratorHolder;
    }
}
