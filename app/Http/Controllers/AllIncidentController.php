<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB; 
use Route;
use App\Models\AllEnergyMeter;
use App\Models\AllWaterHolder;
use App\Models\AllCameraIncident;
use App\Models\AllCameraIncidentPhoto;
use App\Models\AllCameraIncidentDamagedEquipment;
use App\Models\AllEnergyIncident;
use App\Models\AllEnergyIncidentPhoto;
use App\Models\AllEnergyIncidentAffectedHousehold;
use App\Models\AllEnergyIncidentDamagedEquipment;
use App\Models\AllEnergyIncidentSystemDamagedEquipment;
use App\Models\AllIncident;
use App\Models\AllIncidentOccurredStatus;
use App\Models\AllIncidentStatus;
use App\Models\AllInternetIncident;
use App\Models\AllInternetIncidentPhoto;
use App\Models\AllIncidentImpactType;
use App\Models\AllInternetIncidentAffectedArea;
use App\Models\AllInternetIncidentAffectedHousehold;
use App\Models\AllInternetIncidentDamagedEquipment;
use App\Models\AllInternetIncidentSystemDamagedEquipment;
use App\Models\AllWaterIncident;
use App\Models\AllWaterIncidentPhoto;
use App\Models\AllWaterIncidentDamagedEquipment;
use App\Models\AllWaterIncidentSystemDamagedEquipment;
use App\Models\AllWaterIncidentAffectedHousehold;
use App\Models\User;
use App\Models\Community;
use App\Models\Donor;
use App\Models\DisplacedHousehold;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\MeterCase;
use App\Models\Household;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\Region;
use App\Models\Incident;
use App\Models\IncidentEquipment;
use App\Models\InternetUser;
use App\Models\WaterSystem;
use App\Models\InternetSystem;
use App\Models\InternetSystemCommunity;
use App\Models\Router;
use App\Models\Switche;
use App\Models\InternetPtp;
use App\Models\InternetUisp;
use App\Models\InternetController;
use App\Models\InternetAp;

use App\Models\MgIncident;
use App\Models\IncidentStatusMgSystem;
use App\Models\MgIncidentEquipment;
use App\Models\MgAffectedHousehold;
use App\Models\MgIncidentPhoto;

use App\Models\FbsUserIncident;
use App\Models\FbsIncidentEquipment;
use App\Models\FbsIncidentStatus;
use App\Models\FbsIncidentPhoto;
use App\Models\IncidentStatusSmallInfrastructure;

use App\Models\H2oSystemIncident;
use App\Models\WaterIncidentEquipment;
use App\Models\H2oIncidentStatus;
use App\Models\H2oIncidentPhoto;
use App\Models\IncidentStatus;

use App\Models\InternetNetworkIncident;
use App\Models\InternetNetworkAffectedArea;
use App\Models\InternetNetworkAffectedHousehold;
use App\Models\InternetNetworkIncidentEquipment;
use App\Models\InternetNetworkIncidentPhoto;
use App\Models\InternetIncidentStatus;


use App\Models\CameraIncident;
use App\Models\CameraIncidentEquipment;
use App\Models\CameraIncidentPhoto;

