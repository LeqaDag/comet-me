<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Community;
use App\Models\AllEnergyMeterSafetyCheck;
use App\Models\AllEnergyMeter;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\MeterCase;
use Carbon\Carbon; 
use Excel;

class ImportSafetyChecks implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row) {

            $household = Household::where("english_name", $row["holder"])->first();
            $public = PublicStructure::where("english_name", $row['holder'])->first();
            $allEnergyMeter = []; 
            $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['visit_date']);

            if($household) $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                ->where('household_id', $household->id)->first();
            else if($public) $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                ->where('public_structure_id', $public->id)->first();
    
            $meterCase = MeterCase::where('meter_case_name_english', $row["meter_case"])->first();
    
            if($allEnergyMeter) {
    
                $allEnergyMeter->ground_connected = "Yes";
                if($meterCase) $allEnergyMeter->meter_case_id = $meterCase->id;
                $allEnergyMeter->save();
    
                $existEnergySafety = AllEnergyMeterSafetyCheck::where('is_archived', 0)
                    ->where('all_energy_meter_id', $allEnergyMeter->id)->first();
    
                if($existEnergySafety) {
    
                    if(date_timestamp_get($reg_date)) $existEnergySafety->visit_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                    $existEnergySafety->rcd_x_phase0 = $row['rcd_x_phase0'];
                    $existEnergySafety->rcd_x_phase1 = $row['rcd_x_phase1'];
                    $existEnergySafety->rcd_x1_phase0 = $row['x1_phase0'];
                    $existEnergySafety->rcd_x1_phase1 = $row['x1_phase180'];
                    $existEnergySafety->rcd_x5_phase0 = $row['x5_phase0'];
                    $existEnergySafety->rcd_x5_phase1 = $row['x5_phase180'];
                    $existEnergySafety->ph_loop = $row['ph_loop'];
                    $existEnergySafety->n_loop = $row['n_loop'];
                    $existEnergySafety->notes = $row['notes'];
                    $existEnergySafety->save();
                } else {
    
                    $energySafety = new AllEnergyMeterSafetyCheck();
                    $energySafety->all_energy_meter_id = $allEnergyMeter->id;                
                    if(date_timestamp_get($reg_date)) $energySafety->visit_date = date_timestamp_get($reg_date) ? $reg_date->format('Y-m-d') : null;
                    $energySafety->rcd_x_phase0 = $row['rcd_x_phase0'];
                    $energySafety->rcd_x_phase1 = $row['rcd_x_phase1'];
                    $energySafety->rcd_x1_phase0 = $row['x1_phase0'];
                    $energySafety->rcd_x1_phase1 = $row['x1_phase180'];
                    $energySafety->rcd_x5_phase0 = $row['x5_phase0'];
                    $energySafety->rcd_x5_phase1 = $row['x5_phase180'];
                    $energySafety->ph_loop = $row['ph_loop'];
                    $energySafety->n_loop = $row['n_loop'];
                    $energySafety->notes = $row['notes'];
                    $energySafety->save();
                }
            }
        }
    }
}
