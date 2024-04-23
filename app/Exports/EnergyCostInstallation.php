<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use DB;

class EnergyCostInstallation implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
{
    private $misc = 0;
    private $miscCost = 0;

    protected $request; 

    function __construct($request) {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()  
    { 
        $totalFbsComponent = 0; 

        $inverters = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                'energy_inverters.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_inverters.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_inverters.cost / energy_system_inverters.inverter_units) as cost_per_unit')
            )
            ->groupBy('energy_inverters.inverter_model')
            ->first(); 

        $controllers = DB::table('energy_system_charge_controllers')
            ->join('energy_systems', 'energy_system_charge_controllers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 
                'energy_charge_controllers.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_charge_controllers.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_charge_controllers.cost / energy_system_charge_controllers.controller_units) as cost_per_unit')
            )
            ->groupBy('energy_charge_controllers.charge_controller_model')
            ->first(); 

        $relayDrivers = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                'energy_relay_drivers.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_relay_drivers.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_relay_drivers.cost / energy_system_relay_drivers.relay_driver_units) * 2 as cost_per_unit')
            )
            ->groupBy('energy_relay_drivers.model')
            ->first(); 

        $bts = DB::table('energy_system_battery_temperature_sensors')
            ->join('energy_systems', 'energy_system_battery_temperature_sensors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_temperature_sensors', 'energy_battery_temperature_sensors.id',
                'energy_system_battery_temperature_sensors.energy_battery_temperature_sensor_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_battery_temperature_sensors.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_battery_temperature_sensors.cost / energy_system_battery_temperature_sensors.bts_units) as cost_per_unit'),
            )
            ->groupBy('energy_battery_temperature_sensors.BTS_model')
            ->first();

        $pvs = DB::table('energy_systems')
            ->join('energy_system_pvs', 'energy_systems.id', 'energy_system_pvs.energy_system_id')
            ->leftJoin('energy_pvs', 'energy_pvs.id', 'energy_system_pvs.pv_type_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_pvs.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_pvs.cost / energy_system_pvs.pv_units) * 2 as cost_per_unit')
            )
            ->groupBy('energy_pvs.pv_model')
            ->first();

        $pvMounts = DB::table('energy_systems')
            ->join('energy_system_pv_mounts', 'energy_systems.id', 'energy_system_pv_mounts.energy_system_id')
            ->leftJoin('energy_pv_mounts', 'energy_pv_mounts.id', 'energy_system_pv_mounts.energy_pv_mount_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_pv_mounts.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_pv_mounts.cost / energy_system_pv_mounts.unit) as cost_per_unit')
            )
            ->groupBy('energy_pv_mounts.model')
            ->first();

        $batteries = DB::table('energy_systems')
            ->join('energy_system_batteries', 'energy_systems.id', 'energy_system_batteries.energy_system_id')
            ->leftJoin('energy_batteries', 'energy_batteries.id', 'energy_system_batteries.battery_type_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_batteries.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_batteries.cost / energy_system_batteries.battery_units) * 4 as cost_per_unit')
            )
            ->groupBy('energy_batteries.battery_model')
            ->first();

        $houseWiring = DB::table('energy_system_wiring_houses')
            ->join('energy_systems', 'energy_system_wiring_houses.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_wiring_houses.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_wiring_houses.cost / energy_system_wiring_houses.unit) as cost_per_unit')
            )
            ->first();

        $fbsWiring = DB::table('energy_system_fbs_wirings')
            ->join('energy_systems', 'energy_system_fbs_wirings.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_wirings.cost', '>', 0)
            ->select(
                DB::raw('(energy_system_fbs_wirings.cost / energy_system_fbs_wirings.unit) as cost_per_unit')
            )
            ->first(); 

        $fbsLock = DB::table('energy_system_fbs_locks')
            ->join('energy_systems', 'energy_system_fbs_locks.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_locks.cost', '>', 0)
            ->select(
                DB::raw('(energy_system_fbs_locks.cost / energy_system_fbs_locks.unit) *2 as cost_per_unit')
            )
            ->first(); 

        $fbsFan = DB::table('energy_system_fbs_fans')
            ->join('energy_systems', 'energy_system_fbs_fans.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_fans.cost', '>', 0)
            ->select(
                DB::raw('(energy_system_fbs_fans.cost / energy_system_fbs_fans.unit) as cost_per_unit')
            )
            ->first();

        $fbsCabinet = DB::table('energy_system_fbs_cabinets')
            ->join('energy_systems', 'energy_system_fbs_cabinets.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_cabinets.cost', '>', 0)
            ->select(
                DB::raw('(energy_system_fbs_cabinets.cost / energy_system_fbs_cabinets.unit) as cost_per_unit')
            )
            ->first();

        
        $refrigeratorFbs = DB::table('energy_system_refrigerator_costs')
            ->join('energy_systems', 'energy_system_refrigerator_costs.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_refrigerator_costs.cost', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                DB::raw('(energy_system_refrigerator_costs.cost / energy_system_refrigerator_costs.unit) as cost_per_unit')
            )
            ->first();

        $totalFbsComponent += $refrigeratorFbs->cost_per_unit + $fbsCabinet->cost_per_unit + $houseWiring->cost_per_unit 
        + $batteries->cost_per_unit + $fbsWiring->cost_per_unit 
       // $fbsFan->cost_per_unit + $fbsLock->cost_per_unit 
        + $pvMounts->cost_per_unit + $pvs->cost_per_unit + $bts->cost_per_unit + $relayDrivers->cost_per_unit 
        + $controllers->cost_per_unit + $inverters->cost_per_unit ;
        
        $totalFbsComponent = $totalFbsComponent * 1.16;

        $queryCommunities =  DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->leftJoin('households as all_households', 'all_households.community_id',
                'communities.id')
            ->leftJoin('energy_system_types as all_energy_types', 'all_energy_types.id',
                'all_households.energy_system_type_id') 
            ->leftJoin('energy_systems', 'energy_systems.community_id', 'communities.id') 
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'all_households.id')
            ->leftJoin('grid_community_compounds', 'communities.id',
                'grid_community_compounds.community_id')
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->where('communities.energy_system_cycle_id', '!=', null)
            ->where('all_households.household_status_id', '!=', 8)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('compounds')
                    ->whereRaw('compounds.community_id = communities.id');
            }) 
            ->select( 
                'communities.english_name', 
                'regions.english_name as region',
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 2 THEN 1 END) as sum_FBS'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 1 THEN 1 END) as sum_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 4 THEN 1 END) as sum_SMG'),
                DB::raw('COUNT(CASE WHEN all_households.household_status_id != 4 AND 
                    all_households.household_status_id  != 8 THEN 1 END) as household'),
                    DB::raw('group_concat(DISTINCT CASE WHEN community_donors.is_archived = 0 THEN donors.donor_name END) as donors'),
                DB::raw('CASE 
                    WHEN energy_systems.energy_system_type_id = 2 THEN (' . $totalFbsComponent . ' * COUNT(CASE WHEN all_households.household_status_id != 4 AND all_households.household_status_id != 8 THEN 1 END)) 
                    ELSE (((energy_systems.total_costs * 0.16) + energy_systems.total_costs) * 0.10 ) + energy_systems.total_costs 
                    END as system_cost')
                )
            ->groupBy('communities.english_name');

        $queryCompounds = DB::table('compounds') 
            ->leftJoin('grid_community_compounds', 'compounds.id',
                'grid_community_compounds.compound_id')
            ->leftJoin('energy_systems', 'grid_community_compounds.energy_system_id',
                'energy_systems.id')
            ->join('communities', 'communities.id', 'compounds.community_id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('compound_households', 'compound_households.compound_id', 'compounds.id')
            ->join('households', 'compound_households.household_id', 'households.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 
                'energy_system_types.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('community_donors', 'community_donors.compound_id', 'compounds.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->where('communities.energy_system_cycle_id', '!=', null)
            ->where(function ($query) {
                $query->where('communities.community_status_id', 2);
            })
            ->select(
                'compounds.english_name', 
                'regions.english_name as region',
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 2 THEN households.id END) as sum_FBS'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 1 THEN households.id END) as sum_MG'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 4 THEN households.id END) as sum_SMG'),
                DB::raw('COUNT(CASE WHEN households.household_status_id != 8 AND households.id 
                    AND households.is_archived = 0 THEN 0 END) as household'),
                DB::raw('group_concat(DISTINCT CASE WHEN community_donors.is_archived = 0 THEN donors.donor_name END) as donors'),
                DB::raw('CASE 
                WHEN energy_systems.energy_system_type_id = 2 THEN (' . $totalFbsComponent . ' * COUNT(CASE WHEN households.household_status_id != 4 AND households.household_status_id != 8 THEN 1 END)) 
                ELSE (((energy_systems.total_costs * 0.16) + energy_systems.total_costs) * 0.10 ) + energy_systems.total_costs 
                END as system_cost')
            )
            ->groupBy('compounds.english_name');

        $this->misc = DB::table('households')
            ->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 5)
            ->where('energy_request_systems.recommendede_energy_system_id', 2)
            ->where('households.energy_system_cycle_id', '!=', null)
            ->count();

        $this->miscCost = $totalFbsComponent * $this->misc;
      
        // Fetch data from EnergyCostSystem export
        $energySystemsFromSheet = (new EnergyCostSystem($this->request))->collection();

        // Add system_cost to the query result
        foreach ($queryCompounds->get() as $key => $queryCompound) {

            $energySystem = $energySystemsFromSheet->get($key);
            $queryCompound->system_cost = $energySystem ? $energySystem->system_cost : null;
        }

        if($this->request->community_id) {

            $queryCompounds->where("communities.id", $this->request->community_id);
            $queryCommunities->where("communities.id", $this->request->community_id);
        }
        if($this->request->energy_type_id) {

            $queryCompounds->where("energy_systems.energy_system_type_id", $this->request->energy_type_id);
            $queryCommunities->where("energy_systems.energy_system_type_id", $this->request->energy_type_id);
        }
        if($this->request->energy_cycle_id) {

            $queryCompounds->where("energy_systems.energy_system_cycle_id", $this->request->energy_cycle_id);
            $queryCommunities->where("energy_systems.energy_system_cycle_id", $this->request->energy_cycle_id);
        }


        $communitiesCollection = collect($queryCommunities->get());
        $compoundsCollection = collect($queryCompounds->get());

        return $compoundsCollection->merge($communitiesCollection);
    } 

    public function startCell(): string
    {
        return 'A3';
    }

    public function title(): string
    {
        return 'Installation budget';
    }

       /**
     * Write code on Method
     *
     * @return response()
     */ 
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $sheet = $event->sheet->getDelegate();
        
                for ($row = 1; $row <= $highestRow; $row++) {
                    $cell = $sheet->getCell('H' . $row);
                    $value = $cell->getValue();

                    // Check if the cell value is numeric
                    if (is_numeric($value)) {

                        $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                    }
                }

                $event->sheet->getDelegate()->freezePane('A3'); 
            }
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:H1');
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', '# confirmed FBS'); 
        $sheet->setCellValue('D1', '# confirmed households/meters (MG)'); 
        $sheet->setCellValue('E1', 'Small MG'); 
        $sheet->setCellValue('F1', '# of Households'); 
        $sheet->setCellValue('G1', 'Donor'); 
        $sheet->setCellValue('H1', 'Cost'); 

        $sheet->setCellValue('A2', 'MISC FBS -- "Requested Systems"');     
        $sheet->setCellValue('B2', ' ');   
        $sheet->setCellValue('C2', $this->misc);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }
}