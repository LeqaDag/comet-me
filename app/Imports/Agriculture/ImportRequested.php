<?php

namespace App\Imports\Agriculture;

use App\Helpers\AzollaSystemCalculator; 
use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\User;
use App\Models\Community;
use App\Models\Household;
use App\Models\AzollaType;
use App\Models\AgricultureHolderStatus;
use App\Models\AgricultureSystemCycle;
use App\Models\AgricultureSystem;
use App\Models\AgricultureHolder;
use App\Models\AgricultureSharedHolder;
use App\Models\AgricultureHolderSystem;
use App\Models\AgricultureImportContext;
use App\Models\AgricultureInstallationType;
use App\Imports\Agriculture\ImportContext;
use Carbon\Carbon;
use Excel; 

class ImportRequested implements ToModel, WithHeadingRow
{ 
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // This number is fixed
        $numberOfHerd = 25;

        // Get data from KOBO 
        if($row["select_community"]) { 

            $community = Community::where("english_name", $row["select_community"])->first();
            $household = Household::where('comet_id', $row["select_household_name"])->first();
            $agricultureSystemCycle = AgricultureSystemCycle::where('name', $row["select_cycle_year"])->first();
            $agricultureInstallationType = AgricultureInstallationType::where('english_name', $row["select_installtion_type"])->first();
            
            $reg_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['submission_time']);

            if($household) {

                $household->phone_number = $row["phone_number"];
                $household->size_of_herd = $row["size_of_herd"];
                $household->save();
            }

            $cleanName = preg_replace('/\d/', '', $row["submitted_by"]);  
            $user = User::where('name', 'like', '%' . $cleanName . '%')->first();

            // agriculture user
            $agicultureHolder = new AgricultureHolder();
            $agicultureHolder->household_id = $household->id;
            $agicultureHolder->community_id = $community->id;
            $agicultureHolder->agriculture_holder_status_id = 1;
            $agicultureHolder->size_of_herds = $row["size_of_herd"];
            $agicultureHolder->size_of_goat = $row["size_of_goat"];
            $agicultureHolder->size_of_cow = $row["size_of_cow"];
            $agicultureHolder->size_of_camel = $row["size_of_camel"];
            $agicultureHolder->size_of_chicken = $row["size_of_chicken"];
            $agicultureHolder->agriculture_system_cycle_id = $agricultureSystemCycle->id;
            $agicultureHolder->agriculture_installation_type_id = $agricultureInstallationType->id;
            $agicultureHolder->area_of_installation = $row["select_area_type"];
            if(date_timestamp_get($reg_date)) {
        
                $agicultureHolder->requested_date = $reg_date ? $reg_date->format('Y-m-d') : null;            
            }
             
            // Calculation for azolla unit
            $agicultureHolder->azolla_unit = AzollaSystemCalculator::calculateAzollaUnits((int) $row["size_of_herd"]);

            $agicultureHolder->contribution_rate = $row["select_contribution_rate"];
            $agicultureHolder->area = $row["area"];
            $agicultureHolder->alternative_area = $row["alternative_area"];
            $agicultureHolder->notes = $row["agriculture_notes"];
            if($user) $agicultureHolder->user_id = $user->id;
            $agicultureHolder->save();

            // Save mapping between index and holder_id
            if ($row['index']) {
                
                $importContext = new AgricultureImportContext();
                $importContext->excel_index = $row['index'];
                $importContext->agriculture_holder_id = $agicultureHolder->id;
                $importContext->save();
            }

            $systems = AzollaSystemCalculator::calculateSystemsNeeded((int) $row["size_of_herd"]);

            foreach ($systems as $sys) {
                
                $systemType = $sys['system_type'];
                // Extract numeric part (20, 50, 100)
                preg_match('/(\d+)/', $systemType, $matches);
                $number = $matches[1] ?? null;

                if ($number) {
                    // Find a system with a name containing the number (case-insensitive)
                    $agricultureSystem = AgricultureSystem::where('name', 'LIKE', "%$number%")->first();

                    if ($agricultureSystem) {
                        $holderSystem = new AgricultureHolderSystem();
                        $holderSystem->agriculture_holder_id = $agicultureHolder->id;
                        $holderSystem->agriculture_system_id = $agricultureSystem->id;
                        $holderSystem->save();
                    }
                }
            }

        }
    }
}