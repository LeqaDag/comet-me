<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Community;
use App\Models\PublicStructure;
use App\Models\AllWaterHolder;
use App\Models\Household;
use App\Models\H2oUser;
use App\Models\H2oSharedUser;
use Carbon\Carbon;
use Excel;

class ImportWaterQualityResult implements ToModel, WithHeadingRow
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

        $community = Community::where("english_name", $row['community'])->first();
        $household = Household::where("english_name", $row["household"])->first();
        $public = PublicStructure::where("english_name", $row['public'])->first();

        $waterResult = new WaterQualityResult();
        $waterResult->community_id = $community->id;

        if($household) {

            $waterResult->household_id = $household->id;

            $h2o_user_id = H2oUser::where('household_id', $household->id)->first();
            if($h2o_user_id != null) {

                $waterResult->h2o_user_id = $h2o_user_id->id;
            } 

            $h2oSharedUser = H2oSharedUser::where('household_id', $household->id)->first();
            if($h2oSharedUser) {

                $waterResult->h2o_shared_user_id = $h2oSharedUser->id;
            }
               
        } else if($public) {

            if($public) $waterResult->public_structure_id = $public->id;
        }
        
        if(date_timestamp_get($reg_date)) {

            $waterResult->date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
            $year = explode('-', date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null);
            $waterResult->year = $year[0];
        }
             
        $waterResult->cfu = $row['cfu'];
        $waterResult->ph = $row['ph'];
        $waterResult->fci = $row['fci'];
        $waterResult->ec = $row['ec'];
        $waterResult->notes = $row['notes'];
        $waterResult->save();

        return $waterResult;
    }
}
