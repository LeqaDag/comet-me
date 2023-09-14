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
use App\Models\RefrigeratorHolder;
use App\Models\RefrigeratorHolderReceiveNumber;
use App\Models\H2oSharedUser;
use Carbon\Carbon; 
use Excel;

class ImportRefrigerator implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // add receive numbers
        $household = Household::where("english_name", $row["household"])->first();
        $public = PublicStructure::where("english_name", $row['public'])->first();

        $refrigeratorHolder = new RefrigeratorHolderReceiveNumber();
        $refrigeratorHolder->receive_number = $row['receive_number'];
        $refrigeratorHolder->community_name = $row['community'];
        $refrigeratorHolder->year = $row['year'];
        $refrigeratorHolder->maintenance_year = $row['maintenance_year'];
        $refrigeratorHolder->household_name = $row['household'];

        if($household) {

            $refrigeratorHolder->household_name = $row['household'];
        } 
        if($public) {

            if($public) $refrigeratorHolder->public_name = $row['public'];
        }    
        
        $refrigeratorHolder->save();

        return $refrigeratorHolder;
        

      
       // dd(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']));

        

        //$community = Community::where("english_name", $row['community'])->pluck("id");
       
       // add new holder

    //    $refrigeratorHolder = new RefrigeratorHolder();

    //     if($row['household']) {

    //         $household = Household::where("english_name", $row["household"])->first();
    //         $refrigeratorHolder->household_name = $row['household'];  
    //         if($household) $refrigeratorHolder->household_id = $household->id;
    //     } 
    //     if($row['public']) {

    //         $public = PublicStructure::where("english_name", $row['public'])->first();
    //         $refrigeratorHolder->public_name = $row['public']; 
    //         if($public) $refrigeratorHolder->public_structure_id = $public->id;
    //     } 

    //     if($row['date']) {

    //         $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']);

    //         if(date_timestamp_get($reg_date)) {
    //             $refrigeratorHolder->date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
    //             $year = explode('-', date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null);
    //         }  
    //     } 

    //     $refrigeratorHolder->refrigerator_type_id = $row['refrigerator_type_id'];
    //     $refrigeratorHolder->payment = $row['payment'];
    //     $refrigeratorHolder->year = $row['year'];
    //     $refrigeratorHolder->maintenance_year = $row['maintenance_year'];
    //     $refrigeratorHolder->number_of_fridge = $row['number_of_fridge'];
    //     $refrigeratorHolder->is_paid = $row['is_paid'];
    //     $refrigeratorHolder->community_name = $row['community'];
        
    //     $refrigeratorHolder->save();

    //     return $refrigeratorHolder;
        
    }
}
