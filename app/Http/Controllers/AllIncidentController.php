<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\AllIncident;
use App\Models\AllIncidentOccurredStatus;
use App\Models\AllIncidentStatus;
use App\Models\AllInternetIncident;
use App\Models\AllInternetIncidentPhoto;
use App\Models\AllInternetIncidentAffectedArea;
use App\Models\AllInternetIncidentAffectedHousehold;
use App\Models\AllInternetIncidentDamagedEquipment;
use App\Models\AllWaterIncident;
use App\Models\AllWaterIncidentPhoto;
use App\Models\AllWaterIncidentDamagedEquipment;
use App\Models\AllWaterIncidentAffectedHousehold;
use App\Models\User;
use App\Models\Community;
use App\Models\Donor;
use App\Models\DisplacedHousehold;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\Region;
use App\Models\Incident;
use App\Models\IncidentEquipment;
use App\Models\InternetUser;
use App\Models\WaterSystem;

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

        return view('incidents.all.create', compact('communities', 'energySystems', 'incidents', 
            'energyEquipments', 'waterEquipments', 'internetEquipments', 'cameraEquipments',
            'households', 'waterUsers', 'incidentStatuses', 'serviceTypes'));
    }

    // This function is to store the common fields in AllIncident Model
    private function createBaseIncident(Request $request, int $serviceTypeId, string $notesField, string $prefix): int
    {
        $allIncident = new AllIncident();
        $allIncident->service_type_id = $serviceTypeId;
        $allIncident->community_id = $request->community_id;
        $allIncident->incident_id = $request->incident_id;
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
        if($request->service_type_ids) {

            foreach ($request->service_type_ids as $serviceTypeId) {
 
                if($serviceTypeId == 1) {

                    $allIncidentId = $this->createBaseIncident($request, 1, 'energy_notes', 'energy');

                    $this->attachIncidentStatuses($request->energy_incident_status_ids ?? [], $allIncidentId);

                    // Energy Incidents
                    $newAllEnergyIncident = new AllEnergyIncident();
                    $newAllEnergyIncident->all_incident_id = $allIncidentId;

                    if($request->energy_system_holder == "user") {

                        $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                            ->where("household_id", $request->energy_holder_system)
                            ->first();

                        if($allEnergyMeter) $newAllEnergyIncident->all_energy_meter_id = $allEnergyMeter->id;
                    } else if ($request->energy_system_holder == "public") {

                        $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                            ->where("public_structure_id", $request->energy_holder_system)
                            ->first();

                        if($allEnergyMeter) $newAllEnergyIncident->all_energy_meter_id = $allEnergyMeter->id;
                    } else if ($request->energy_system_holder == "system") {

                        $energySystem = EnergySystem::findOrFail($request->energy_holder_system);

                        if($energySystem) $newAllEnergyIncident->energy_system_id = $energySystem->id;
                    }
                    
                    $newAllEnergyIncident->save();

                    if ($request->energy_equipment) {

                        for ($eq = 0; $eq  < count($request->energy_equipment); $eq++) {

                            $energyEquipment = new AllEnergyIncidentDamagedEquipment();
                            $energyEquipment->incident_equipment_id = $request->energy_equipment[$eq];
                            $energyEquipment->all_energy_incident_id = $newAllEnergyIncident->id;
                    
                            $energyEquipment->count = $request->input("addMoreInputFieldsEnergyUnit.$eq.subject");
                            $energyEquipment->cost = $request->input("addMoreInputFieldsEnergyCost.$eq.subject");
                    
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

                    if ($request->water_equipment) {

                        for ($wq = 0; $wq < count($request->water_equipment); $wq++) {

                            $waterEquipment = new AllWaterIncidentDamagedEquipment();
                            $waterEquipment->incident_equipment_id = $request->water_equipment[$wq];
                            $waterEquipment->all_water_incident_id = $newAllWaterIncident->id;
                    
                            $waterEquipment->count = $request->input("addMoreInputFieldsWaterUnit.$wq.subject");
                            $waterEquipment->cost = $request->input("addMoreInputFieldsWaterCost.$wq.subject");
                    
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

                    if ($request->internet_equipment) {

                        for ($inq = 0; $inq < count($request->internet_equipment); $inq++) {

                            $internetEquipment = new AllInternetIncidentDamagedEquipment();
                            $internetEquipment->incident_equipment_id = $request->internet_equipment[$inq];
                            $internetEquipment->all_internet_incident_id = $newAllInternetIncident->id;
                    
                            $internetEquipment->count = $request->input("addMoreInputFieldsInternetUnit.$inq.subject");
                            $internetEquipment->cost = $request->input("addMoreInputFieldsInternetCost.$inq.subject");
                    
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

        if($allIncident->service_type_id === 1) $allEnergyIncident = AllEnergyIncident::where("all_incident_id", $id)->first();
       
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
        $energyUser = AllEnergyMeter::findOrFail($id);

        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)
            ->where("is_archived", 0)
            ->get();

        $community_id = Community::findOrFail($energyUser->community_id);
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $communityVendors = DB::table('community_vendors')
            //->where('community_id', $community_id->id)
            ->where('community_vendors.is_archived', 0)
            ->join('vendor_user_names', 'community_vendors.vendor_username_id', 
                '=', 'vendor_user_names.id')
            ->select('vendor_user_names.name', 'community_vendors.id as id',
                'vendor_user_names.id as vendor_username_id')
            ->groupBy('vendor_user_names.id')
            ->get();
        
        $energySystems = EnergySystem::where('is_archived', 0)->get();
        $household = Household::findOrFail($energyUser->household_id);
        $meterCases = MeterCase::where('is_archived', 0)->get();
        $vendor = VendorUserName::where('id', $energyUser->vendor_username_id)->first();
        $donors = Donor::where('is_archived', 0)->get();

        $energyDonorsId = AllEnergyMeterDonor::where("all_energy_meter_id", $id)
            ->where("is_archived", 0)
            ->pluck('donor_id'); 

        $moreDonors = Donor::where('is_archived', 0)
            ->whereNotIn('id', $energyDonorsId) 
            ->get();

        $installationTypes = InstallationType::where('is_archived', 0)->get();
        $energyCycles = EnergySystemCycle::get();

        $electricityCollectionBoxes = ElectricityCollectionBox::where('is_archived', 0)->get();
        $electricityPhases = ElectricityPhase::where('is_archived', 0)->get();
        $allEnergyMeterPhase = AllEnergyMeterPhase::where('is_archived', 0)
            ->where('all_energy_meter_id', $id)
            ->first();

        return view('users.energy.not_active.edit_energy', compact('household', 'communities',
            'meterCases', 'energyUser', 'communityVendors', 'vendor', 'energySystems', 'electricityPhases',
            'energyDonors', 'donors', 'installationTypes', 'energyCycles', 'electricityCollectionBoxes',
            'allEnergyMeterPhase', 'moreDonors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        

        return redirect('/all-incident')->with('message', 'Incident Record Updated Successfully!');
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
        
        $households = DB::table('all_energy_meters')
            ->join('households', 'all_energy_meters.household_id', 'households.id')
            ->where('all_energy_meters.is_archived', 0)
            ->where("all_energy_meters.community_id", $community_id)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();

        if($flag == "user") {

            $households = $households;
            
        } else if($flag == "public") {

            $households = DB::table('all_energy_meters')
                ->join('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
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

        return response()->json(['html' => $html, 'htmlAffectedHouseholds' => $htmlAffectedHouseholds]);
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
        
        $households = DB::table('all_water_holders')
            ->join('households', 'all_water_holders.household_id', 'households.id')
            ->where('all_water_holders.is_archived', 0)
            ->where("all_water_holders.community_id", $community_id)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();

        if($flag == "user") {

            $households = $households;
            
        } else if($flag == "public") {

            $households = DB::table('all_water_holders')
                ->join('public_structures', 'all_water_holders.public_structure_id', 'public_structures.id')
                ->where('all_water_holders.is_archived', 0)
                ->where("all_water_holders.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
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

        return response()->json(['html' => $html, 'htmlAffectedHouseholds' => $htmlAffectedHouseholds]);
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
        
        $households = DB::table('internet_users')
            ->join('households', 'internet_users.household_id', 'households.id')
            ->where('internet_users.is_archived', 0)
            ->where("internet_users.community_id", $community_id)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();

        if($flag == "user") {

            $households = $households;
            
        } else if($flag == "public") {

            $households = DB::table('internet_users')
                ->join('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
                ->where('internet_users.is_archived', 0)
                ->where("internet_users.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
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
            'htmlAffectedAreas' => $htmlAffectedAreas
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
