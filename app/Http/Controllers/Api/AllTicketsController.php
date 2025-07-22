<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\AllEnergyMeter;
use App\Models\AllMaintenanceTicket;
use App\Models\AllIncident;
use App\Models\AllIncidentStatus;
use App\Models\AllIncidentOccurredStatus;
use App\Models\AllEnergyIncident;
use App\Models\AllWaterIncident;
use App\Models\AllCameraIncident;
use App\Models\InternetUser;
use App\Models\AllWaterHolder;
use App\Models\AllInternetIncident;
use App\Models\Incident;
use App\Models\AllMaintenanceTicketAction;
use App\Models\User;
use App\Models\Community;
use App\Models\ServiceType;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\Household;
use App\Models\MeterCase;
use App\Models\PublicStructure;
use App\Models\EnergySystem;
use App\Models\WaterSystem;
use App\Models\InternetSystemCommunity;
use App\Models\InternetSystem;
use App\Models\EnergyTurbineCommunity;
use App\Models\EnergyGeneratorCommunity;
use Carbon\Carbon;
use DB;

class AllTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getTickets();
    }

    public function getTickets()
    {
        // Fetch tickets from the API
        $data = Http::get('https://cometme.org/api/tickets');
        $ticketsData = json_decode($data, true);
        $tickets = $ticketsData['tickets']; 

        // Loop through each ticket
        foreach ($tickets as $ticket) {
            // Skip tickets that do not have comet_id
            if (!isset($ticket['comet_id'])) {
                continue;
            }

            // Fetch necessary data from models
            $serviceType = $this->getServiceType($ticket['department'], $ticket["is_camera"]);
            if($ticket['assigned_to'] != null) $assignedTo = $this->getAssignedUser($ticket['assigned_to']);
            else $assignedTo = null;
            $maintenanceType = $this->getMaintenanceType($ticket['channel']);
            $maintenanceStatus = $this->getMaintenanceStatus($ticket['status']);
            $community = $this->getCommunityFromTicket($ticket);

           
            // Check if maintenance ticket exists
            $existingTicket = AllMaintenanceTicket::where("comet_id_from_uss", $ticket["ticket_comet_id"])->first();

            // If ticket exists, update it, otherwise create a new one
            if($community) $maintenanceTicketID = $this->saveOrUpdateTicket($ticket, $existingTicket, $serviceType, 
                $assignedTo, $maintenanceType, $maintenanceStatus, $community);

            // Handle ticket resolutions (actions)
            $this->handleTicketActions($ticket['resolution'], $maintenanceTicketID);

            //This code is for incidents tickets
            if($ticket['is_incident'] === 1) {

                $this->saveIncidentTicket($ticket, $community);
            }
        }

        return response()->json(AllMaintenanceTicket::all());
    }

    // Get the service type based on department
    private function getServiceType($department, $isCamera)
    {
        if ($department == "energy") {

            return ServiceType::where('service_name', 'like', '%Electricity%')->first();
        } else if($isCamera === 1 && $department == "internet") {

            return ServiceType::where('service_name', 'like', 'Camera')->first();
        } else {

            return ServiceType::where('service_name', 'like', '%' . $department . '%')->first();
        }
    }

    // Get the assigned user
    private function getAssignedUser($assignedToName)
    {
        return User::where('name', 'like', '%' . $assignedToName . '%')->first();
    }

    // Get the maintenance type based on the channel
    private function getMaintenanceType($channel)
    {
        if ($channel == "site") {

            return MaintenanceType::where('type', 'like', '%visit%')->first();
        } else {

            return MaintenanceType::where('type', 'like', '%phone%')->first();
        }
    }

    // Get the maintenance status based on the status field
    private function getMaintenanceStatus($status)
    {
        if ($status == "resolved") {

            return MaintenanceStatus::where('name', 'like', '%Completed%')->first();
        } elseif ($status == "progress") {

            return MaintenanceStatus::where('name', 'like', '%In Progress%')->first();
        } else {

            return MaintenanceStatus::where('name', 'like', '%New%')->first();
        }
    }

    // Get the community based on comet_id
    private function getCommunityFromTicket($ticket)
    {
        // Shortcut for camera incidents — directly return community by Arabic name
        if ($ticket["is_camera"] === 1 && $ticket['department'] === "internet") {

            return Community::where("is_archived", 0)
                ->where("arabic_name", $ticket["community_name_arabic"])
                ->first();
        } else if($ticket["is_camera"] === 0 && $ticket['department'] === "internet") {

            return Community::where("is_archived", 0)
                ->where("arabic_name", $ticket["community_name_arabic"])
                ->first();
        }

        // Lookups by comet_id
        $modelsToCheck = [
            Household::class,
            PublicStructure::class,
            EnergySystem::class,
            WaterSystem::class,
            EnergyTurbineCommunity::class,
            EnergyGeneratorCommunity::class,
        ];

        foreach ($modelsToCheck as $model) {
            $entity = $model::where("comet_id", $ticket['comet_id'])->first();
            if ($entity && isset($entity->community_id)) {
                return Community::find($entity->community_id);
            }
        }

        // Special case: Internet System (non-camera)
        if ($ticket['department'] === "internet" && $ticket["is_camera"] === 0) {
            $internetSystem = InternetSystem::where("comet_id", $ticket['comet_id'])->first();

            if ($internetSystem) {
                $internetSystemCommunity = InternetSystemCommunity::where("is_archived", 0)
                    ->where("internet_system_id", $internetSystem->id)
                    ->first();

                if ($internetSystemCommunity) {
                    return Community::find($internetSystemCommunity->community_id);
                }
            }
        }

        return null; // Community not found
    }


    // Save or update the maintenance ticket
    private function saveOrUpdateTicket($ticket, $existingTicket, $serviceType, $assignedTo, $maintenanceType, 
        $maintenanceStatus, $community)
    {
        if ($existingTicket) {
    
            if($serviceType != null) {

                // Update existing ticket
                if($serviceType->service_name === "Camera") $existingTicket->comet_id = 0;
                else $existingTicket->comet_id = $ticket["comet_id"];

                $existingTicket->comet_id_from_uss = $ticket["ticket_comet_id"];

                if($serviceType->service_name === "Camera") $existingTicket->meter_number = null;
                else $existingTicket->meter_number = $ticket["meter_number"];

                if($ticket["duplicated_ticket"] == null) $existingTicket->is_duplicated = 0;
                else $existingTicket->is_duplicated = $ticket["duplicated_ticket"];
                $existingTicket->service_type_id = $serviceType->id;
                $existingTicket->assigned_to = $assignedTo ? $assignedTo->id : null;
                $existingTicket->maintenance_type_id = $maintenanceType->id;
                $existingTicket->maintenance_status_id = $maintenanceStatus->id;
                $existingTicket->start_date = $ticket["created_at"];
                $existingTicket->completed_date = $ticket["updated_at"];
                $existingTicket->support_created_at = $ticket["created_at"];
                $existingTicket->supported_updated_at = $ticket["updated_at"];
                $existingTicket->notes = $ticket["description"];
                $existingTicket->save();

                return $existingTicket->id;
            }
        } else {

            if($serviceType != null && $community != null) {

                // Create new ticket
                $newTicket = new AllMaintenanceTicket();

                if($serviceType->service_name === "Camera") $newTicket->comet_id = 0;
                else $newTicket->comet_id = $ticket["comet_id"];

                $newTicket->comet_id_from_uss = $ticket["ticket_comet_id"];

                if($serviceType->service_name === "Camera") $newTicket->meter_number = null;
                else $newTicket->meter_number = $ticket["meter_number"];

                if($ticket["duplicated_ticket"] == null) $newTicket->is_duplicated = 0;
                else $newTicket->is_duplicated = $ticket["duplicated_ticket"];

                $newTicket->community_id = $community->id;
                $newTicket->service_type_id = $serviceType->id;
                $newTicket->assigned_to = $assignedTo ? $assignedTo->id : null;
                $newTicket->maintenance_type_id = $maintenanceType->id;
                $newTicket->maintenance_status_id = $maintenanceStatus->id;
                $newTicket->start_date = $ticket["created_at"];
                $newTicket->completed_date = $ticket["updated_at"];
                $newTicket->support_created_at = $ticket["created_at"];
                $newTicket->supported_updated_at = $ticket["updated_at"];
                $newTicket->notes = $ticket["description"];
                $newTicket->save();
    
                return $newTicket->id;
            }
        }
    }

    // Handle the resolution actions for the ticket
    private function handleTicketActions($resolutionActions, $maintenanceTicketID)
    {
        if ($resolutionActions) {
            // Ensure actions are unique
            $uniqueActions = array_unique($resolutionActions);

            foreach ($uniqueActions as $actionId) {
                // Check if action already exists
                $existingAction = AllMaintenanceTicketAction::where("is_archived", 0)
                    ->where("all_maintenance_ticket_id", $maintenanceTicketID)
                    ->where("action_id", $actionId)
                    ->first();

                // If the action doesn't exist, create and save it
                if (!$existingAction) {
                    $newAction = new AllMaintenanceTicketAction();
                    $newAction->all_maintenance_ticket_id = $maintenanceTicketID;
                    $newAction->action_id = $actionId;
                    $newAction->save();
                } else {
                    \Log::info('Action already exists for ticket ' . $maintenanceTicketID . ' - Action ID: ' . $actionId);
                }
            }
        }
    }
 

    private function handleEnergyIncident($incidentId, $comet_id)
    {
        $meterCase = MeterCase::where('meter_case_name_english', 'Incident')->first();

        $household = Household::where("comet_id", $comet_id)->first();
        $publicStructure = PublicStructure::where("comet_id", $comet_id)->first();
        $energySystem = EnergySystem::where("comet_id", $comet_id)->first();

        if ($household) {

            $this->createEnergyIncident($incidentId, 'household_id', $household->id, $meterCase);
        } elseif ($publicStructure) {

            $this->createEnergyIncident($incidentId, 'public_structure_id', $publicStructure->id, $meterCase);
        } elseif ($energySystem) {

            $incident = new AllEnergyIncident();
            $incident->all_incident_id = $incidentId;
            $incident->energy_system_id = $energySystem->id;
            $incident->save();
        }
    }

    private function createEnergyIncident($incidentId, $column, $id, $meterCase)
    {
        $incident = new AllEnergyIncident();
        $incident->all_incident_id = $incidentId;

        $meter = AllEnergyMeter::where("is_archived", 0)
            ->where($column, $id)
            ->first();

        if ($meter) {

            $incident->all_energy_meter_id = $meter->id;
            $meter->meter_case_id = $meterCase->id;
            $meter->save();
        }

        $incident->save();
    }

    private function handleWaterIncident($incidentId, $comet_id)
    {
        $household = Household::where("comet_id", $comet_id)->first();
        $publicStructure = PublicStructure::where("comet_id", $comet_id)->first();
        $waterSystem = WaterSystem::where("comet_id", $comet_id)->first();

        $incident = new AllWaterIncident();
        $incident->all_incident_id = $incidentId;

        if ($household) {

            $holder = AllWaterHolder::where("is_archived", 0)
                ->where("household_id", $household->id)
                ->first();
            if ($holder) $incident->all_water_holder_id = $holder->id;
        } elseif ($publicStructure) {

            $holder = AllWaterHolder::where("is_archived", 0)
                ->where("public_structure_id", $publicStructure->id)
                ->first();
            if ($holder) $incident->all_water_holder_id = $holder->id;
        } elseif ($waterSystem) {

            $incident->water_system_id = $waterSystem->id;
        }

        $incident->save();
    }


    private function handleInternetIncident($incidentId, $comet_id)
    {
        $household = Household::where("comet_id", $comet_id)->first();
        $publicStructure = PublicStructure::where("comet_id", $comet_id)->first();
        $internetSystem = InternetSystem::where("comet_id", $comet_id)->first();

        $incident = new AllInternetIncident();
        $incident->all_incident_id = $incidentId;

        if ($household) {
            $user = InternetUser::where("is_archived", 0)
                ->where("household_id", $household->id)
                ->first();
            if ($user) $incident->internet_user_id = $user->id;
        } elseif ($publicStructure) {
            $user = InternetUser::where("is_archived", 0)
                ->where("public_structure_id", $publicStructure->id)
                ->first();
            if ($user) $incident->internet_user_id = $user->id;
        } elseif ($internetSystem) {
            $community = InternetSystemCommunity::where("is_archived", 0)
                ->where("internet_system_id", $internetSystem->id)
                ->first();
            if ($community) $incident->community_id = $community->id;
        }

        $incident->save();
    }


    private function handleCameraIncident($incidentId, $communityId)
    {
        $incident = new AllCameraIncident();
        $incident->all_incident_id = $incidentId;
        $incident->community_id = $communityId;
        $incident->save();
    }

    private function dispatchIncidentHandler($incidentId, $comet_id, $department, $cameraFlag, $communityId)
    {
        $key = ($department === 'internet' && $cameraFlag === 1) ? 'camera' : $department;

        $handlers = [
            'energy'   => fn() => $this->handleEnergyIncident($incidentId, $comet_id),
            'water'    => fn() => $this->handleWaterIncident($incidentId, $comet_id),
            'internet' => fn() => $this->handleInternetIncident($incidentId, $comet_id),
            'camera'   => fn() => $this->handleCameraIncident($incidentId, $communityId),
        ];

        if (isset($handlers[$key])) {
            $handlers[$key]();
        } else {
            \Log::warning("No handler found for incident. Department: $department, CameraFlag: $cameraFlag");
        }
    }

    // This function for getting the holder depends on both service & comet_id
    private function getHolderForIncident($incidentId, $comet_id, $department, $cameraFlag, $communityId)
    {
        $this->dispatchIncidentHandler($incidentId, $comet_id, $department, $cameraFlag, $communityId);
    }

    // Save the incidents tickets 
    private function saveIncidentTicket($ticket, $community) {
        
        // This is for the Base details for AllIncident table
        $existingIncidentTicket = AllIncident::where("comet_id", $ticket["ticket_comet_id"])->first();
        $incidentTypeID = null;

        if(!$existingIncidentTicket) {

            $newIncident = new AllIncident();
            $newIncident->comet_id = $ticket["ticket_comet_id"];
            $newIncident->description = $ticket["description"];

            $newIncident->community_id = $community->id;

            if($ticket['department'] === "energy") $newIncident->service_type_id = 1;
        
            else if($ticket['department'] === "water") $newIncident->service_type_id = 2;
    
            else if($ticket['department'] === "internet" && $ticket['is_camera'] === 0) $newIncident->service_type_id = 3;
    
            else if($ticket['department'] === "internet" && $ticket['is_camera'] === 1) $newIncident->service_type_id = 4;

            if(!empty($ticket['incident'])) {

                foreach ($ticket['incident'] as $incident) {

                    $incidentType = Incident::where('arabic_name', 'like', $incident['incident_type'])->first();
                    $incidentTime = Carbon::parse($incident['incident_time'])->toDateString();
                    $newIncident->date = $incidentTime;
                    $year = explode('-', $incidentTime);
                    $newIncident->year = $year[0];
                    if($incidentType) {

                        $newIncident->incident_id = $incidentType->id;
                        $incidentTypeID = $incidentType->id;
                    }
                }
            }

            $newIncident->save();

            $status = AllIncidentStatus::where('status', "New")
                ->where("incident_id", $incidentTypeID)
                ->first();
    
            if($status) {

                $incidentStatus = new AllIncidentOccurredStatus();
                $incidentStatus->all_incident_status_id = $status->id;
                $incidentStatus->all_incident_id = $newIncident->id;
                $incidentStatus->save();
            }

            // Now we should filter the agent on Enrgy, water, internet, camera
            $this->getHolderForIncident($newIncident->id, $ticket["comet_id"], $ticket['department'], 
                $ticket['is_camera'], $community->id);
        } else {

            $existingIncidentTicket->description = $ticket["description"];
            $existingIncidentTicket->save();
        }
    }
}