use App\Exports\Incidents\MainIncidentSheet;
use App\Helpers\SequenceHelper;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class AllIncidentController extends Controller
{

    public function getTickets()
    {
        // Fetch tickets from the API
        $data = Http::get('https://cometme.org/api/tickets');
        $ticketsData = json_decode($data, true);
        $tickets = $ticketsData['tickets']; 

        // Loop through each ticket
        foreach ($tickets as $ticket) {

            //This code is for incidents tickets
            if($ticket['is_incident'] === 1) $this->handleIncidentStatuses($ticket);
        }
    }

    private function checkResolutionStatus($resolutionId)
    {
        $issueTypes = [
            'energy' => ['issues' => 'energy_issues', 'actions' => 'energy_actions', 'column' => 'energy_action_id'],
            'refrigerator' => ['issues' => 'refrigerator_issues', 'actions' => 'refrigerator_actions', 'column' => 'refrigerator_action_id'],
            'water' => ['issues' => 'water_issues', 'actions' => 'water_actions', 'column' => 'water_action_id'],
            'internet' => ['issues' => 'internet_issues', 'actions' => 'internet_actions', 'column' => 'internet_action_id'],
        ];

        foreach ($issueTypes as $type) {

            $result = DB::table('all_maintenance_ticket_actions')
                ->leftJoin($type['issues'], "{$type['issues']}.comet_id", 'all_maintenance_ticket_actions.action_id')
                ->leftJoin($type['actions'], "{$type['issues']}.{$type['column']}", "{$type['actions']}.id")
                ->leftJoin('action_categories', 'action_categories.id', "{$type['actions']}.action_category_id")
                ->where('all_maintenance_ticket_actions.action_id', $resolutionId)
                ->select("{$type['actions']}.english_name as action_name")
                ->first();

            if ($result && isset($result->action_name)) {

                return $this->getStatusFromActionName($result->action_name);
            }
        }

        return 'unknown';
    }


    private function getStatusFromActionName($actionName)
    {
        $name = strtolower(trim(preg_replace('/\s+/', ' ', $actionName)));

        if (str_contains($name, 'replace') || str_contains($name, 'replacement')) {
            return 'Replaced';
        }

        if (str_contains($name, 'repair')) {
            return 'Repaired';
        }

        return 'unchanged';

    }


    // Main function to handle incident statuses
    private function handleIncidentStatuses($ticket)
    {
        if (!empty($ticket['resolution'])) {

            $uniqueActions = array_unique($ticket['resolution']);

            foreach ($uniqueActions as $actionId) {
                $status = $this->checkResolutionStatus($actionId);

                if (!empty($ticket["ticket_comet_id"])) {

                    $incidentRecord = AllIncident::where("is_archived", 0)
                        ->where("comet_id", $ticket["ticket_comet_id"])
                        ->first();
                        
                    if ($incidentRecord) {
                        $incidentOccurredStatus = AllIncidentOccurredStatus::where("all_incident_id", $incidentRecord->id)
                            ->first();

                        if ($incidentRecord->incident_id === 1 && $incidentOccurredStatus) {
                            // Demolition
                            $statusId = AllIncidentStatus::where("incident_id", $incidentRecord->incident_id)
                                ->where(function ($query) {
                                    $query->where("status", "New");
                                        //->orWhere("status", "In Progress");
                                })->first();

                                
                            if ($statusId && $incidentOccurredStatus->all_incident_status_id === $statusId->id && 
                                $status !== 'unknown') {
                                $newStatus = AllIncidentStatus::where("incident_id", $incidentRecord->incident_id)
                                    ->where("status", $status)
                                    ->first();

                                if ($newStatus) {
                                    $incidentOccurredStatus->all_incident_status_id = $newStatus->id;
                                    $incidentOccurredStatus->save();
                                }
                            }

                        } else if ($incidentRecord->incident_id === 2 && $incidentOccurredStatus) {
                            // Settler (placeholder - you can implement logic here)
                        }
                    }
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        static $executed = false;

        // // get all camera incidents
        // $cameraIncidents = CameraIncident::where("is_archived", 0)->get();

        // foreach($cameraIncidents as $cameraIncident) {

        //     $allIncident = new AllIncident(); 
        //     $allIncident->community_id = $cameraIncident->community_id;
        //     $allIncident->incident_id = $cameraIncident->incident_id;
        //     $allIncident->service_type_id = 4;
        //     $allIncident->year = $cameraIncident->year;
        //     $allIncident->date = $cameraIncident->date;
        //     $allIncident->response_date = $cameraIncident->response_date;
        //     $allIncident->description = $cameraIncident->description;
        //     $allIncident->order_number = $cameraIncident->order_number;
        //     $allIncident->order_date = $cameraIncident->order_date;
        //     $allIncident->geolocation_lat = $cameraIncident->geolocation_lat;
        //     $allIncident->geolocation_long = $cameraIncident->geolocation_long;
        //     $allIncident->hearing_date = $cameraIncident->hearing_date;
        //     $allIncident->structure_description = $cameraIncident->structure_description;
        //     $allIncident->case_chronology = $cameraIncident->case_chronology;
        //     $allIncident->building_permit_request_number = $cameraIncident->building_permit_request_number;
        //     $allIncident->building_permit_request_submission_date = $cameraIncident->building_permit_request_submission_date;
        //     $allIncident->illegal_construction_case_number = $cameraIncident->illegal_construction_case_number;
        //     $allIncident->district_court_case_number = $cameraIncident->district_court_case_number;
        //     $allIncident->supreme_court_case_number = $cameraIncident->supreme_court_case_number;
        //     $allIncident->monetary_losses = $cameraIncident->monetary_losses;
        //     $allIncident->notes = $cameraIncident->notes;
        //     $allIncident->save();

        //     // Create new internet incident
        //     $allCameraIncident = new AllCameraIncident();
        //     $allCameraIncident->all_incident_id = $allIncident->id;
        //     $allCameraIncident->community_id = $cameraIncident->community_id;
        //     $allCameraIncident->save();
            
        //     // Create incident status
        //     $existInternetStatus = InternetIncidentStatus::findOrFail($cameraIncident->internet_incident_status_id);

        //     $allStatuses = AllIncidentStatus::all();

        //     $matchedStatus = $allStatuses->first(function ($status) use ($existInternetStatus) {
        //         return stripos($existInternetStatus->name, $status->status) !== false;
        //     });

        //     if ($matchedStatus) {

        //         $allIncidentOccurredStatus = new AllIncidentOccurredStatus();
        //         $allIncidentOccurredStatus->all_incident_id = $allIncident->id;
        //         $allIncidentOccurredStatus->all_incident_status_id = $matchedStatus->id;
        //         $allIncidentOccurredStatus->save();
        //     }
            
        //     // Get all equipment damaged
        //     $cameraDamagedEquipments = CameraIncidentEquipment::where("camera_incident_id", $cameraIncident->id)->get();

        //     foreach($cameraDamagedEquipments as $cameraDamagedEquipment) {

        //         $allCameraIncidentDamagedEquipment = new AllCameraIncidentDamagedEquipment();
        //         $allCameraIncidentDamagedEquipment->all_camera_incident_id = $allCameraIncident->id;
        //         $allCameraIncidentDamagedEquipment->incident_equipment_id = $cameraDamagedEquipment->incident_equipment_id;
        //         $allCameraIncidentDamagedEquipment->save();
        //     }

        //     // Get all photos
        //     $internetPhotos = CameraIncidentPhoto::where("camera_incident_id", $cameraIncident->id)->get();

        //     foreach($internetPhotos as $internetPhoto) {

        //         $allCameraIncidentPhoto = new AllCameraIncidentPhoto();
        //         $allCameraIncidentPhoto->all_camera_incident_id = $allCameraIncident->id;
        //         $allCameraIncidentPhoto->slug = $internetPhoto->slug;
        //         $allCameraIncidentPhoto->save();
        //     }
        // }


        // // get all internet incidents (network)
        // $internetIncidents = InternetNetworkIncident::where("is_archived", 0)->get();

        // foreach($internetIncidents as $internetIncident) {

        //     $allIncident = new AllIncident(); 
        //     $allIncident->community_id = $internetIncident->community_id;
        //     $allIncident->incident_id = $internetIncident->incident_id;
        //     $allIncident->service_type_id = 3;
        //     $allIncident->year = $internetIncident->year;
        //     $allIncident->date = $internetIncident->date;
        //     $allIncident->response_date = $internetIncident->response_date;
        //     $allIncident->description = $internetIncident->description;
        //     $allIncident->order_number = $internetIncident->order_number;
        //     $allIncident->order_date = $internetIncident->order_date;
        //     $allIncident->geolocation_lat = $internetIncident->geolocation_lat;
        //     $allIncident->geolocation_long = $internetIncident->geolocation_long;
        //     $allIncident->hearing_date = $internetIncident->hearing_date;
        //     $allIncident->structure_description = $internetIncident->structure_description;
        //     $allIncident->case_chronology = $internetIncident->case_chronology;
        //     $allIncident->building_permit_request_number = $internetIncident->building_permit_request_number;
        //     $allIncident->building_permit_request_submission_date = $internetIncident->building_permit_request_submission_date;
        //     $allIncident->illegal_construction_case_number = $internetIncident->illegal_construction_case_number;
        //     $allIncident->district_court_case_number = $internetIncident->district_court_case_number;
        //     $allIncident->supreme_court_case_number = $internetIncident->supreme_court_case_number;
        //     $allIncident->monetary_losses = $internetIncident->monetary_losses;
        //     $allIncident->notes = $internetIncident->notes;
        //     $allIncident->next_step = $internetIncident->next_step;
        //     $allIncident->save();

        //     // Create new internet incident
        //     $allInternetIncident = new AllInternetIncident();
        //     $allInternetIncident->all_incident_id = $allIncident->id;
        //     $allInternetIncident->community_id = $internetIncident->community_id;
        //     $allInternetIncident->save();
            
        //     // Create incident status
        //     $existInternetStatus = InternetIncidentStatus::findOrFail($internetIncident->internet_incident_status_id);

        //     $allStatuses = AllIncidentStatus::all();

        //     $matchedStatus = $allStatuses->first(function ($status) use ($existInternetStatus) {
        //         return stripos($existInternetStatus->name, $status->status) !== false;
        //     });

        //     if ($matchedStatus) {

        //         $allIncidentOccurredStatus = new AllIncidentOccurredStatus();
        //         $allIncidentOccurredStatus->all_incident_id = $allIncident->id;
        //         $allIncidentOccurredStatus->all_incident_status_id = $matchedStatus->id;
        //         $allIncidentOccurredStatus->save();
        //     }
            
        //     // Get all equipment damaged
        //     $internetDamagedEquipments = InternetNetworkIncidentEquipment::where("internet_network_incident_id", $internetIncident->id)->get();

        //     foreach($internetDamagedEquipments as $internetDamagedEquipment) {

        //         $allInternetIncidentDamagedEquipment = new allInternetIncidentDamagedEquipment();
        //         $allInternetIncidentDamagedEquipment->all_internet_incident_id = $allInternetIncident->id;
        //         $allInternetIncidentDamagedEquipment->incident_equipment_id = $internetDamagedEquipment->incident_equipment_id;
        //         $allInternetIncidentDamagedEquipment->save();
        //     }

        //     // Get all eaffected areas
        //     $internetAreas = InternetNetworkAffectedArea::where("internet_network_incident_id", $internetIncident->id)->get();

        //     foreach($internetAreas as $internetArea) {

        //         $allInternetIncidentArea = new AllInternetIncidentAffectedArea();
        //         $allInternetIncidentArea->all_internet_incident_id = $allInternetIncident->id;
        //         $allInternetIncidentArea->affected_community_id = $internetArea->affected_community_id;
        //         $allInternetIncidentArea->save();
        //     }

        //     // Get all affected households
        //     $internetAffectedHouseholds = InternetNetworkAffectedHousehold::where("internet_network_incident_id", $internetIncident->id)->get();

        //     foreach($internetAffectedHouseholds as $internetAffectedHousehold) {

        //         $allInternetIncidentAffectedHousehold = new AllInternetIncidentAffectedHousehold();
        //         $allInternetIncidentAffectedHousehold->all_internet_incident_id = $allInternetIncident->id;
        //         $allInternetIncidentAffectedHousehold->household_id = $internetAffectedHousehold->household_id;
        //         $allInternetIncidentAffectedHousehold->save();
        //     }

        //     // Get all photos
        //     $internetPhotos = InternetNetworkIncidentPhoto::where("internet_network_incident_id", $internetIncident->id)->get();

        //     foreach($internetPhotos as $internetPhoto) {

        //         $allInternetIncidentPhoto = new allInternetIncidentPhoto();
        //         $allInternetIncidentPhoto->all_internet_incident_id = $allInternetIncident->id;
        //         $allInternetIncidentPhoto->slug = $internetPhoto->slug;
        //         $allInternetIncidentPhoto->save();
        //     }
        // }

        
        // // get all Energy users from and store it into AllIncident
        // $energyIncidentUsers = FbsUserIncident::where("is_archived", 0)->get();
        
        // foreach($energyIncidentUsers as $energyIncidentUser) {

        //     $allIncident = new AllIncident(); 
        //     $allIncident->community_id = $energyIncidentUser->community_id;
        //     $allIncident->incident_id = $energyIncidentUser->incident_id;
        //     $allIncident->service_type_id = 1;
        //     $allIncident->year = $energyIncidentUser->year;
        //     $allIncident->date = $energyIncidentUser->date;
        //     $allIncident->response_date = $energyIncidentUser->response_date;
        //     $allIncident->description = $energyIncidentUser->description;
        //     $allIncident->order_number = $energyIncidentUser->order_number;
        //     $allIncident->order_date = $energyIncidentUser->order_date;
        //     $allIncident->geolocation_lat = $energyIncidentUser->geolocation_lat;
        //     $allIncident->geolocation_long = $energyIncidentUser->geolocation_long;
        //     $allIncident->hearing_date = $energyIncidentUser->hearing_date;
        //     $allIncident->structure_description = $energyIncidentUser->structure_description;
        //     $allIncident->case_chronology = $energyIncidentUser->case_chronology;
        //     $allIncident->building_permit_request_number = $energyIncidentUser->building_permit_request_number;
        //     $allIncident->building_permit_request_submission_date = $energyIncidentUser->building_permit_request_submission_date;
        //     $allIncident->illegal_construction_case_number = $energyIncidentUser->illegal_construction_case_number;
        //     $allIncident->district_court_case_number = $energyIncidentUser->district_court_case_number;
        //     $allIncident->supreme_court_case_number = $energyIncidentUser->supreme_court_case_number;
        //     $allIncident->monetary_losses = $energyIncidentUser->losses_energy;
        //     $allIncident->notes = $energyIncidentUser->notes;
        //     $allIncident->save();

        //     // Create new energy user incident
        //     $allEnergyIncident = new AllEnergyIncident();
        //     $allEnergyIncident->all_incident_id = $allIncident->id;
        //     $allEnergyIncident->all_energy_meter_id = $energyIncidentUser->energy_user_id;
        //     $allEnergyIncident->save();
            

        //     // Create incident status
        //     $fbsStatuses = FbsIncidentStatus::where("fbs_user_incident_id", $energyIncidentUser->id)->get();

        //     foreach($fbsStatuses as $fbsStatus) {

        //         $existStatus = IncidentStatusSmallInfrastructure::findOrFail($fbsStatus->incident_status_small_infrastructure_id);

        //         $allStatuses = AllIncidentStatus::all();

        //         $matchedStatus = $allStatuses->first(function ($status) use ($existStatus) {
        //             return stripos($existStatus->name, $status->status) !== false;
        //         });
    
        //         if ($matchedStatus) {
    
        //             $allIncidentOccurredStatus = new AllIncidentOccurredStatus();
        //             $allIncidentOccurredStatus->all_incident_id = $allIncident->id;
        //             $allIncidentOccurredStatus->all_incident_status_id = $matchedStatus->id;
        //             $allIncidentOccurredStatus->save();
        //         }
        //     }

        //     // Get all equipment damaged
        //     $damagedEquipments = FbsIncidentEquipment::where("fbs_user_incident_id", $energyIncidentUser->id)->get();

        //     foreach($damagedEquipments as $damagedEquipment) {

        //         $allEnergyIncidentDamagedEquipment = new AllEnergyIncidentDamagedEquipment();
        //         $allEnergyIncidentDamagedEquipment->all_energy_incident_id = $allEnergyIncident->id;
        //         $allEnergyIncidentDamagedEquipment->incident_equipment_id = $damagedEquipment->incident_equipment_id;
        //         $allEnergyIncidentDamagedEquipment->save();
        //     }

        //     // Get all photos
        //     $incidentPhotos = FbsIncidentPhoto::where("fbs_user_incident_id", $energyIncidentUser->id)->get();

        //     foreach($incidentPhotos as $incidentPhoto) {

        //         $allEnergyIncidentPhoto = new AllEnergyIncidentPhoto();
        //         $allEnergyIncidentPhoto->all_energy_incident_id = $allEnergyIncident->id;
        //         $allEnergyIncidentPhoto->slug = $incidentPhoto->slug;
        //         $allEnergyIncidentPhoto->save();
        //     }
        // }


        // // get all MG inicdent from MgIncident and store it into AllIncident

        // $mgIncidents = MgIncident::where("is_archived", 0)->get();

        // foreach($mgIncidents as $mgIncident) {

        //     $allIncident = new AllIncident(); 
        //     $allIncident->community_id = $mgIncident->community_id;
        //     $allIncident->incident_id = $mgIncident->incident_id;
        //     $allIncident->service_type_id = 1;
        //     $allIncident->year = $mgIncident->year;
        //     $allIncident->date = $mgIncident->date;
        //     $allIncident->response_date = $mgIncident->response_date;
        //     $allIncident->description = $mgIncident->description;
        //     $allIncident->order_number = $mgIncident->order_number;
        //     $allIncident->order_date = $mgIncident->order_date;
        //     $allIncident->geolocation_lat = $mgIncident->geolocation_lat;
        //     $allIncident->geolocation_long = $mgIncident->geolocation_long;
        //     $allIncident->hearing_date = $mgIncident->hearing_date;
        //     $allIncident->structure_description = $mgIncident->structure_description;
        //     $allIncident->case_chronology = $mgIncident->case_chronology;
        //     $allIncident->building_permit_request_number = $mgIncident->building_permit_request_number;
        //     $allIncident->building_permit_request_submission_date = $mgIncident->building_permit_request_submission_date;
        //     $allIncident->illegal_construction_case_number = $mgIncident->illegal_construction_case_number;
        //     $allIncident->district_court_case_number = $mgIncident->district_court_case_number;
        //     $allIncident->supreme_court_case_number = $mgIncident->supreme_court_case_number;
        //     $allIncident->monetary_losses = $mgIncident->monetary_losses;
        //     $allIncident->notes = $mgIncident->notes;
        //     $allIncident->save();

        //     // Create new energy incident
        //     $allEnergyIncident = new AllEnergyIncident();
        //     $allEnergyIncident->all_incident_id = $allIncident->id;
        //     $allEnergyIncident->energy_system_id = $mgIncident->energy_system_id;
        //     $allEnergyIncident->save();
            

        //     // Create incident status
        //     $existMgStatus = IncidentStatusMgSystem::findOrFail($mgIncident->incident_status_mg_system_id);

        //     $allStatuses = AllIncidentStatus::all();

        //     $matchedStatus = $allStatuses->first(function ($status) use ($existMgStatus) {
        //         return stripos($existMgStatus->name, $status->status) !== false;
        //     });

        //     if ($matchedStatus) {

        //         $allIncidentOccurredStatus = new AllIncidentOccurredStatus();
        //         $allIncidentOccurredStatus->all_incident_id = $allIncident->id;
        //         $allIncidentOccurredStatus->all_incident_status_id = $matchedStatus->id;
        //         $allIncidentOccurredStatus->save();
        //     }

        //     // Get all affected households
        //     $mgAffectedHouseholds = MgAffectedHousehold::where("mg_incident_id", $mgIncident->id)->get();

        //     foreach($mgAffectedHouseholds as $mgAffectedHousehold) {

        //         $allEnergyIncidentAffectedHousehold = new AllEnergyIncidentAffectedHousehold();
        //         $allEnergyIncidentAffectedHousehold->all_energy_incident_id = $allEnergyIncident->id;
        //         $allEnergyIncidentAffectedHousehold->household_id = $mgAffectedHousehold->household_id;
        //         $allEnergyIncidentAffectedHousehold->save();
        //     }

        //     // Get all equipment damaged
        //     $mgDamagedEquipments = MgIncidentEquipment::where("mg_incident_id", $mgIncident->id)->get();

        //     foreach($mgDamagedEquipments as $mgDamagedEquipment) {

        //         $allEnergyIncidentDamagedEquipment = new AllEnergyIncidentDamagedEquipment();
        //         $allEnergyIncidentDamagedEquipment->all_energy_incident_id = $allEnergyIncident->id;
        //         $allEnergyIncidentDamagedEquipment->incident_equipment_id = $mgDamagedEquipment->incident_equipment_id;
        //         $allEnergyIncidentDamagedEquipment->save();
        //     }

        //     // Get all photos
        //     $mgPhotos = MgIncidentPhoto::where("mg_incident_id", $mgIncident->id)->get();

        //     foreach($mgPhotos as $mgPhoto) {

        //         $allEnergyIncidentPhoto = new AllEnergyIncidentPhoto();
        //         $allEnergyIncidentPhoto->all_energy_incident_id = $allEnergyIncident->id;
        //         $allEnergyIncidentPhoto->slug = $mgPhoto->slug;
        //         $allEnergyIncidentPhoto->save();
        //     }
        // }
        
        
        // get all water incidents
        // if (!$executed) {
        //     $executed = true;
        //     $h2oIncidents = H2oSystemIncident::where("is_archived", 0)->get();

        //     foreach($h2oIncidents as $h2oIncident) {

        //         $allIncident = new AllIncident(); 
        //         $allIncident->community_id = $h2oIncident->community_id;
        //         $allIncident->incident_id = $h2oIncident->incident_id;
        //         $allIncident->service_type_id = 2;
        //         $allIncident->year = $h2oIncident->year;
        //         $allIncident->date = $h2oIncident->date;
        //         $allIncident->response_date = $h2oIncident->response_date;
        //         $allIncident->description = $h2oIncident->description;
        //         $allIncident->order_number = $h2oIncident->order_number;
        //         $allIncident->order_date = $h2oIncident->order_date;
        //         $allIncident->geolocation_lat = $h2oIncident->geolocation_lat;
        //         $allIncident->geolocation_long = $h2oIncident->geolocation_long;
        //         $allIncident->hearing_date = $h2oIncident->hearing_date;
        //         $allIncident->structure_description = $h2oIncident->structure_description;
        //         $allIncident->case_chronology = $h2oIncident->case_chronology;
        //         $allIncident->building_permit_request_number = $h2oIncident->building_permit_request_number;
        //         $allIncident->building_permit_request_submission_date = $h2oIncident->building_permit_request_submission_date;
        //         $allIncident->illegal_construction_case_number = $h2oIncident->illegal_construction_case_number;
        //         $allIncident->district_court_case_number = $h2oIncident->district_court_case_number;
        //         $allIncident->supreme_court_case_number = $h2oIncident->supreme_court_case_number;
        //         $allIncident->monetary_losses = $h2oIncident->monetary_losses;
        //         $allIncident->notes = $h2oIncident->notes;
        //         $allIncident->save();

        //         // Create new water incident
        //         $allWaterIncident = new AllWaterIncident();
        //         $allWaterIncident->all_incident_id = $allIncident->id;
        //         if($h2oIncident->water_system_id) $allWaterIncident->water_system_id = $h2oIncident->water_system_id;
        //         if($h2oIncident->all_water_holder_id) $allWaterIncident->all_water_holder_id = $h2oIncident->all_water_holder_id;
        //         $allWaterIncident->save();
                
        //         // Create incident status
        //         $waterStatuses = H2oIncidentStatus::where("h2o_system_incident_id", $h2oIncident->id)->get();

        //         foreach($waterStatuses as $waterStatus) {

        //             $existStatus = IncidentStatus::findOrFail($waterStatus->incident_status_id);

        //             $allStatuses = AllIncidentStatus::all();

        //             $matchedStatus = $allStatuses->first(function ($status) use ($existStatus) {
        //                 return stripos($existStatus->name, $status->status) !== false;
        //             });
        
        //             if ($matchedStatus) {
        
        //                 $allIncidentOccurredStatus = new AllIncidentOccurredStatus();
        //                 $allIncidentOccurredStatus->all_incident_id = $allIncident->id;
        //                 $allIncidentOccurredStatus->all_incident_status_id = $matchedStatus->id;
        //                 $allIncidentOccurredStatus->save();
        //             }
        //         }

        //         // Get all equipment damaged
        //         $waterDamagedEquipments = WaterIncidentEquipment::where("h2o_system_incident_id", $h2oIncident->id)->get();

        //         foreach($waterDamagedEquipments as $waterDamagedEquipment) {

        //             $allWaterIncidentDamagedEquipment = new AllWaterIncidentDamagedEquipment();
        //             $allWaterIncidentDamagedEquipment->all_water_incident_id = $allWaterIncident->id;
        //             $allWaterIncidentDamagedEquipment->incident_equipment_id = $waterDamagedEquipment->incident_equipment_id;
        //             $allWaterIncidentDamagedEquipment->save();
        //         }

        //         // Get all photos
        //         $waterPhotos = H2oIncidentPhoto::where("h2o_system_incident_id", $h2oIncident->id)->get();

        //         foreach($waterPhotos as $waterPhoto) {

        //             $allWaterIncidentPhoto = new AllWaterIncidentPhoto();
        //             $allWaterIncidentPhoto->all_water_incident_id = $allWaterIncident->id;
        //             $allWaterIncidentPhoto->slug = $waterPhoto->slug;
        //             $allWaterIncidentPhoto->save();
        //         }
        //     }
        // }

        $serviceFilter = $request->input('service_filter');
        $communityFilter = $request->input('community_filter');
        $incidentTypeFilter = $request->input('incident_filter');
        $dateFilter = $request->input('date_filter');

        if (Auth::guard('user')->user() != null) {

            $this->getTickets();

            if ($request->ajax()) {  
 
                $data = DB::table('all_incidents')
                    ->join('communities', 'all_incidents.community_id', 'communities.id')
                    ->join('service_types', 'all_incidents.service_type_id', 'service_types.id')
                    ->join('incidents', 'all_incidents.incident_id', 'incidents.id')
                    ->join('all_incident_occurred_statuses', 'all_incidents.id', 'all_incident_occurred_statuses.all_incident_id')
                    ->join('all_incident_statuses', 'all_incident_statuses.id', 'all_incident_occurred_statuses.all_incident_status_id')

                    ->leftJoin('all_energy_incidents', 'all_energy_incidents.all_incident_id', 'all_incidents.id')
                    ->leftJoin('all_energy_meters', 'all_energy_incidents.all_energy_meter_id', 'all_energy_meters.id')
                    ->leftJoin('households as energy_users', 'energy_users.id', 'all_energy_meters.household_id')
                    ->leftJoin('public_structures as energy_publics', 'energy_publics.id', 'all_energy_meters.public_structure_id')
                    ->leftJoin('energy_systems', 'energy_systems.id', 'all_energy_incidents.energy_system_id')

                    ->leftJoin('all_water_incidents', 'all_water_incidents.all_incident_id', 'all_incidents.id')
                    ->leftJoin('all_water_holders', 'all_water_incidents.all_water_holder_id', 'all_water_holders.id')
                    ->leftJoin('households as water_users', 'water_users.id', 'all_water_holders.household_id')
                    ->leftJoin('public_structures as water_publics', 'water_publics.id', 'all_water_holders.public_structure_id')
                    ->leftJoin('water_systems', 'water_systems.id', 'all_water_incidents.water_system_id')

                    ->leftJoin('all_internet_incidents', 'all_internet_incidents.all_incident_id', 'all_incidents.id')
                    ->leftJoin('internet_users', 'all_internet_incidents.internet_user_id', 'internet_users.id')
                    ->leftJoin('households as internet_holders', 'internet_holders.id', 'internet_users.household_id')
                    ->leftJoin('public_structures as internet_publics', 'internet_publics.id', 'internet_users.public_structure_id')
                    ->leftJoin('internet_system_communities', 'internet_system_communities.community_id', 
                        'all_incidents.community_id')
                    ->leftJoin('internet_systems', 'internet_systems.id', 'internet_system_communities.internet_system_id')

                    ->leftJoin('all_camera_incidents', 'all_incidents.id', 'all_camera_incidents.all_incident_id')
                    ->leftJoin('communities as cameras_communities', 'cameras_communities.id', 'all_camera_incidents.community_id')

                    ->where('all_incidents.is_archived', 0);
     
                if($serviceFilter != null) {

                    $data->where('service_types.id', $serviceFilter);
                }
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($incidentTypeFilter != null) {

                    $data->where('incidents.id', $incidentTypeFilter);
                }
                if ($dateFilter != null) {

                    $data->where('all_incidents.date', '>=', $dateFilter);
                }

                $data->select(
                    DB::raw("COALESCE(
                        energy_users.english_name,
                        energy_publics.english_name,
                        water_users.english_name,
                        water_publics.english_name,
                        internet_holders.english_name,
                        internet_publics.english_name,
                        energy_systems.name,
                        water_systems.name,
                        internet_systems.system_name,
                        cameras_communities.english_name
                    ) AS holder"),
                    
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(all_incident_statuses.status) 
                        SEPARATOR ', ') as incident_statuses"),
                    'all_incidents.date', 'all_incidents.year',
                    'all_incidents.id as id', 'all_incidents.created_at as created_at', 
                    'all_incidents.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'service_types.service_name as service',
                    'incidents.english_name as incident')
                ->latest()
                ->distinct()
                ->groupBy('all_incidents.id')
                ->orderBy('all_incidents.date', 'desc');

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        
                        $viewButton = "<a type='button' class='viewAllIncident' data-id='".$row->id."' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateAllIncident' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteAllIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('incidents.english_name', 'LIKE', "%$search%")
                                ->orWhere('incidents.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('service_types.service_name', 'LIKE', "%$search%")
                                ->orWhere('all_incidents.date', 'LIKE', "%$search%")
                                ->orWhere('all_incidents.year', 'LIKE', "%$search%")
                                ->orWhere('all_incident_statuses.status', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $serviceTypes = ServiceType::where('is_archived', 0)->get();
            $incidents = Incident::where('is_archived', 0)->get();

            return view('incidents.all.index', compact('communities', 'regions', 'serviceTypes', 'incidents'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $energySystems = EnergySystem::where('energy_system_type_id',1)
            ->where('is_archived', 0)
            ->get();

        $incidents = Incident::where('is_archived', 0)->get();

        $energyEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 2)
            ->orWhere("incident_equipment_type_id", 3)
            ->orderBy('name', 'ASC')
            ->get(); 

        $waterEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 1)
            ->orderBy('name', 'ASC')
            ->get();

        $internetEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 4)
            ->orderBy('name', 'ASC')
            ->get();

        $cameraEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 5)
            ->orderBy('name', 'ASC')
            ->get();

        $households = DB::table('all_energy_meters')
            ->join("households", "all_energy_meters.household_id", "households.id")
            ->select("households.id", "households.english_name")
            ->get();

        $waterUsers = DB::table('all_water_holders')
            ->join('households', 'all_water_holders.household_id', '=', 'households.id')
            ->where('all_water_holders.is_archived', 0)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.english_name', 'all_water_holders.id')
            ->get();

        $incidentStatuses = AllIncidentStatus::get();

        $serviceTypes = ServiceType::get();

        $impactTypes = AllIncidentImpactType::get();

        return view('incidents.all.create', compact('communities', 'energySystems', 'incidents', 
            'energyEquipments', 'waterEquipments', 'internetEquipments', 'cameraEquipments',
            'households', 'waterUsers', 'incidentStatuses', 'serviceTypes', 'impactTypes'));
    }

    // This function is to store the common fields in AllIncident Model
    private function createBaseIncident(Request $request, int $serviceTypeId, string $notesField, string $prefix): int
    {
        $allIncident = new AllIncident();
        $allIncident->service_type_id = $serviceTypeId;
        $allIncident->community_id = $request->community_id;
        $allIncident->incident_id = $request->incident_id;
        $allIncident->all_incident_impact_type_id = $request->all_incident_impact_type_id;
        $allIncident->date = $request->date;
        $year = explode('-', $request->date);
        $allIncident->year = $year[0];
        $allIncident->response_date = $request->input("{$prefix}_response_date");
        $allIncident->notes = $request->{$notesField};

        if ($request->incident_id === 4) {

            $allIncident->order_date = $request->input("{$prefix}_order_date");
            $allIncident->geolocation_lat = $request->input("{$prefix}_geolocation_lat");
            $allIncident->geolocation_long = $request->input("{$prefix}_geolocation_long");
            $allIncident->hearing_date = $request->input("{$prefix}_hearing_date");
            $allIncident->structure_description = $request->input("{$prefix}_structure_description");
            $allIncident->building_permit_request_number = $request->input("{$prefix}_building_permit_request_number");
            $allIncident->building_permit_request_submission_date = $request->input("{$prefix}_building_permit_request_submission_date");
            $allIncident->illegal_construction_case_number = $request->input("{$prefix}_illegal_construction_case_number");
            $allIncident->district_court_case_number = $request->input("{$prefix}_district_court_case_number");
            $allIncident->supreme_court_case_number = $request->input("{$prefix}_supreme_court_case_number");
            $allIncident->case_chronology = $request->input("{$prefix}_case_chronology");
        }

        $allIncident->save();

        return $allIncident->id;
    }

    // This function for saving the statues
    private function attachIncidentStatuses(array $statusIds, int $incidentId): void
    {
        foreach ($statusIds as $statusId) {

            $incidentStatus = new AllIncidentOccurredStatus();
            $incidentStatus->all_incident_status_id = $statusId;
            $incidentStatus->all_incident_id = $incidentId;
            $incidentStatus->save();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        //dd($request->all()); 

        if($request->service_type_ids) {

            foreach ($request->service_type_ids as $serviceTypeId) {
 
                if($serviceTypeId == 1) {

                    $allIncidentId = $this->createBaseIncident($request, 1, 'energy_notes', 'energy');

                    $this->attachIncidentStatuses($request->energy_incident_status_ids ?? [], $allIncidentId);
 
                    // Energy Incidents
                    $newAllEnergyIncident = new AllEnergyIncident();
                    $newAllEnergyIncident->all_incident_id = $allIncidentId;
                    $meterCase = MeterCase::where('meter_case_name_english', "%Incident%")->first();

                    if($request->energy_system_holder == "user") {

                        $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                            ->where("household_id", $request->energy_holder_system)
                            ->first();

                        if($allEnergyMeter) {

                            $newAllEnergyIncident->all_energy_meter_id = $allEnergyMeter->id;
                            if($meterCase) $allEnergyMeter->meter_case_id = $meterCase->id;
                            $allEnergyMeter->save();
                        }
                    } else if ($request->energy_system_holder == "public") {

                        $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                            ->where("public_structure_id", $request->energy_holder_system)
                            ->first();

                        if($allEnergyMeter) {

                            $newAllEnergyIncident->all_energy_meter_id = $allEnergyMeter->id;
                            $allEnergyMeter->meter_case_id = $meterCase->id;
                            $allEnergyMeter->save();
                        }
                    } else if ($request->energy_system_holder == "system") {

                        $energySystem = EnergySystem::findOrFail($request->energy_holder_system);

                        if($energySystem) $newAllEnergyIncident->energy_system_id = $energySystem->id;
                    }
                    
                    $newAllEnergyIncident->save();

                    if($request->energy_system_holder == "system") {

                        if ($request->energy_equipment && $request->equipment_type) {

                            $equipmentIds = array_values($request->energy_equipment);
                            $equipmentTypes = array_values($request->equipment_type);
    
                            for ($eq = 0; $eq < count($equipmentIds); $eq++) {
                        
                                $equipmentId = $equipmentIds[$eq];
                                $equipmentType = $equipmentTypes[$eq];
                        
                                $energyEquipment = new AllEnergyIncidentSystemDamagedEquipment();
                                $energyEquipment->all_energy_incident_id = $newAllEnergyIncident->id;
                                $energyEquipment->count = $request->input("addMoreInputFieldsEnergyUnit.$eq.subject");
                                $energyEquipment->cost = $request->input("addMoreInputFieldsEnergyCost.$eq.subject");
                        
                                // Set the correct foreign key based on the equipment type
                                switch ($equipmentType) {
                                    case "EnergyBattery":
                                        $energyEquipment->energy_system_battery_id = $equipmentId;
                                        break;
                        
                                    case "EnergyBatteryMount":
                                        $energyEquipment->energy_system_battery_mount_id = $equipmentId;
                                        break;
                        
                                    case "EnergyBatteryStatusProcessor":
                                        $energyEquipment->energy_system_battery_status_processor_id = $equipmentId;
                                        break;
                        
                                    case "EnergyBatteryTemperatureSensor":
                                        $energyEquipment->energy_system_battery_temperature_sensor_id = $equipmentId;
                                        break;
                        
                                    case "EnergyChargeController":
                                        $energyEquipment->energy_system_charge_controller_id = $equipmentId;
                                        break;
                        
                                    case "EnergyGenerator":
                                        $energyEquipment->energy_system_generator_id = $equipmentId;
                                        break;
                        
                                    case "EnergyInverter":
                                        $energyEquipment->energy_system_inverter_id = $equipmentId;
                                        break;
                        
                                    case "EnergyLoadRelay":
                                        $energyEquipment->energy_system_load_relay_id = $equipmentId;
                                        break;
                    
                                    case "EnergyMcbChargeController":
                                        $energyEquipment->energy_system_mcb_charge_controller_id = $equipmentId;
                                        break;
                    
                                    case "EnergyMcbInverter":
                                        $energyEquipment->energy_system_mcb_inverter_id = $equipmentId;
                                        break;
            
                                    case "EnergyMcbPv":
                                        $energyEquipment->energy_system_mcb_pv_id = $equipmentId;
                                        break;
    
                                    case "EnergyMonitoring":
                                        $energyEquipment->energy_system_monitoring_id = $equipmentId;
                                        break;
    
                                    case "EnergyPv":
                                        $energyEquipment->energy_system_pv_id = $equipmentId;
                                        break;
                                
                                    case "EnergyPvMount":
                                        $energyEquipment->energy_system_pv_mount_id = $equipmentId;
                                        break;
                                            
                                    case "EnergyRelayDriver":
                                        $energyEquipment->energy_system_relay_driver_id = $equipmentId;
                                        break;
                                        
                                    case "EnergyRemoteControlCenter":
                                        $energyEquipment->energy_system_remote_control_center_id = $equipmentId;
                                        break;
                                        
                                    case "EnergyWindTurbine":
                                        $energyEquipment->energy_system_wind_turbine_id = $equipmentId;
                                        break;    
                                    
                                    case "EnergyAirConditioner":
                                        $energyEquipment->energy_system_air_conditioner_id = $equipmentId;
                                        break;  
                                        
                                    default:
                                        break;
                                }
                        
                                $energyEquipment->save();
                            }
                        }

                        if($request->affected_households) { 

                            for($eah=0; $eah < count($request->affected_households); $eah++) {
                
                                $energyAffectedHousehold = new AllEnergyIncidentAffectedHousehold();
                                $energyAffectedHousehold->household_id = $request->affected_households[$eah];
                                $energyAffectedHousehold->all_energy_incident_id = $newAllEnergyIncident->id;
                                $energyAffectedHousehold->save();
                
                                $energyUser = AllEnergyMeter::where("is_archived", 0)
                                    ->where("household_id", $request->affected_households[$eah])
                                    ->first();
                
                                if($energyUser) {
                
                                    $energyUser->meter_case_id = 20;
                                    $energyUser->save();
                                }
                            }
                        }
                    } else {

                        if ($request->energy_equipment) {

                            $equipmentIds = array_values($request->energy_equipment);
                            $units = array_values($request->addMoreInputFieldsEnergyUnit);
                            $costs = array_values($request->addMoreInputFieldsEnergyCost);
    
                            for ($eq = 0; $eq  < count($equipmentIds); $eq++) {
    
                                $energyEquipment = new AllEnergyIncidentDamagedEquipment();
                                $energyEquipment->incident_equipment_id = $request->energy_equipment[$eq];
                                $energyEquipment->all_energy_incident_id = $newAllEnergyIncident->id;
                        
                                $energyEquipment->count = $units[$eq]['subject'];
                                $energyEquipment->cost = $costs[$eq]['subject'];
                        
                                $energyEquipment->save();
                            }
                        }
                    }
            
                    if ($request->file('energy_photos')) {
            
                        foreach($request->energy_photos as $photo) {
            
                            $original_name = $photo->getClientOriginalName();
                            $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                            $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                            if($request->energy_system_holder == "system") $destinationPath = public_path().'/incidents/mg/' ;
                            else $destinationPath = public_path().'/incidents/energy/' ;
                            $photo->move($destinationPath, $extra_name);
                
                            $energyIncidentPhoto = new AllEnergyIncidentPhoto();
                            $energyIncidentPhoto->slug = $extra_name;
                            $energyIncidentPhoto->all_energy_incident_id = $newAllEnergyIncident->id;
                            $energyIncidentPhoto->save();
                        }
                    }
                } else if($serviceTypeId == 2) {

                    // Water Incidents
                    $allIncidentId = $this->createBaseIncident($request, 2, 'water_notes', 'water');

                    $this->attachIncidentStatuses($request->water_incident_status_ids ?? [], $allIncidentId);

                    // Water Incidents
                    $newAllWaterIncident = new AllWaterIncident();
                    $newAllWaterIncident->all_incident_id = $allIncidentId;

                    if($request->water_system_holder == "user") {

                        $allWaterHolder = AllWaterHolder::where("is_archived", 0)
                            ->where("household_id", $request->water_holder_system)
                            ->first();

                        if($allWaterHolder) $newAllWaterIncident->all_water_holder_id = $allWaterHolder->id;
                    } else if ($request->water_system_holder == "public") {

                        $allWaterHolder = AllWaterHolder::where("is_archived", 0)
                            ->where("public_structure_id", $request->water_holder_system)
                            ->first();

                        if($allWaterHolder) $newAllWaterIncident->all_water_holder_id = $allWaterHolder->id;
                    } else if ($request->water_system_holder == "system") {

                        $waterSystem = WaterSystem::findOrFail($request->water_holder_system);

                        if($waterSystem) $newAllWaterIncident->water_system_id = $waterSystem->id;
                    }
                    
                    $newAllWaterIncident->save();

                    if($request->water_system_holder == "system") {

                        if ($request->water_equipment && $request->equipment_type) {

                            $equipmentIds = array_values($request->water_equipment);
                            $equipmentTypes = array_values($request->equipment_type);
    
                            for ($eq = 0; $eq < count($equipmentIds); $eq++) {
                        
                                $equipmentId = $equipmentIds[$eq];
                                $equipmentType = $equipmentTypes[$eq];
                        
                                $waterEquipment = new AllWaterIncidentSystemDamagedEquipment();
                                $waterEquipment->all_water_incident_id = $newAllWaterIncident->id;
                                $waterEquipment->count = $request->input("addMoreInputFieldsWaterUnit.$eq.subject");
                                $waterEquipment->cost = $request->input("addMoreInputFieldsWaterCost.$eq.subject");
                        
                                // Set the correct foreign key based on the equipment type
                                switch ($equipmentType) {
                                    case "WaterTank":
                                        $waterEquipment->water_system_tank_id = $equipmentId;
                                        break;
                        
                                    case "WaterPipe":
                                        $waterEquipment->water_system_pipe_id = $equipmentId;
                                        break;
                        
                                    case "WaterPump":
                                        $waterEquipment->water_system_pump_id = $equipmentId;
                                        break;
                        
                                    case "WaterFilter":
                                        $waterEquipment->water_system_filter_id = $equipmentId;
                                        break;
                        
                                    case "WaterConnector":
                                        $waterEquipment->water_system_connector_id = $equipmentId;
                                        break;
                        
                                    case "WaterValve":
                                        $waterEquipment->water_system_valve_id = $equipmentId;
                                        break;
                        
                                    case "WaterTap":
                                        $waterEquipment->water_system_tap_id = $equipmentId;
                                        break;
                                        
                                    default:
                                        break;
                                }
                        
                                $waterEquipment->save();
                            }
                        }

                        if($request->water_affected_households) { 

                            for($wah=0; $wah < count($request->water_affected_households); $wah++) {
                
                                $waterAffectedHousehold = new AllWaterIncidentAffectedHousehold();
                                $waterAffectedHousehold->household_id = $request->water_affected_households[$wah];
                                $waterAffectedHousehold->all_water_incident_id = $newAllWaterIncident->id;
                                $waterAffectedHousehold->save();
                            }
                        }
                    } else {

                        if ($request->water_equipment) {

                            $equipmentIds = array_values($request->water_equipment);
                            $units = array_values($request->addMoreInputFieldsWaterUnit);
                            $costs = array_values($request->addMoreInputFieldsWaterCost);
    
                            for ($eq = 0; $eq  < count($equipmentIds); $eq++) {
    
                                $waterEquipment = new AllWaterIncidentDamagedEquipment();
                                $waterEquipment->incident_equipment_id = $request->water_equipment[$eq];
                                $waterEquipment->all_water_incident_id = $newAllWaterIncident->id;
                        
                                $waterEquipment->count = $units[$eq]['subject'];
                                $waterEquipment->cost = $costs[$eq]['subject'];
                        
                                $waterEquipment->save();
                            }
                        }
                    }
            
                    if ($request->file('water_photos')) {
            
                        foreach($request->water_photos as $photo) {
            
                            $original_name = $photo->getClientOriginalName();
                            $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                            $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                            $destinationPath = public_path().'/incidents/water/' ;
                            $photo->move($destinationPath, $extra_name);
                
                            $waterIncidentPhoto = new AllWaterIncidentPhoto();
                            $waterIncidentPhoto->slug = $extra_name;
                            $waterIncidentPhoto->all_water_incident_id = $newAllWaterIncident->id;
                            $waterIncidentPhoto->save();
                        }
                    }
                } else if($serviceTypeId == 3) {

                    // Internet Incidents
                    $allIncidentId = $this->createBaseIncident($request, 3, 'internet_notes', 'internet');

                    $this->attachIncidentStatuses($request->internet_incident_status_ids ?? [], $allIncidentId);

                    // Internet Incidents
                    $newAllInternetIncident = new AllInternetIncident();
                    $newAllInternetIncident->all_incident_id = $allIncidentId;

                    if($request->internet_system_holder == "user") {

                        $internetUser = InternetUser::where("is_archived", 0)
                            ->where("household_id", $request->internet_holder_system)
                            ->first();

                        if($internetUser) $newAllInternetIncident->internet_user_id = $internetUser->id;
                    } else if ($request->internet_system_holder == "public") {

                        $internetUser = InternetUser::where("is_archived", 0) 
                            ->where("public_structure_id", $request->internet_holder_system)
                            ->first();

                        if($internetUser) $newAllInternetIncident->internet_user_id = $internetUser->id;
                    } else if ($request->internet_system_holder == "system") {

                        $newAllInternetIncident->community_id = $request->community_id;
                    }
                    
                    $newAllInternetIncident->save();

                    if($request->internet_system_holder == "system") {

                        if ($request->internet_equipment && $request->equipment_type) {

                            $equipmentIds = array_values($request->internet_equipment);
                            $equipmentTypes = array_values($request->equipment_type);
                            $equipmentCabinetFlags = array_values($request->equipment_is_cabinet);
    
                           // dd($equipmentCabinetFlags);
                            for ($eq = 0; $eq < count($equipmentIds); $eq++) {
                        
                                $equipmentId = $equipmentIds[$eq];
                                $equipmentType = $equipmentTypes[$eq];
                                $isCabinet = $equipmentCabinetFlags[$eq];
                        
                                $internetEquipment = new AllInternetIncidentSystemDamagedEquipment();
                                $internetEquipment->all_internet_incident_id = $newAllInternetIncident->id;
                                $internetEquipment->count = $request->input("addMoreInputFieldsInternetUnit.$eq.subject");
                                $internetEquipment->cost = $request->input("addMoreInputFieldsInternetCost.$eq.subject");
                        
                                // Set the correct foreign key based on the equipment type
                                if ($isCabinet) {

                                    $internetEquipment->network_cabinet_component_id = $equipmentId;
                                } else {

                                    switch ($equipmentType) {

                                        case "Router":
                                            $internetEquipment->router_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "Switche":
                                            $internetEquipment->switch_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetController":
                                            $internetEquipment->controller_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetPtp":
                                            $internetEquipment->ptp_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetAp":
                                            $internetEquipment->ap_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetApLite":
                                            $internetEquipment->ap_lite_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetUisp":
                                            $internetEquipment->uisp_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetConnector":
                                            $internetEquipment->connector_internet_system_id = $equipmentId;
                                            break;
                            
                                        case "InternetElectrician":
                                            $internetEquipment->electrician_internet_system_id = $equipmentId;
                                            break;
                                            
                                        default:
                                            break;
                                    }
                                }
                                $internetEquipment->save();
                            }
                        }

                        if($request->internet_affected_households) { 

                            for($inah=0; $inah < count($request->internet_affected_households); $inah++) {
                
                                $internetAffectedHousehold = new AllInternetIncidentAffectedHousehold();
                                $internetAffectedHousehold->household_id = $request->internet_affected_households[$inah];
                                $internetAffectedHousehold->all_internet_incident_id = $newAllInternetIncident->id;
                                $internetAffectedHousehold->save();
                            }
                        }

                        if($request->internet_affected_areas) { 

                            for($inaa=0; $inaa < count($request->internet_affected_areas); $inaa++) {
                
                                $internetAffectedArea = new AllInternetIncidentAffectedArea();
                                $internetAffectedArea->affected_community_id = $request->internet_affected_areas[$inaa];
                                $internetAffectedArea->all_internet_incident_id = $newAllInternetIncident->id;
                                $internetAffectedArea->save();
                            }
                        }
                    } else {

                        if ($request->internet_equipment) {

                            $equipmentIds = array_values($request->internet_equipment);
                            $units = array_values($request->addMoreInputFieldsInternetUnit);
                            $costs = array_values($request->addMoreInputFieldsInternetCost);
    
                            for ($eq = 0; $eq  < count($equipmentIds); $eq++) {
    
                                $internetEquipment = new AllInternetIncidentDamagedEquipment();
                                $internetEquipment->incident_equipment_id = $request->internet_equipment[$eq];
                                $internetEquipment->all_internet_incident_id = $newAllInternetIncident->id;
                        
                                $internetEquipment->count = $units[$eq]['subject'];
                                $internetEquipment->cost = $costs[$eq]['subject'];
                        
                                $internetEquipment->save();
                            }
                        }
                    }
                    
            
                    if ($request->file('internet_photos')) {
            
                        foreach($request->internet_photos as $photo) {
            
                            $original_name = $photo->getClientOriginalName();
                            $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                            $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                            $destinationPath = public_path().'/incidents/internet/' ;
                            $photo->move($destinationPath, $extra_name);
                
                            $internetIncidentPhoto = new AllInternetIncidentPhoto();
                            $internetIncidentPhoto->slug = $extra_name;
                            $internetIncidentPhoto->all_internet_incident_id = $newAllInternetIncident->id;
                            $internetIncidentPhoto->save();
                        }
                    }
                } else if($serviceTypeId == 4) {

                    // Camera Incidents
                    $allIncidentId = $this->createBaseIncident($request, 4, 'camera_notes', 'camera');

                    $this->attachIncidentStatuses($request->camera_incident_status_ids ?? [], $allIncidentId);
                    
                    // Camera Incidents
                    $newAllCameraIncident = new AllCameraIncident();
                    $newAllCameraIncident->all_incident_id = $allIncidentId;
                    $newAllCameraIncident->community_id = $request->community_id;
                    $newAllCameraIncident->save();

                    if ($request->camera_equipment) {

                        for ($cnq = 0; $cnq < count($request->camera_equipment); $cnq++) {

                            $cameraEquipment = new AllCameraIncidentDamagedEquipment();
                            $cameraEquipment->incident_equipment_id = $request->camera_equipment[$cnq];
                            $cameraEquipment->all_camera_incident_id = $newAllCameraIncident->id;
                    
                            $cameraEquipment->count = $request->input("addMoreInputFieldsCameraUnit.$cnq.subject");
                            $cameraEquipment->cost = $request->input("addMoreInputFieldsCameraCost.$cnq.subject");
                    
                            $cameraEquipment->save();
                        }
                    }
            
                    if ($request->file('camera_photos')) {
            
                        foreach($request->camera_photos as $photo) {
            
                            $original_name = $photo->getClientOriginalName();
                            $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                            $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                            $destinationPath = public_path().'/incidents/camera/' ;
                            $photo->move($destinationPath, $extra_name);
                
                            $cameraIncidentPhoto = new AllCameraIncidentPhoto();
                            $cameraIncidentPhoto->slug = $extra_name;
                            $cameraIncidentPhoto->all_camera_incident_id = $newAllCameraIncident->id;
                            $cameraIncidentPhoto->save();
                        }
                    }
                } 
            }
        }
    
        return redirect('/all-incident')
            ->with('message', 'New Incident Added Successfully!');
    }

     /**
     * Show the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allIncident = AllIncident::findOrFail($id);
        $allIncidentOccurredStatus = AllIncidentOccurredStatus::where("all_incident_id", $id)->get();

        $allEnergyIncident = null;
        $allWaterIncident = null;
        $allInternetIncident = null;
        $allCameraIncident = null;

        if($allIncident->service_type_id === 1)  {

            $allEnergyIncident = AllEnergyIncident::where("all_incident_id", $id)->firstOrFail();

           //die($allEnergyIncident);
        }
       
        else if($allIncident->service_type_id === 2) $allWaterIncident = AllWaterIncident::where("all_incident_id", $id)->first();

        else if($allIncident->service_type_id === 3) $allInternetIncident = AllInternetIncident::where("all_incident_id", $id)->first();

        else if($allIncident->service_type_id === 4) $allCameraIncident = AllCameraIncident::where("all_incident_id", $id)->first();

        return view('incidents.all.show', compact('allIncident', 'allEnergyIncident', 'allIncidentOccurredStatus', 
            'allWaterIncident', 'allInternetIncident', 'allCameraIncident' ));
    }


    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $energyUser = AllEnergyMeter::findOrFail($id);

        return response()->json($energyUser);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allIncident = AllIncident::findOrFail($id);
        $incidents = Incident::where('is_archived', 0)->get();
        $incidentStatuses = AllIncidentOccurredStatus::where("all_incident_id", $id)->get();
        $allEnergyIncident = AllEnergyIncident::where("all_incident_id", $id)->first();
        $allWaterIncident = AllWaterIncident::where("all_incident_id", $id)->first();
        $allInternetIncident = AllInternetIncident::where("all_incident_id", $id)->first();
        $allCameraIncident = AllCameraIncident::where("all_incident_id", $id)->first();
        $statuses = AllIncidentStatus::get();
        $userPublicEnergyEquipments = IncidentEquipment::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get(); 

        $energySystemComponents = null;
        $energyAffectedHouseholds = null;
        if ($allEnergyIncident && $allEnergyIncident->energy_system_id) {

            $energySystemComponents = $this->extractSystemComponents($allEnergyIncident->energy_system_id);
            $energyAffectedHouseholds = DB::table('all_energy_meters')
                ->join('households', 'all_energy_meters.household_id', 'households.id')
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $allIncident->community_id)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
        }

        $waterSystemComponents = null;
        $waterAffectedHouseholds = null;
        if ($allWaterIncident && $allWaterIncident->water_system_id) {

            $waterSystemComponents = $this->extractWaterSystemComponents($allWaterIncident->water_system_id);
            $waterAffectedHouseholds = DB::table('all_water_holders')
                ->join('households', 'all_water_holders.household_id', 'households.id')
                ->where('all_water_holders.is_archived', 0)
                ->where("all_water_holders.community_id", $allIncident->community_id)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
        }


        $internetSystemComponents = null;
        $internetAffectedHouseholds = null;
        $internetAffectedAreas = null;
        if ($allInternetIncident && $allInternetIncident->community_id) {

            $internetSystemCommunity = InternetSystemCommunity::where("is_archived", 0)
                ->where("community_id", $allInternetIncident->community_id)
                ->select("internet_system_id")
                ->first();

            $internetSystemComponents = $this->extractInternetSystemComponents($internetSystemCommunity->internet_system_id);
            $internetAffectedHouseholds = DB::table('internet_users')
                ->join('households', 'internet_users.household_id', 'households.id')
                ->where('internet_users.is_archived', 0)
                ->where("internet_users.community_id", $allInternetIncident->community_id)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
            $internetAffectedAreas = Community::where('is_archived', 0)
                ->where('internet_service', 'yes')
                ->orderBy('english_name', 'ASC')
                ->select('id', 'english_name')
                ->get();
        }

        return view('incidents.all.edit', compact('allIncident', 'incidents', 'allEnergyIncident', 
            'allWaterIncident', 'allInternetIncident', 'allCameraIncident', 'incidentStatuses',
            'statuses', 'userPublicEnergyEquipments', 'energySystemComponents', 'waterSystemComponents',
            'internetSystemComponents', 'energyAffectedHouseholds', 'waterAffectedHouseholds',
            'internetAffectedHouseholds', 'internetAffectedAreas'));
    }

    // This function is to update the Energy Equipment Damaged
    public function updateEnergyEquipmentDamaged($id, $units, $cost)
    {
        $energyEquipmentDamaged = AllEnergyIncidentDamagedEquipment::findOrFail($id);
        $energyEquipmentDamaged->count = $units;
        $energyEquipmentDamaged->cost = $cost;
        $energyEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Energy Equipment Damaged updated successfully']);
    }

    // This function is to update the Water Equipment Damaged
    public function updateWaterEquipmentDamaged($id, $units, $cost)
    {
        $waterEquipmentDamaged = AllWaterIncidentDamagedEquipment::findOrFail($id);
        $waterEquipmentDamaged->count = $units;
        $waterEquipmentDamaged->cost = $cost;
        $waterEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Water Equipment Damaged updated successfully']);
    }

    // This function is to update the Internet Equipment Damaged
    public function updateInternetEquipmentDamaged($id, $units, $cost)
    {
        $internetEquipmentDamaged = AllInternetIncidentDamagedEquipment::findOrFail($id);
        $internetEquipmentDamaged->count = $units;
        $internetEquipmentDamaged->cost = $cost;
        $internetEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Internet Equipment Damaged updated successfully']);
    }

    // This function is to update the Camera Equipment Damaged
    public function updateCameraEquipmentDamaged($id, $units, $cost)
    {
        $cameraEquipmentDamaged = AllCameraIncidentDamagedEquipment::findOrFail($id);
        $cameraEquipmentDamaged->count = $units;
        $cameraEquipmentDamaged->cost = $cost;
        $cameraEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Camera Equipment Damaged updated successfully']);
    }

    // This function is to update the Energy System Equipment Damaged
    public function updateEnergySystemEquipmentDamaged($id, $units, $cost)
    {
        $energySystemEquipmentDamaged = AllEnergyIncidentSystemDamagedEquipment::findOrFail($id);
        $energySystemEquipmentDamaged->count = $units;
        $energySystemEquipmentDamaged->cost = $cost;
        $energySystemEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Energy System Equipment Damaged updated successfully']);
    }

    // This function is to update the Water System Equipment Damaged
    public function updateWaterSystemEquipmentDamaged($id, $units, $cost)
    {
        $waterSystemEquipmentDamaged = AllWaterIncidentSystemDamagedEquipment::findOrFail($id);
        $waterSystemEquipmentDamaged->count = $units;
        $waterSystemEquipmentDamaged->cost = $cost;
        $waterSystemEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Water System Equipment Damaged updated successfully']);
    }

    // This function is to update the Internet System Equipment Damaged
    public function updateInternetSystemEquipmentDamaged($id, $units, $cost)
    {
        $internetSystemEquipmentDamaged = AllInternetIncidentSystemDamagedEquipment::findOrFail($id);
        $internetSystemEquipmentDamaged->count = $units;
        $internetSystemEquipmentDamaged->cost = $cost;
        $internetSystemEquipmentDamaged->save();

        return response()->json(['success' => 1, 'msg' => 'Internet System Equipment Damaged updated successfully']);
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $allIncident = AllIncident::findOrFail($id);
        if($request->date) {

            $allIncident->date = $request->date;
            $year = explode('-', $request->date);
            $allIncident->year = $year[0];
        }
        $allIncident->response_date = $request->response_date;
        $allIncident->description = $request->description;
        if($request->order_number) $allIncident->order_number = $request->order_number;
        if($request->order_date) $allIncident->order_date = $request->order_date;
        if($request->geolocation_lat) $allIncident->geolocation_lat = $request->geolocation_lat;
        if($request->geolocation_long) $allIncident->geolocation_long = $request->geolocation_long;
        if($request->hearing_date) $allIncident->hearing_date = $request->hearing_date;
        if($request->structure_description) $allIncident->structure_description = $request->structure_description;
        if($request->case_chronology) $allIncident->case_chronology = $request->case_chronology;
        if($request->building_permit_request_number) $allIncident->building_permit_request_number = $request->building_permit_request_number;
        if($request->building_permit_request_submission_date) $allIncident->building_permit_request_submission_date = $request->building_permit_request_submission_date;
        if($request->illegal_construction_case_number) $allIncident->illegal_construction_case_number = $request->illegal_construction_case_number;
        if($request->district_court_case_number) $allIncident->district_court_case_number = $request->district_court_case_number;
        if($request->supreme_court_case_number) $allIncident->supreme_court_case_number = $request->supreme_court_case_number;
        if($request->monetary_losses) $allIncident->monetary_losses = $request->monetary_losses;
        if($request->next_step) $allIncident->next_step = $request->next_step;
        if($request->notes) $allIncident->notes = $request->notes;
        $allIncident->save();

        if($request->new_statuses) {

            for ($stas = 0; $stas < count($request->new_statuses); $stas++) {

                $incidentStatus = new AllIncidentOccurredStatus();
                $incidentStatus->all_incident_status_id = $request->new_statuses[$stas];
                $incidentStatus->all_incident_id = $id;
                $incidentStatus->save();
            }
        }

        $allEnergyIncident = AllEnergyIncident::where("all_incident_id", $id)->first();

        // Energy equipment damaged (USER/PUBLIC)
        if ($request->energy_equipment_damaged_ids && $allEnergyIncident) {
            
            for ($enrEqp = 0; $enrEqp < count($request->energy_equipment_damaged_ids); $enrEqp++) {

                $energyDamagedEquipment = new AllEnergyIncidentDamagedEquipment();
                $energyDamagedEquipment->incident_equipment_id = $request->energy_equipment_damaged_ids[$enrEqp];
                $energyDamagedEquipment->all_energy_incident_id = $allEnergyIncident->id;
                $energyDamagedEquipment->count = $request->input("energy_equipment_damaged_units.$enrEqp.subject") ?? 0;
                $energyDamagedEquipment->cost = $request->input("energy_equipment_damaged_costs.$enrEqp.subject") ?? 0;
        
                $energyDamagedEquipment->save();
            }
        }

        // Store new Energy photos
        if ($request->file('more_photos') && $allEnergyIncident) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                if($allEnergyIncident->all_energy_meter_id) $destinationPath = public_path().'/incidents/energy/' ;
                else $destinationPath = public_path().'/incidents/mg/' ;
                $photo->move($destinationPath, $extra_name);
    
                $energyIncidentPhoto = new AllEnergyIncidentPhoto();
                $energyIncidentPhoto->slug = $extra_name;
                $energyIncidentPhoto->all_energy_incident_id = $allEnergyIncident->id;
                $energyIncidentPhoto->save();
            }
        }

        // Store energy affected households
        if($request->energy_affected_households_ids) { 

            for($eah=0; $eah < count($request->energy_affected_households_ids); $eah++) {

                $energyAffectedHousehold = new AllEnergyIncidentAffectedHousehold();
                $energyAffectedHousehold->household_id = $request->energy_affected_households_ids[$eah];
                $energyAffectedHousehold->all_energy_incident_id = $allEnergyIncident->id;
                $energyAffectedHousehold->save();

                $energyUser = AllEnergyMeter::where("is_archived", 0)
                    ->where("household_id", $request->energy_affected_households_ids[$eah])
                    ->first();

                if($energyUser) {

                    $energyUser->meter_case_id = 20;
                    $energyUser->save();
                }
            }
        }


        $allWaterIncident = AllWaterIncident::where("all_incident_id", $id)->first();

        // Water equipment damaged (USER/PUBLIC)
        if ($request->water_equipment_damaged_ids && $allWaterIncident) {
            
            for ($enrEqp = 0; $enrEqp < count($request->water_equipment_damaged_ids); $enrEqp++) {

                $waterDamagedEquipment = new AllWaterIncidentDamagedEquipment();
                $waterDamagedEquipment->incident_equipment_id = $request->water_equipment_damaged_ids[$enrEqp];
                $waterDamagedEquipment->all_water_incident_id = $allWaterIncident->id;
                $waterDamagedEquipment->count = $request->input("water_equipment_damaged_units.$enrEqp.subject") ?? 0;
                $waterDamagedEquipment->cost = $request->input("water_equipment_damaged_costs.$enrEqp.subject") ?? 0;
        
                $waterDamagedEquipment->save();
            }
        }

        // Store new Water photos
        if ($request->file('more_photos') && $allWaterIncident) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/water/' ;
                $photo->move($destinationPath, $extra_name);
    
                $waterIncidentPhoto = new AllWaterIncidentPhoto();
                $waterIncidentPhoto->slug = $extra_name;
                $waterIncidentPhoto->all_water_incident_id = $allWaterIncident->id;
                $waterIncidentPhoto->save();
            }
        }

        // Store water affected households
        if($request->water_affected_households_ids) { 

            for($wahs=0; $wahs < count($request->water_affected_households_ids); $wahs++) {

                $waterAffectedHousehold = new AllWaterIncidentAffectedHousehold();
                $waterAffectedHousehold->household_id = $request->water_affected_households_ids[$wahs];
                $waterAffectedHousehold->all_water_incident_id = $allWaterIncident->id;
                $waterAffectedHousehold->save();
            }
        }

        $allInternetIncident = AllInternetIncident::where("all_incident_id", $id)->first();

        // Internet equipment damaged (USER/PUBLIC)
        if ($request->internet_equipment_damaged_ids && $allInternetIncident) {
            
            for ($enrEqp = 0; $enrEqp < count($request->internet_equipment_damaged_ids); $enrEqp++) {

                $internetDamagedEquipment = new AllInternetIncidentDamagedEquipment();
                $internetDamagedEquipment->incident_equipment_id = $request->internet_equipment_damaged_ids[$enrEqp];
                $internetDamagedEquipment->all_internet_incident_id = $allInternetIncident->id;
                $internetDamagedEquipment->count = $request->input("internet_equipment_damaged_units.$enrEqp.subject") ?? 0;
                $internetDamagedEquipment->cost = $request->input("internet_equipment_damaged_costs.$enrEqp.subject") ?? 0;
        
                $internetDamagedEquipment->save();
            }
        }

        // Store new internet photos
        if ($request->file('more_photos') && $allInternetIncident) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/internet/' ;
                $photo->move($destinationPath, $extra_name);
    
                $internetIncidentPhoto = new AllInternetIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->all_internet_incident_id = $allInternetIncident->id;
                $internetIncidentPhoto->save();
            }
        }

        // Store internet affected households
        if($request->internet_affected_households_ids) { 

            for($itahs=0; $itahs < count($request->internet_affected_households_ids); $itahs++) {

                $internetAffectedHousehold = new AllInternetIncidentAffectedHousehold();
                $internetAffectedHousehold->household_id = $request->internet_affected_households_ids[$itahs];
                $internetAffectedHousehold->all_internet_incident_id = $allInternetIncident->id;
                $internetAffectedHousehold->save();
            }
        }

        // Store internet affected areas
        if($request->internet_affected_areas_ids) { 

            for($inaa=0; $inaa < count($request->internet_affected_areas_ids); $inaa++) {

                $internetAffectedArea = new AllInternetIncidentAffectedArea();
                $internetAffectedArea->affected_community_id = $request->internet_affected_areas_ids[$inaa];
                $internetAffectedArea->all_internet_incident_id = $allInternetIncident->id;
                $internetAffectedArea->save();
            }
        }

        $allCameraIncident = AllCameraIncident::where("all_incident_id", $id)->first();

        // Camera equipment damaged 
        if ($request->camera_equipment_damaged_ids && $allCameraIncident) {
            
            for ($enrEqp = 0; $enrEqp < count($request->camera_equipment_damaged_ids); $enrEqp++) {

                $cameraDamagedEquipment = new AllCameraIncidentDamagedEquipment();
                $cameraDamagedEquipment->incident_equipment_id = $request->camera_equipment_damaged_ids[$enrEqp];
                $cameraDamagedEquipment->all_camera_incident_id = $allCameraIncident->id;
                $cameraDamagedEquipment->count = $request->input("camera_equipment_damaged_units.$enrEqp.subject") ?? 0;
                $cameraDamagedEquipment->cost = $request->input("camera_equipment_damaged_costs.$enrEqp.subject") ?? 0;
        
                $cameraDamagedEquipment->save();
            }
        }

        // Store new camera photos
        if ($request->file('more_photos') && $allCameraIncident) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/camera/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraIncidentPhoto = new AllCameraIncidentPhoto();
                $cameraIncidentPhoto->slug = $extra_name;
                $cameraIncidentPhoto->all_camera_incident_id = $allCameraIncident->id;
                $cameraIncidentPhoto->save();
            }
        }


        // This for update the energy system equipments
        if ($request->energy_system_equipment_damaged_ids && $request->energy_system_equipment_types && $allEnergyIncident) {

            $equipmentIds = array_values($request->energy_system_equipment_damaged_ids);
            $equipmentTypes = array_values($request->energy_system_equipment_types);

            for ($eq = 0; $eq < count($equipmentIds); $eq++) {

                $equipmentId = $equipmentIds[$eq];
                $equipmentType = $equipmentTypes[$eq]['subject'] ?? null;

                $count = $request->input("energy_system_equipment_damaged_units.$eq.subject");
                $cost = $request->input("energy_system_equipment_damaged_costs.$eq.subject");

                if (!$equipmentId || !$equipmentType || !is_numeric($count) || !is_numeric($cost)) {
                    continue; // skip invalid or incomplete rows
                }

                $energyEquipment = new AllEnergyIncidentSystemDamagedEquipment();
                $energyEquipment->all_energy_incident_id = $allEnergyIncident->id;
                $energyEquipment->count = $count;
                $energyEquipment->cost = $cost;

                switch ($equipmentType) {
                    case "EnergyBattery":
                        $energyEquipment->energy_system_battery_id = $equipmentId;
                        break;
                    case "EnergyBatteryMount":
                        $energyEquipment->energy_system_battery_mount_id = $equipmentId;
                        break;
                    case "EnergyBatteryStatusProcessor":
                        $energyEquipment->energy_system_battery_status_processor_id = $equipmentId;
                        break;
                    case "EnergyBatteryTemperatureSensor":
                        $energyEquipment->energy_system_battery_temperature_sensor_id = $equipmentId;
                        break;
                    case "EnergyChargeController":
                        $energyEquipment->energy_system_charge_controller_id = $equipmentId;
                        break;
                    case "EnergyGenerator":
                        $energyEquipment->energy_system_generator_id = $equipmentId;
                        break;
                    case "EnergyInverter":
                        $energyEquipment->energy_system_inverter_id = $equipmentId;
                        break;
                    case "EnergyLoadRelay":
                        $energyEquipment->energy_system_load_relay_id = $equipmentId;
                        break;
                    case "EnergyMcbChargeController":
                        $energyEquipment->energy_system_mcb_charge_controller_id = $equipmentId;
                        break;
                    case "EnergyMcbInverter":
                        $energyEquipment->energy_system_mcb_inverter_id = $equipmentId;
                        break;
                    case "EnergyMcbPv":
                        $energyEquipment->energy_system_mcb_pv_id = $equipmentId;
                        break;
                    case "EnergyMonitoring":
                        $energyEquipment->energy_system_monitoring_id = $equipmentId;
                        break;
                    case "EnergyPv":
                        $energyEquipment->energy_system_pv_id = $equipmentId;
                        break;
                    case "EnergyPvMount":
                        $energyEquipment->energy_system_pv_mount_id = $equipmentId;
                        break;
                    case "EnergyRelayDriver":
                        $energyEquipment->energy_system_relay_driver_id = $equipmentId;
                        break;
                    case "EnergyRemoteControlCenter":
                        $energyEquipment->energy_system_remote_control_center_id = $equipmentId;
                        break;
                    case "EnergyWindTurbine":
                        $energyEquipment->energy_system_wind_turbine_id = $equipmentId;
                        break;
                    case "EnergyAirConditioner":
                        $energyEquipment->energy_system_air_conditioner_id = $equipmentId;
                        break;
                    default:
                        continue 2;
                }

                $energyEquipment->save();
            }
        }

        // This for update the water system equipments
        if ($request->water_system_equipment_damaged_ids && $request->water_system_equipment_types && $allWaterIncident) {

            $equipmentIds = array_values($request->water_system_equipment_damaged_ids);
            $equipmentTypes = array_values($request->water_system_equipment_types);

            for ($eq = 0; $eq < count($equipmentIds); $eq++) {

                $equipmentId = $equipmentIds[$eq];
                $equipmentType = $equipmentTypes[$eq]['subject'] ?? null;

                $count = $request->input("water_system_equipment_damaged_units.$eq.subject");
                $cost = $request->input("water_system_equipment_damaged_costs.$eq.subject");

                if (!$equipmentId || !$equipmentType || !is_numeric($count) || !is_numeric($cost)) {
                    continue; // skip invalid or incomplete rows
                }

                $waterEquipment = new AllWaterIncidentSystemDamagedEquipment();
                $waterEquipment->all_water_incident_id = $allWaterIncident->id;
                $waterEquipment->count = $count;
                $waterEquipment->cost = $cost;

                switch ($equipmentType) {
                    case "WaterTank":
                        $waterEquipment->water_system_tank_id = $equipmentId;
                    break;
        
                    case "WaterPipe":
                        $waterEquipment->water_system_pipe_id = $equipmentId;
                    break;
                    case "WaterPump":
                        $waterEquipment->water_system_pump_id = $equipmentId;
                    break;
                    case "WaterFilter":
                        $waterEquipment->water_system_filter_id = $equipmentId;
                    break;
                    case "WaterConnector":
                        $waterEquipment->water_system_connector_id = $equipmentId;
                    break;
                    case "WaterValve":
                        $waterEquipment->water_system_valve_id = $equipmentId;
                    break;
                    case "WaterTap":
                        $waterEquipment->water_system_tap_id = $equipmentId;
                        break;
                    default:
                        continue 2;
                }

                $waterEquipment->save();
            }
        }

        // This for update the internet system equipments
        if ($request->internet_system_equipment_damaged_ids && $request->internet_system_equipment_types && $allInternetIncident) {

            $equipmentIds = array_values($request->internet_system_equipment_damaged_ids);
            $equipmentTypes = array_values($request->internet_system_equipment_types);
            $equipmentCabinetFlags = array_values($request->equipment_is_cabinet);

            for ($eq = 0; $eq < count($equipmentIds); $eq++) {

                $equipmentId = $equipmentIds[$eq];
                $equipmentType = $equipmentTypes[$eq]['subject'] ?? null;
                $isCabinet = $equipmentCabinetFlags[$eq] ?? '0';

                $count = $request->input("internet_system_equipment_damaged_units.$eq.subject");
                $cost = $request->input("internet_system_equipment_damaged_costs.$eq.subject");

                if (!$equipmentId || !$equipmentType || !is_numeric($count) || !is_numeric($cost)) {
                    continue; 
                }

                $internetEquipment = new AllInternetIncidentSystemDamagedEquipment();
                $internetEquipment->all_internet_incident_id = $allInternetIncident->id;
                $internetEquipment->count = $count;
                $internetEquipment->cost = $cost;

                if ($isCabinet == 1) {

                    $internetEquipment->network_cabinet_component_id = $equipmentId;
                } else {

                    switch ($equipmentType) {
                        case "Router":
                            $internetEquipment->router_internet_system_id = $equipmentId;
                            break;
            
                        case "Switche":
                            $internetEquipment->switch_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetController":
                            $internetEquipment->controller_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetPtp":
                            $internetEquipment->ptp_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetAp":
                            $internetEquipment->ap_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetApLite":
                            $internetEquipment->ap_lite_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetUisp":
                            $internetEquipment->uisp_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetConnector":
                            $internetEquipment->connector_internet_system_id = $equipmentId;
                            break;
            
                        case "InternetElectrician":
                            $internetEquipment->electrician_internet_system_id = $equipmentId;
                            break;
                        default:
                            continue 2;
                    }
                }
                $internetEquipment->save();
            }
        }

        return redirect('/all-incident')->with('message', 'Incident Record Updated Successfully!');
    }

    protected function extractSystemComponents($systemId)
    {
        $system = EnergySystem::with([
            'batteries', 'batteryMount', 'pvs', 'pvMount', 'chargeController',
            'inverter', 'loadRelay', 'relayDriver', 'bsp', 'bts', 'monitoring',
            'remoteControlCenter', 'mcbChargeController', 'mcbInverter', 'mcbPv',
            'airConditioners', 'generator', 'windTurbine'
        ])->find($systemId);

        if (!$system) {
            return null;
        }

        $components = collect()
            ->merge($system->batteries)->merge($system->batteryMount)
            ->merge($system->pvs)->merge($system->pvMount)
            ->merge($system->chargeController)->merge($system->inverter)
            ->merge($system->loadRelay)->merge($system->relayDriver)
            ->merge($system->bsp)->merge($system->bts)->merge($system->monitoring)
            ->merge($system->remoteControlCenter)->merge($system->mcbChargeController)
            ->merge($system->mcbInverter)->merge($system->mcbPv)
            ->merge($system->airConditioners)->merge($system->generator)
            ->merge($system->windTurbine);

        return $components->map(function ($component) {
            switch (get_class($component)) {
 
                case \App\Models\EnergyBattery::class:
                    $name = $component->battery_model;
                    break;
                case \App\Models\EnergyPv::class:
                    $name = $component->pv_model;
                    break;
                case \App\Models\EnergyChargeController::class:
                    $name = $component->charge_controller_model;
                    break;
                case \App\Models\EnergyInverter::class:
                    $name = $component->inverter_model;
                    break;
                case \App\Models\EnergyLoadRelay::class:
                    $name = $component->load_relay_model;
                    break;
                case \App\Models\EnergyMonitoring::class:
                    $name = $component->monitoring_model;
                    break;
                case \App\Models\EnergyGenerator::class:
                    $name = $component->generator_model;
                    break;
                case \App\Models\EnergyWindTurbine::class:
                    $name = $component->wind_turbine_model;
                    break;
                case \App\Models\EnergyBatteryTemperatureSensor::class:
                    $name = $component->BTS_model;
                    break;
                case \App\Models\EnergyPvMount::class:
                    $name = $component->model;
                    break;
                case \App\Models\EnergyBatteryMount::class:
                    $name = $component->model;
                    break;
                case \App\Models\EnergyMcbInverter::class:
                    $name = $component->inverter_MCB_model;
                    break;
                default:
                    $name = $component->model ?? 'Unnamed Component';
            }

            return [
                'component_energy_system_id' => $component->pivot->id ?? $component->id,
                'model_name' => $name,
                'type' => class_basename($component),
                'cost' => $component->pivot->cost ?? null,
            ];
        })->values();
    }


    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSystemComponents($systemId)
    {
        $equipmentList = $this->extractSystemComponents($systemId);

        if (!$equipmentList) {
            return response()->json(['message' => 'System not found'], 404);
        }

        return response()->json([
            'equipment' => $equipmentList,
        ]);
    }


    protected function extractWaterSystemComponents($systemId)
    {
        $system = WaterSystem::with([
            'tanks',
            'pipes',
            'pumps',
            'filters',
            'connectors',
            'valves',
            'taps'
        ])->find($systemId);

        if (!$system) {
            return null;
        }

        $components = collect();

        $components = $components->merge($system->tanks)
            ->merge($system->pipes)
            ->merge($system->pumps)
            ->merge($system->filters)
            ->merge($system->connectors)
            ->merge($system->valves)
            ->merge($system->taps);

        return $components->map(function ($component) {
            $class = get_class($component);
            $type = class_basename($component);
            $name = $component->model ?? 'Unnamed Component';

            // Set pivot cost field based on the component type
            $pivotCost = match ($class) {
                \App\Models\WaterTank::class      => $component->pivot->tank_costs ?? null,
                \App\Models\WaterPipe::class      => $component->pivot->pipe_costs ?? null,
                \App\Models\WaterPump::class      => $component->pivot->pump_costs ?? null,
                \App\Models\WaterFilter::class    => $component->pivot->filter_costs ?? null,
                \App\Models\WaterConnector::class => $component->pivot->connector_costs ?? null,
                \App\Models\WaterValve::class     => $component->pivot->valve_costs ?? null,
                \App\Models\WaterTap::class       => $component->pivot->tap_costs ?? null,
                default                           => null,
            };

            return [
                'component_water_system_id' => $component->pivot->id ?? $component->id,
                'model_name' => $name,
                'type'       => $type,
                'cost'       => $pivotCost,
            ];
        })->values();
    }

  
    protected function extractInternetSystemComponents($systemId)
    {
        $system = InternetSystem::with([
            'routers',
            'ptps',
            'uisps',
            'aps',
            'aplites',
            'switches',
            'controllers',
            'connectors',
            'electricians',
            'networkCabinetInternetSystems.networkCabinet.components.component', // eager load polymorphic components
        ])->find($systemId);

        if (!$system) {
            return null; 
        }

        $componentSources = [
            ['relation' => $system->routers,     'type' => 'Router',             'pivotCostField' => 'router_costs'],
            ['relation' => $system->ptps,        'type' => 'InternetPtp',        'pivotCostField' => 'ptp_costs'],
            ['relation' => $system->uisps,       'type' => 'InternetUisp',       'pivotCostField' => 'uisp_costs'],
            ['relation' => $system->aps,         'type' => 'InternetAp',         'pivotCostField' => 'ap_costs'],
            ['relation' => $system->aplites,     'type' => 'InternetApLite',     'pivotCostField' => 'ap_lite_costs'],
            ['relation' => $system->switches,    'type' => 'Switche',            'pivotCostField' => 'switch_costs'],
            ['relation' => $system->controllers, 'type' => 'InternetController', 'pivotCostField' => 'controller_costs'],
            ['relation' => $system->connectors,  'type' => 'InternetConnector',  'pivotCostField' => 'connector_costs'],
            ['relation' => $system->electricians, 'type' => 'InternetElectrician', 'pivotCostField' => 'electrician_costs'],
        ];

        $components = collect();

        // Regular internet components
        foreach ($componentSources as $source) {
            $components = $components->merge(
                $source['relation']->map(function ($component) use ($source) {
                    return [
                        'component_internet_system_id' => $component->pivot->id ?? $component->id,
                        'model_name' => $component->model ?? 'Unnamed Component',
                        'type'       => $source['type'],
                        'cost'       => $component->pivot->{$source['pivotCostField']} ?? null,
                        'unit'       => null,
                        'cabinet_model' => null,
                        'cabinet_brand' => null,
                        'cabinet_cost'  => null,
                    ];
                })
            );
        }

        // Cabinet-based components
        foreach ($system->networkCabinetInternetSystems as $cabinetSystem) {

            $cabinet = $cabinetSystem->networkCabinet;

            //die($cabinet);
            foreach ($cabinet->components as $cabinetComponent) {
                $componentModel = $cabinetComponent->component;
                $componentName = $componentModel->model ?? 'Unnamed';
                $componentType = class_basename($cabinetComponent->component_type); // e.g., "Router"
                $fullName = "{$cabinet->model} - {$componentName} ";

                $components->push([
                    'component_internet_system_id' => $cabinetComponent->id,
                    'model_name' => $fullName,
                    'type'       => $componentType,
                    'cost'       => $cabinetComponent->cost,
                    'unit'       => $cabinetComponent->unit ?? null,
                    'cabinet_model' => $cabinet->model,
                    'cabinet_brand' => $cabinet->brand,
                    'cabinet_cost'  => $cabinetSystem->cost,
                ]);
            }
        }

        return $components->values();
    }



    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWaterSystemComponents($systemId)
    {
        $equipmentList = $this->extractWaterSystemComponents($systemId);

        if (!$equipmentList) {
            return response()->json(['message' => 'System not found'], 404);
        }

        return response()->json([

            'equipmentWater' => $equipmentList,
        ]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInternetSystemComponents($systemId)
    {
       $components = $this->extractInternetSystemComponents($systemId);

        if (!$components) {
            return response()->json(['message' => 'System not found'], 404);
        }

        return response()->json(['equipmentInternet' => $components]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyHolderSystemByCommunity($community_id, $flag)
    {
        $html = "<option disabled selected>Choose one...</option>";
        $htmlAffectedHouseholds = "<option disabled selected>Choose one...</option>";
        $systems = null;
        $userPublicEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 2)
            ->orderBy('name', 'ASC')
            ->get(); 

        $households = DB::table('all_energy_meters')
            ->join('households', 'all_energy_meters.household_id', 'households.id')
            ->where('all_energy_meters.is_archived', 0)
            ->where("all_energy_meters.community_id", $community_id)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();
 
        if($flag == "user") {

            $households = $households;
            $userPublicEquipments = $userPublicEquipments;
            
        } else if($flag == "public") {

            $households = DB::table('all_energy_meters')
                ->join('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
            $userPublicEquipments = $userPublicEquipments;
        } else if($flag == "system") {

            $systems = DB::table('energy_systems')
                ->join('communities', 'energy_systems.community_id', 'communities.id')
                ->where("energy_systems.community_id", $community_id)
                ->select('energy_systems.id as id', 'energy_systems.name')
                ->get();
        }

        if($systems) {

            foreach ($systems as $system) {

                $html .= '<option value="'.$system->id.'">'.$system->name.'</option>';
            }

            foreach ($households as $affectedHousehold) {

                $htmlAffectedHouseholds .= '<option value="'.$affectedHousehold->id.'">'.$affectedHousehold->english_name.'</option>';
            }
        } else {

            foreach ($households as $household) {

                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }    
        }

        return response()->json([
            'html' => $html, 
            'htmlAffectedHouseholds' => $htmlAffectedHouseholds,
            'userPublicEquipments' => $userPublicEquipments
        ]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWaterHolderSystemByCommunity($community_id, $flag)
    {
        $html = "<option disabled selected>Choose one...</option>";
        $htmlAffectedHouseholds = "<option disabled selected>Choose one...</option>";
        $systems = null;
        $userPublicEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 1)
            ->orderBy('name', 'ASC')
            ->get(); 
        
        $households = DB::table('all_water_holders')
            ->join('households', 'all_water_holders.household_id', 'households.id')
            ->where('all_water_holders.is_archived', 0)
            ->where("all_water_holders.community_id", $community_id)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();

        if($flag == "user") {

            $households = $households;
            $userPublicEquipments = $userPublicEquipments;
            
        } else if($flag == "public") {

            $households = DB::table('all_water_holders')
                ->join('public_structures', 'all_water_holders.public_structure_id', 'public_structures.id')
                ->where('all_water_holders.is_archived', 0)
                ->where("all_water_holders.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
            $userPublicEquipments = $userPublicEquipments;
        } else if($flag == "system") {

            $systems = DB::table('water_systems')
                ->join('communities', 'water_systems.community_id', 'communities.id')
                ->where("water_systems.community_id", $community_id)
                ->select('water_systems.id as id', 'water_systems.name')
                ->get();
        }

        if($systems) {

            foreach ($systems as $system) {

                $html .= '<option value="'.$system->id.'">'.$system->name.'</option>';
            }

            foreach ($households as $affectedHousehold) {

                $htmlAffectedHouseholds .= '<option value="'.$affectedHousehold->id.'">'.$affectedHousehold->english_name.'</option>';
            }
        } else {

            foreach ($households as $household) {

                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }    
        }

        return response()->json([
            'html' => $html, 
            'htmlAffectedHouseholds' => $htmlAffectedHouseholds,
            'userPublicEquipments' => $userPublicEquipments
        
        ]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInternetHolderSystemByCommunity($community_id, $flag)
    {
        $html = "<option disabled selected>Choose one...</option>";
        $htmlAffectedHouseholds = "<option disabled selected>Choose one...</option>";
        $htmlAffectedAreas = "<option disabled selected>Choose one...</option>";
        $systems = null;
        $userPublicEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 4)
            ->orderBy('name', 'ASC')
            ->get(); 
        
        $households = DB::table('internet_users')
            ->join('households', 'internet_users.household_id', 'households.id')
            ->where('internet_users.is_archived', 0)
            ->where("internet_users.community_id", $community_id)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();

        if($flag == "user") {

            $households = $households;
            $userPublicEquipments = $userPublicEquipments;
        } else if($flag == "public") {

            $households = DB::table('internet_users')
                ->join('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
                ->where('internet_users.is_archived', 0)
                ->where("internet_users.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
            $userPublicEquipments = $userPublicEquipments;
        } else if($flag == "system") {

            $systems = DB::table('internet_system_communities')
                ->join('communities', 'internet_system_communities.community_id', 'communities.id')
                ->join('internet_systems', 'internet_system_communities.internet_system_id', 'internet_systems.id')
                ->where("internet_system_communities.community_id", $community_id)
                ->select('internet_systems.id as id', 'internet_systems.system_name')
                ->get();

            $affectedAreas = Community::where('is_archived', 0)
                ->where('internet_service', 'yes')
                ->orderBy('english_name', 'ASC')
                ->get();
        }

        if($systems) {

            foreach ($systems as $system) {

                $html .= '<option value="'.$system->id.'">'.$system->system_name.'</option>';
            }

            foreach ($households as $affectedHousehold) {

                $htmlAffectedHouseholds .= '<option value="'.$affectedHousehold->id.'">'.$affectedHousehold->english_name.'</option>';
            }

            foreach ($affectedAreas as $affectedArea) {

                $htmlAffectedAreas .= '<option value="'.$affectedArea->id.'">'.$affectedArea->english_name.'</option>';
            }
        } else {

            foreach ($households as $household) {

                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }    
        }

        return response()->json([
            'html' => $html, 
            'htmlAffectedHouseholds' => $htmlAffectedHouseholds,
            'htmlAffectedAreas' => $htmlAffectedAreas,
            'userPublicEquipments' => $userPublicEquipments
        ]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIncidentStatusesByType($incident_id)
    {
        $html = "<option disabled selected>Choose one...</option>";
        
        $statuses = DB::table('all_incident_statuses')
            ->join('incidents', 'all_incident_statuses.incident_id', 'incidents.id')
            ->where("all_incident_statuses.incident_id", $incident_id)
            ->orderBy('all_incident_statuses.status', 'ASC')
            ->select('all_incident_statuses.id as id', 'all_incident_statuses.status')
            ->get();

        foreach ($statuses as $status) {

            $html .= '<option value="'. $status->id .'">'. $status->status .'</option>';
        }    
   
        return response()->json(['html' => $html]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteAllIncident(Request $request)
    {
        $id = $request->id;

        $allIncident = AllIncident::find($id);

        if($allIncident) {

            $allIncident->is_archived = 1;
            $allIncident->save();

            $response['success'] = 1;
            $response['msg'] = 'Incident Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteAllIncidentStatus(Request $request)
    {
        $incidentStatus = AllIncidentOccurredStatus::find($request->id);

        if($incidentStatus->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Incident Status Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteEnergyPhoto(Request $request)
    {
        $energyPhoto = AllEnergyIncidentPhoto::find($request->id);

        if($energyPhoto->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy Photo Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteWaterPhoto(Request $request)
    {
        $waterPhoto = AllWaterIncidentPhoto::find($request->id);

        if($waterPhoto->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Photo Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteInternetPhoto(Request $request)
    {
        $internetPhoto = AllInternetIncidentPhoto::find($request->id);

        if($internetPhoto->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet Photo Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteCameraPhoto(Request $request)
    {
        $cameraPhoto = AllCameraIncidentPhoto::find($request->id);

        if($cameraPhoto->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Camera Photo Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteEnergyAffectedHousehold(Request $request)
    {
        $energyHousehold = AllEnergyIncidentAffectedHousehold::find($request->id);

        if($energyHousehold->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy Affected Household Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteWaterAffectedHousehold(Request $request)
    {
        $waterHousehold = AllWaterIncidentAffectedHousehold::find($request->id);

        if($waterHousehold->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Affected Household Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteInternetAffectedHousehold(Request $request)
    {
        $internetHousehold = AllInternetIncidentAffectedHousehold::find($request->id);

        if($internetHousehold->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet Affected Household Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function deleteInternetAffectedArea(Request $request)
    {
        $internetArea = AllInternetIncidentAffectedArea::find($request->id);

        if($internetArea->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet Affected Area Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyEquipmentDamaged(Request $request)
    {
        $energyEqipment = AllEnergyIncidentDamagedEquipment::find($request->id);

        if($energyEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergySystemEquipmentDamaged(Request $request)
    {
        $energySystemEqipment = AllEnergyIncidentSystemDamagedEquipment::find($request->id);

        if($energySystemEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy System Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }


    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterEquipmentDamaged(Request $request)
    {
        $waterEqipment = AllWaterIncidentDamagedEquipment::find($request->id);

        if($waterEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterSystemEquipmentDamaged(Request $request)
    {
        $waterSystemEqipment = AllWaterIncidentSystemDamagedEquipment::find($request->id);

        if($waterSystemEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water System Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetEquipmentDamaged(Request $request)
    {
        $internetEqipment = AllInternetIncidentDamagedEquipment::find($request->id);

        if($internetEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemEquipmentDamaged(Request $request)
    {
        $internetSystemEqipment = AllInternetIncidentSystemDamagedEquipment::find($request->id);

        if($internetSystemEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCameraEquipmentDamaged(Request $request)
    {
        $cameraEqipment = AllCameraIncidentDamagedEquipment::find($request->id);

        if($cameraEqipment->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Camera Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {

        return Excel::download(new MainIncidentSheet($request), 'All Incidents.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request) 
    {
      
    }
}
