<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\AllMaintenanceTicket;
use App\Models\AllMaintenanceTicketAction;
use App\Models\User;
use App\Models\Community;
use App\Models\ServiceType;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\EnergySystem;
use App\Models\WaterSystem;
use App\Models\EnergyTurbineCommunity;
use App\Models\EnergyGeneratorCommunity;

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
            $serviceType = $this->getServiceType($ticket['department']);
            if($ticket['assigned_to'] != null) $assignedTo = $this->getAssignedUser($ticket['assigned_to']);
            else $assignedTo = null;
            $maintenanceType = $this->getMaintenanceType($ticket['channel']);
            $maintenanceStatus = $this->getMaintenanceStatus($ticket['status']);
            $community = $this->getCommunityFromTicket($ticket['comet_id']);

            // Check if maintenance ticket exists
            $existingTicket = AllMaintenanceTicket::where("comet_id", $ticket["comet_id"])
                ->where("support_created_at", $ticket["created_at"])
                ->first();

            // If ticket exists, update it, otherwise create a new one
            $maintenanceTicketID = $this->saveOrUpdateTicket($ticket, $existingTicket, $serviceType, $assignedTo, $maintenanceType, $maintenanceStatus, $community);

            // Handle ticket resolutions (actions)
            $this->handleTicketActions($ticket['resolution'], $maintenanceTicketID);
        }

        return response()->json(AllMaintenanceTicket::all());
    }

    // Get the service type based on department
    private function getServiceType($department)
    {
        if ($department == "energy") {

            return ServiceType::where('service_name', 'like', '%Electricity%')->first();
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
    private function getCommunityFromTicket($comet_id)
    {
        $household = Household::where("comet_id", $comet_id)->first();
        $publicStructure = PublicStructure::where("comet_id", $comet_id)->first();
        $energySystem = EnergySystem::where("comet_id", $comet_id)->first();
        $waterSystem = WaterSystem::where("comet_id", $comet_id)->first();
        $turbine = EnergyTurbineCommunity::where("comet_id", $comet_id)->first();
        $generator = EnergyGeneratorCommunity::where("comet_id", $comet_id)->first();

        if ($household) {

            return Community::findOrFail($household->community_id);
        } elseif ($publicStructure) {

            return Community::findOrFail($publicStructure->community_id);
        } elseif ($energySystem) {

            return Community::findOrFail($energySystem->community_id);
        } elseif ($waterSystem) {
            
            return Community::findOrFail($waterSystem->community_id);
        } elseif ($turbine) {

            return Community::findOrFail($turbine->community_id);
        } elseif ($generator) {

            return Community::findOrFail($generator->community_id);
        }

        return null; // In case no community is found
    }

    // Save or update the maintenance ticket
    private function saveOrUpdateTicket($ticket, $existingTicket, $serviceType, $assignedTo, $maintenanceType, $maintenanceStatus, $community)
    {
        if ($existingTicket) {
    
            if($serviceType != null) {

                // Update existing ticket
                $existingTicket->comet_id = $ticket["comet_id"];
                $existingTicket->meter_number = $ticket["meter_number"];
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

            if($serviceType != null) {

                // Create new ticket
                $newTicket = new AllMaintenanceTicket();
                $newTicket->comet_id = $ticket["comet_id"];
                $newTicket->meter_number = $ticket["meter_number"];
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
}
