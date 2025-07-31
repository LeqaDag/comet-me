<?php

namespace App\Exports\Incidents;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\AllIncident;
use DB;

class Test implements FromCollection, WithHeadings, ShouldAutoSize
{

    protected $request;

    function __construct($request) {

        $this->request = $request; 
    }

    public function collection()
    {
        // Run base query to get flattened report rows
        $incidents = $this->buildQuery()->get();

        // Enrich with detailed equipment lists
        return $incidents->map(function ($row) {

            /** @var AllIncident $full */
            $full = AllIncident::with([

                'energyIncident.equipmentDamaged.incidentEquipment',
                'energyIncident.damagedSystemEquipments',
                'waterIncident.equipmentDamaged.incidentEquipment',
                'waterIncident.damagedSystemEquipments',
                'internetIncident.equipmentDamaged.incidentEquipment',
                'internetIncident.damagedSystemEquipments'
            ])->find($row->id);

            $list = fn($relation) => collect()
                ->when(isset($relation), fn($c) => $c
                    ->merge($relation->equipmentDamaged->map(
                        fn($eq) => "{$eq->incidentEquipment?->name} ({$eq->count})"
                    ))
                    ->merge($relation->damagedSystemEquipments->map(
                        fn($sys) => "{$sys->getModelName()} ({$sys->count})"
                    ))
                );

            $energy = $full->energyIncident ? $list($full->energyIncident)->implode(', ') : '';
            $water   = $full->waterIncident   ? $list($full->waterIncident)->implode(', ')   : '';
            $internet = $full->internetIncident ? $list($full->internetIncident)->implode(', ') : '';

            return [
                'Date'              => $row->date,
                'User'              => $row->user,
                'System'            => $row->system,
                'Community'         => $row->community_name,
                'Region'            => $row->region,
                'Incident'          => $row->incident,
                'Department'        => $row->department,
                'Energy Equipment'  => $energy,
                'Water Equipment'   => $water,
                'Internet Equipment'=> $internet,
                'Notes'             => $row->notes,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date', 'User', 'System', 'Community', 'Region',
            'Incident', 'Department',
            'Energy Equipment', 'Water Equipment', 'Internet Equipment',
            'Notes'
        ];
    }

    private function buildQuery()
    {
        return DB::table('all_incidents')
            ->join('communities', 'all_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('service_types', 'all_incidents.service_type_id', 'service_types.id')
            ->join('incidents', 'all_incidents.incident_id', 'incidents.id')
            ->join('all_incident_occurred_statuses', 'all_incidents.id', 'all_incident_occurred_statuses.all_incident_id')
            ->join('all_incident_statuses','all_incident_statuses.id', 'all_incident_occurred_statuses.all_incident_status_id')
            ->where('all_incidents.is_archived', 0)
            ->where('all_incidents.incident_id', '!=', 4)
            ->where('all_incidents.date', "2025-07-13")

            ->leftJoin('all_energy_incidents', 'all_incidents.id','all_energy_incidents.all_incident_id')
            ->leftJoin('all_energy_meters','all_energy_meters.id','all_energy_incidents.all_energy_meter_id')
            ->leftJoin('households as energy_users','all_energy_meters.household_id','energy_users.id')
            ->leftJoin('public_structures as energy_publics','all_energy_meters.public_structure_id','energy_publics.id')
            ->leftJoin('energy_systems','energy_systems.id','all_energy_incidents.energy_system_id')
            ->leftJoin('all_energy_incident_damaged_equipment','all_energy_incidents.id','all_energy_incident_damaged_equipment.all_energy_incident_id')
            ->leftJoin('incident_equipment as energy_equipment','energy_equipment.id','all_energy_incident_damaged_equipment.incident_equipment_id')
            // water joins...
            ->leftJoin('all_water_incidents','all_incidents.id','all_water_incidents.all_incident_id')
            ->leftJoin('all_water_holders','all_water_holders.id','all_water_incidents.all_water_holder_id')
            ->leftJoin('households as water_users','all_water_holders.household_id','water_users.id')
            ->leftJoin('public_structures as water_publics','all_water_holders.public_structure_id','water_publics.id')
            ->leftJoin('water_systems','water_systems.id','all_water_incidents.water_system_id')
            ->leftJoin('all_water_incident_damaged_equipment','all_water_incidents.id','all_water_incident_damaged_equipment.all_water_incident_id')
            ->leftJoin('incident_equipment as water_equipment','water_equipment.id','all_water_incident_damaged_equipment.incident_equipment_id')
            // internet joins...
            ->leftJoin('all_internet_incidents','all_incidents.id','all_internet_incidents.all_incident_id')
            ->leftJoin('internet_users','internet_users.id','all_internet_incidents.internet_user_id')
            ->leftJoin('households as internet_holders','internet_holders.id','internet_users.household_id')
            ->leftJoin('public_structures as internet_publics','internet_publics.id','internet_users.public_structure_id')
            ->leftJoin('internet_system_communities','internet_system_communities.community_id','all_internet_incidents.community_id')
            ->leftJoin('internet_systems','internet_systems.id','internet_system_communities.internet_system_id')
            ->leftJoin('all_internet_incident_damaged_equipment', 'all_internet_incidents.id', 
                'all_internet_incident_damaged_equipment.all_internet_incident_id')
            ->leftJoin('incident_equipment as internet_equipment', 'all_internet_incident_damaged_equipment.incident_equipment_id', 
                'internet_equipment.id')
            // camera joins omitted for brevity...
            ->select([
                'all_incidents.id',
                'all_incidents.date',
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(energy_users.english_name, energy_publics.english_name, water_users.english_name, water_publics.english_name, internet_holders.english_name , internet_publics.english_name) SEPARATOR ', ') as user"),
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(energy_systems.name, water_systems.name, internet_systems.system_name, communities.english_name) SEPARATOR ', ') as system"),
                DB::raw("GROUP_CONCAT(DISTINCT communities.english_name SEPARATOR ', ') as community_name"),
                'regions.english_name as region',
                'incidents.english_name as incident',
                DB::raw("GROUP_CONCAT(DISTINCT service_types.service_name SEPARATOR ', ') as department"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 1 THEN all_incident_statuses.status END SEPARATOR ', ') as energy_status"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 2 THEN all_incident_statuses.status END SEPARATOR ', ') as water_status"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 3 THEN all_incident_statuses.status END SEPARATOR ', ') as internet_status"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 4 THEN all_incident_statuses.status END SEPARATOR ', ') as camera_status"),
                'all_incidents.notes'
            ])
            ->groupBy('all_incidents.id')
            ->orderBy('all_incidents.date', 'desc');
    }
}