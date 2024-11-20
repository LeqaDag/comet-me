<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class IncidentSummary implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles
{
    protected $request; 

    function __construct($request) {

        $this->request = $request;
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function collection()  
    { 
        $energySystemIncidents = DB::table('mg_incidents')
            ->join('communities', 'mg_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('energy_systems', 'mg_incidents.energy_system_id', 'energy_systems.id')
            ->join('incidents', 'mg_incidents.incident_id', 'incidents.id')
            ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                'incident_status_mg_systems.id')
            ->leftJoin('mg_incident_equipment', 'mg_incident_equipment.mg_incident_id', 
                'mg_incidents.id')
            ->leftJoin('incident_equipment', 'mg_incident_equipment.incident_equipment_id', 
                'incident_equipment.id') 
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('energy_systems.energy_system_type_id', 1)
            ->where('community_donors.service_id', 1)
            ->where('mg_incidents.is_archived', 0)
            ->select(
                'mg_incidents.date',
                'communities.english_name as community_name',
                'mg_incidents.notes',
                DB::raw("'MG System' as type"), 
                'incidents.english_name as incident',
                'energy_systems.name as exported_value', 
                'regions.english_name as region', 
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'mg_incidents.monetary_losses',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                'incident_status_mg_systems.name as incident_status', 
                'mg_incidents.response_date',
                'mg_incidents.year', 
                'mg_incidents.geolocation_lat', 'mg_incidents.geolocation_long'
            )
            ->groupBy('mg_incidents.id')
            ->orderBy('mg_incidents.date', 'desc')
            ->get(); 

        $energyHolderIncidents = DB::table('fbs_user_incidents')
            ->join('communities', 'fbs_user_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('all_energy_meters', 'fbs_user_incidents.energy_user_id', 'all_energy_meters.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
            ->join('incidents', 'fbs_user_incidents.incident_id', 'incidents.id')
            ->leftJoin('fbs_incident_statuses', 'fbs_user_incidents.id', 'fbs_incident_statuses.fbs_user_incident_id')
            ->leftJoin('incident_status_small_infrastructures', 'fbs_incident_statuses.incident_status_small_infrastructure_id', 'incident_status_small_infrastructures.id')
            ->leftJoin('fbs_incident_equipment', 'fbs_incident_equipment.fbs_user_incident_id', 'fbs_user_incidents.id')
            ->leftJoin('incident_equipment', 'fbs_incident_equipment.incident_equipment_id', 'incident_equipment.id') 
            ->leftJoin('all_energy_meter_donors', 'all_energy_meter_donors.all_energy_meter_id', 'all_energy_meters.id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id', 'donors.id')
            ->where('fbs_user_incidents.is_archived', 0)
            ->where('fbs_incident_statuses.is_archived', 0)
            ->where('fbs_incident_equipment.is_archived', 0)
            ->select(
                'fbs_user_incidents.date', 
                'communities.english_name as community_name', 
                'fbs_user_incidents.notes', 
                DB::raw("'Energy Holder' as type"), 
                'incidents.english_name as incident', 
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
                'regions.english_name as region', 
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                DB::raw('SUM(fbs_user_incidents.losses_energy + fbs_user_incidents.losses_water) as monetary_losses'),
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                DB::raw('group_concat(DISTINCT incident_status_small_infrastructures.name) as incident_status'),
                'fbs_user_incidents.response_date', 
                'fbs_user_incidents.year', 
                'fbs_user_incidents.geolocation_lat', 'fbs_user_incidents.geolocation_long'
            )
            ->groupBy('fbs_user_incidents.id')
            ->orderBy('fbs_user_incidents.date', 'desc')
            ->get();
    
        $waterIncidents = DB::table('h2o_system_incidents')
            ->join('communities', 'h2o_system_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('water_systems', 'h2o_system_incidents.water_system_id', 'water_systems.id')
            ->leftJoin('all_water_holders', 'h2o_system_incidents.all_water_holder_id', 
                'all_water_holders.id')
            ->leftJoin('households', 'all_water_holders.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_water_holders.public_structure_id', 
                'public_structures.id')
            ->join('incidents', 'h2o_system_incidents.incident_id', 'incidents.id')
            ->leftJoin('h2o_incident_statuses', 'h2o_system_incidents.id', 
                'h2o_incident_statuses.h2o_system_incident_id')
            ->leftJoin('incident_statuses', 
                'h2o_incident_statuses.incident_status_id', 
                'incident_statuses.id')
            ->leftJoin('water_incident_equipment', 'water_incident_equipment.h2o_system_incident_id', 
                'h2o_system_incidents.id')
            ->leftJoin('incident_equipment', 'water_incident_equipment.incident_equipment_id', 
                'incident_equipment.id')
            ->LeftJoin('all_water_holder_donors', 'all_water_holders.id', 
                'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors', 'all_water_holder_donors.donor_id', 'donors.id')
            ->where('h2o_system_incidents.is_archived', 0) 
            ->where('h2o_incident_statuses.is_archived', 0) 
            ->where('water_incident_equipment.is_archived', 0) 
            ->select(
                'h2o_system_incidents.date', 
                'communities.english_name as community_name', 
                'h2o_system_incidents.notes', 
                DB::raw('IF(water_systems.name IS NOT NULL, "Water System", "Water Holder") as type'),
                'incidents.english_name as incident', 
                DB::raw('COALESCE(households.english_name, public_structures.english_name, water_systems.name) as exported_value'),
                'regions.english_name as region', 
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'h2o_system_incidents.monetary_losses',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                DB::raw('group_concat(DISTINCT incident_statuses.name) as incident_status'),
                'h2o_system_incidents.response_date', 
                'h2o_system_incidents.year', 
                'h2o_system_incidents.geolocation_lat', 'h2o_system_incidents.geolocation_long',
            )
            ->groupBy('h2o_system_incidents.id')
            ->orderBy('h2o_system_incidents.date', 'desc')
            ->get(); 

        $internetSystemIncidents = DB::table('internet_network_incidents')
            ->join('communities', 'internet_network_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('incidents', 'internet_network_incidents.incident_id', 'incidents.id')
            ->leftJoin('internet_incident_statuses', 
                'internet_network_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->leftJoin('internet_network_incident_equipment', 'internet_network_incidents.id',
                'internet_network_incident_equipment.internet_network_incident_id')
            ->leftJoin('incident_equipment', 'incident_equipment.id',
                'internet_network_incident_equipment.incident_equipment_id') 
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->leftJoin('internet_system_communities', 'internet_system_communities.community_id', 'communities.id')
            ->leftJoin('internet_systems', 'internet_systems.id', 'internet_system_communities.internet_system_id')
            ->where('internet_network_incidents.is_archived', 0)
            ->where('community_donors.service_id', 3)
            ->select(
                'internet_network_incidents.date', 
                'communities.english_name as community_name', 
                'internet_network_incidents.notes',
                DB::raw('"Internet System" as type'),
                'incidents.english_name as incident', 
                DB::raw('internet_systems.system_name as exported_value'),
                'regions.english_name as region', 
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'internet_network_incidents.monetary_losses',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                'internet_incident_statuses.name as incident_status',
                'internet_network_incidents.response_date',
                'internet_network_incidents.year', 
                'internet_network_incidents.geolocation_lat', 'internet_network_incidents.geolocation_long'
            )
            ->groupBy('internet_network_incidents.id')
            ->orderBy('internet_network_incidents.date', 'desc')
            ->get(); 

        $internetUserIncidents = DB::table('internet_user_incidents')
            ->join('communities', 'internet_user_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('internet_users', 'internet_user_incidents.internet_user_id', 
                'internet_users.id')
            ->leftJoin('households', 'internet_users.household_id', 'households.id')
            ->leftJoin('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
            ->join('incidents', 'internet_user_incidents.incident_id', 'incidents.id')
            ->join('internet_incident_statuses', 
                'internet_user_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->leftJoin('internet_user_incident_equipment', 'internet_user_incident_equipment.internet_user_incident_id', 
                'internet_user_incidents.id')
            ->leftJoin('incident_equipment', 'internet_user_incident_equipment.incident_equipment_id', 
                'incident_equipment.id') 
            ->leftJoin('internet_user_donors', 'internet_user_donors.internet_user_id',
                'internet_users.id')
            ->leftJoin('donors', 'internet_user_donors.donor_id', 'donors.id')
            ->where('internet_user_incidents.is_archived', 0)
            ->where('internet_user_incident_equipment.is_archived', 0)
            ->select(
                'internet_user_incidents.date', 
                'communities.english_name as community_name', 
                'internet_user_incidents.notes',
                DB::raw('"Internet User" as type'),
                'incidents.english_name as incident', 
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
                'regions.english_name as region', 
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'internet_user_incidents.monetary_losses',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                'internet_incident_statuses.name as incident_status',
                'internet_user_incidents.response_date',
                'internet_user_incidents.year', 
                'internet_user_incidents.geolocation_lat', 'internet_user_incidents.geolocation_long'
            )
            ->groupBy('internet_user_incidents.id')
            ->get();

        $cameraIncidents = DB::table('camera_incidents')
            ->leftJoin('communities', 'camera_incidents.community_id', 'communities.id')
            ->leftJoin('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('repositories', 'camera_incidents.repository_id', 'repositories.id')
            ->leftJoin('camera_communities as camera_community', 'communities.id', 'camera_community.community_id')
            ->leftJoin('camera_communities as camera_repository', 'repositories.id', 'camera_repository.repository_id')
            ->join('incidents', 'camera_incidents.incident_id', 'incidents.id')
            ->join('internet_incident_statuses', 'camera_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->leftJoin('camera_incident_equipment', 'camera_incident_equipment.camera_incident_id', 
                'camera_incidents.id')
            ->leftJoin('incident_equipment', 'camera_incident_equipment.incident_equipment_id', 
                'incident_equipment.id') 
            ->leftJoin('camera_community_donors', 'camera_community_donors.camera_community_id',
                'camera_community.id')
            ->leftJoin('donors', 'camera_community_donors.donor_id', 'donors.id')
            ->where('camera_incidents.is_archived', 0)
            ->select(
                'camera_incidents.date', 
                'communities.english_name as community_name',
                'camera_incidents.notes',
                DB::raw('"Camera" as type'),
                'incidents.english_name as incident',
                DB::raw('IFNULL(communities.english_name, repositories.name) as exported_value'),
                'regions.english_name as region', 
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'camera_incidents.monetary_losses',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                'internet_incident_statuses.name as incident_status',
                'camera_incidents.response_date',
                'camera_incidents.year',  
                'camera_incidents.geolocation_lat', 'camera_incidents.geolocation_long'
            )
            ->groupBy('camera_incidents.id')
            ->orderBy('camera_incidents.date', 'desc')
            ->get(); 

        return $energySystemIncidents->merge($energyHolderIncidents)
            ->merge($waterIncidents)->merge($internetSystemIncidents)
            ->merge($internetUserIncidents)->merge($cameraIncidents);
    } 
    
   /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Incident Date", "Community", "Notes (---> Description)", "Type of system/service",
            "Incident type", "Holder name", "Region", "Equipment Damaged", 
            "Monetary losses (ILS)", "Donors", "Status", "Response date", "Incident Year", 
            "Geolocation Lat", "Geolocation Long"];
    }

    public function title(): string 
    {
        return 'Incidents Summary';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:P1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

}