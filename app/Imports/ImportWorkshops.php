<?php

namespace App\Imports;

use App\Models\WaterQualityResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\User;
use App\Models\Community;
use App\Models\Compound;
use App\Models\Household;
use App\Models\WorkshopType;
use App\Models\WorkshopCommunity;
use App\Models\WorkshopCommunityCoTrainer;
use App\Models\WorkshopCommunityPhoto;
use App\Models\Structure;

use Carbon\Carbon;
use Excel; 

class ImportWorkshops implements ToModel, WithHeadingRow
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
            $compound = Compound::where("english_name", $row["select_compound"])->first();
            $workshopType = WorkshopType::where('unique_name', $row["select_workshop_type"])->first();

            $workshopDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['workshop_date']);
         
            if($community) {

                $cleanName = preg_replace('/\d/', '', $row["select_lead_by"]);  
                $leadBy = User::where('name', 'like', '%' . $cleanName . '%')->first();

                $submittedByName = preg_replace('/\d/', '', $row["submitted_by"]);  
                $submittedBy = User::where('name', 'like', '%' . $submittedByName . '%')->first();

                $newWorkshopCommunity = new WorkshopCommunity(); 
                $newWorkshopCommunity->community_id = $community->id;
                $newWorkshopCommunity->workshop_type_id = $workshopType->id;
                if($row["select_compound"]) $newWorkshopCommunity->compound_id = $compound->id;
                $newWorkshopCommunity->date = $workshopDate->format('Y-m-d');
                $newWorkshopCommunity->number_of_male = $row["attendance_male"];
                $newWorkshopCommunity->number_of_female = $row["attendance_female"];
                $newWorkshopCommunity->number_of_youth = $row["attendance_youth"];
                $newWorkshopCommunity->number_of_hours = $row["workshop_hours"];
                $newWorkshopCommunity->submitted_by = $submittedBy->id;
                $newWorkshopCommunity->lead_by = $leadBy->id;
                $newWorkshopCommunity->lawyer = $row["lawyer"];
                $newWorkshopCommunity->notes = !empty($row["workshop_notes"]) ? trim(htmlspecialchars($row["workshop_notes"])) : null;
                $newWorkshopCommunity->stories = !empty($row["feedback"]) ? trim(htmlspecialchars($row["feedback"])) : null;
    
                $newWorkshopCommunity->save();

                if($row["select_co_trainer"]) {

                    $array = explode(" ", $row["select_co_trainer"]);

                    foreach ($array as $name) {
                        
                        $userName = preg_replace('/\d/', '', $name);  
                        $coTrainer = User::where('name', 'like', '%' . $userName . '%')->first();

                        if($coTrainer) {

                            $existCoTrainer = WorkshopCommunityCoTrainer::where("is_archived", 0)
                                ->where("workshop_community_id", $newWorkshopCommunity->id)
                                ->where("user_id", $coTrainer->id)
                                ->first();

                            if(!$existCoTrainer) {

                                $newCoTrainer = new WorkshopCommunityCoTrainer();
                                $newCoTrainer->user_id = $coTrainer->id;
                                $newCoTrainer->workshop_community_id = $newWorkshopCommunity->id;
                                $newCoTrainer->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
