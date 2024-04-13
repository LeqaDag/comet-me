<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class EnergyCostSystemComponent implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents
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
         
        $batteries = DB::table('energy_systems')
            ->join('energy_system_batteries', 'energy_systems.id', 'energy_system_batteries.energy_system_id')
            ->leftJoin('energy_batteries', 'energy_batteries.id', 'energy_system_batteries.battery_type_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_batteries.cost', '>', 0)
            ->select(
                'energy_batteries.battery_model as model',
                DB::raw('SUM(energy_system_batteries.battery_units) as total_units'),
                DB::raw('(energy_system_batteries.cost / energy_system_batteries.battery_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_batteries.cost) as total_cost'),
            )
            ->groupBy('energy_batteries.battery_model')
            ->get();

        $batteryMounts = DB::table('energy_systems')
            ->join('energy_system_battery_mounts', 'energy_systems.id', 'energy_system_battery_mounts.energy_system_id')
            ->leftJoin('energy_battery_mounts', 'energy_battery_mounts.id', 'energy_system_battery_mounts.energy_battery_mount_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_battery_mounts.cost', '>', 0)
            ->select(
                'energy_battery_mounts.model as model',
                DB::raw('SUM(energy_system_battery_mounts.unit) as total_units'),
                DB::raw('(energy_system_battery_mounts.cost / energy_system_battery_mounts.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_battery_mounts.cost) as total_cost'),
            )
            ->groupBy('energy_battery_mounts.model')
            ->get();

        $pvs = DB::table('energy_systems')
            ->join('energy_system_pvs', 'energy_systems.id', 'energy_system_pvs.energy_system_id')
            ->leftJoin('energy_pvs', 'energy_pvs.id', 'energy_system_pvs.pv_type_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_pvs.cost', '>', 0)
            ->select(
                'energy_pvs.pv_model as model',
                DB::raw('SUM(energy_system_pvs.pv_units) as total_units'),
                DB::raw('(energy_system_pvs.cost / energy_system_pvs.pv_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_pvs.cost) as total_cost'),
            )
            ->groupBy('energy_pvs.pv_model')
            ->get();

        $pvMounts = DB::table('energy_systems')
            ->join('energy_system_pv_mounts', 'energy_systems.id', 'energy_system_pv_mounts.energy_system_id')
            ->leftJoin('energy_pv_mounts', 'energy_pv_mounts.id', 'energy_system_pv_mounts.energy_pv_mount_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_pv_mounts.cost', '>', 0)
            ->select(
                'energy_pv_mounts.model as model',
                DB::raw('SUM(energy_system_pv_mounts.unit) as total_units'),
                DB::raw('(energy_system_pv_mounts.cost / energy_system_pv_mounts.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_pv_mounts.cost) as total_cost'),
            )
            ->groupBy('energy_pv_mounts.model')
            ->get();
   
        $controllers = DB::table('energy_system_charge_controllers')
            ->join('energy_systems', 'energy_system_charge_controllers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 
                'energy_charge_controllers.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_charge_controllers.cost', '>', 0)
            ->select(
                'energy_charge_controllers.charge_controller_model as model',
                DB::raw('SUM(energy_system_charge_controllers.controller_units) as total_units'),
                DB::raw('(energy_system_charge_controllers.cost / energy_system_charge_controllers.controller_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_charge_controllers.cost) as total_cost'),
            )
            ->groupBy('energy_charge_controllers.charge_controller_model')
            ->get(); 

        $inverters = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                'energy_inverters.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_inverters.cost', '>', 0)
            ->select(
                'energy_inverters.inverter_model as model',
                DB::raw('SUM(energy_system_inverters.inverter_units) as total_units'),
                DB::raw('(energy_system_inverters.cost / energy_system_inverters.inverter_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_inverters.cost) as total_cost'),
            )
            ->groupBy('energy_inverters.inverter_model')
            ->get(); 

        $relayDrivers = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                'energy_relay_drivers.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_relay_drivers.cost', '>', 0)
            ->select(
                'energy_relay_drivers.model as model',
                DB::raw('SUM(energy_system_relay_drivers.relay_driver_units) as total_units'),
                DB::raw('(energy_system_relay_drivers.cost / energy_system_relay_drivers.relay_driver_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_relay_drivers.cost) as total_cost'),
            )
            ->groupBy('energy_relay_drivers.model')
            ->get(); 

        $loadRelay = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                'energy_load_relays.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_load_relays.cost', '>', 0)
            ->select(
                'energy_load_relays.load_relay_model as model',
                DB::raw('SUM(energy_system_load_relays.load_relay_units) as total_units'),
                DB::raw('(energy_system_load_relays.cost / energy_system_load_relays.load_relay_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_load_relays.cost) as total_cost'),
            )
            ->groupBy('energy_load_relays.load_relay_model')
            ->get(); 

        $bsps = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                'energy_battery_status_processors.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_battery_status_processors.cost', '>', 0)
            ->select(
                'energy_battery_status_processors.model as model',
                DB::raw('SUM(energy_system_battery_status_processors.bsp_units) as total_units'),
                DB::raw('(energy_system_battery_status_processors.cost / energy_system_battery_status_processors.bsp_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_battery_status_processors.cost) as total_cost'),
            )
            ->groupBy('energy_battery_status_processors.model')
            ->get(); 

        $bts = DB::table('energy_system_battery_temperature_sensors')
            ->join('energy_systems', 'energy_system_battery_temperature_sensors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_temperature_sensors', 'energy_battery_temperature_sensors.id',
                'energy_system_battery_temperature_sensors.energy_battery_temperature_sensor_id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_battery_temperature_sensors.cost', '>', 0)
            ->select(
                'energy_battery_temperature_sensors.BTS_model as model',
                DB::raw('SUM(energy_system_battery_temperature_sensors.bts_units) as total_units'),
                DB::raw('(energy_system_battery_temperature_sensors.cost / energy_system_battery_temperature_sensors.bts_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_battery_temperature_sensors.cost) as total_cost'),
            )
            ->groupBy('energy_battery_temperature_sensors.BTS_model')
            ->get(); 

        $rccs = DB::table('energy_system_remote_control_centers')
            ->join('energy_systems', 'energy_system_remote_control_centers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id', 
                'energy_remote_control_centers.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_remote_control_centers.cost', '>', 0)
            ->select(
                'energy_remote_control_centers.model as model',
                DB::raw('SUM(energy_system_remote_control_centers.rcc_units) as total_units'),
                DB::raw('(energy_system_remote_control_centers.cost / energy_system_remote_control_centers.rcc_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_remote_control_centers.cost) as total_cost'),
            )
            ->groupBy('energy_remote_control_centers.model')
            ->get(); 

        $loggers = DB::table('energy_system_monitorings')
            ->join('energy_systems', 'energy_system_monitorings.energy_system_id', 
                'energy_systems.id')
            ->join('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id', 
                'energy_monitorings.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_monitorings.cost', '>', 0)
            ->select(
                'energy_monitorings.monitoring_model as model',
                DB::raw('SUM(energy_system_monitorings.monitoring_units) as total_units'),
                DB::raw('(energy_system_monitorings.cost / energy_system_monitorings.monitoring_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_monitorings.cost) as total_cost'),
            )
            ->groupBy('energy_monitorings.monitoring_model')
            ->get(); 

        $generators = DB::table('energy_system_generators')
            ->join('energy_systems', 'energy_system_generators.energy_system_id', 
                'energy_systems.id')
            ->join('energy_generators', 'energy_system_generators.energy_generator_id', 
                'energy_generators.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_generators.cost', '>', 0)
            ->select(
                'energy_generators.generator_model as model',
                DB::raw('SUM(energy_system_generators.generator_units) as total_units'),
                DB::raw('(energy_system_generators.cost / energy_system_generators.generator_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_generators.cost) as total_cost'),
            )
            ->groupBy('energy_generators.generator_model')
            ->get(); 

        $turbines = DB::table('energy_system_wind_turbines')
            ->join('energy_systems', 'energy_system_wind_turbines.energy_system_id', 
                'energy_systems.id')
            ->join('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id', 
                'energy_wind_turbines.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_wind_turbines.cost', '>', 0)
            ->select(
                'energy_wind_turbines.wind_turbine_model as model',
                DB::raw('SUM(energy_system_wind_turbines.turbine_units) as total_units'),
                DB::raw('(energy_system_wind_turbines.cost / energy_system_wind_turbines.turbine_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_wind_turbines.cost) as total_cost'),
            )
            ->groupBy('energy_wind_turbines.wind_turbine_model')
            ->get(); 

        $pvMcb = DB::table('energy_system_mcb_pvs')
            ->join('energy_systems', 'energy_system_mcb_pvs.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', 
                'energy_mcb_pvs.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_mcb_pvs.cost', '>', 0)
            ->select(
                'energy_mcb_pvs.model as model',
                DB::raw('SUM(energy_system_mcb_pvs.mcb_pv_units) as total_units'),
                DB::raw('(energy_system_mcb_pvs.cost / energy_system_mcb_pvs.mcb_pv_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_mcb_pvs.cost) as total_cost'),
            )
            ->groupBy('energy_mcb_pvs.model')
            ->get();

        $controllerMcb = DB::table('energy_system_mcb_charge_controllers')
            ->join('energy_systems', 'energy_system_mcb_charge_controllers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_charge_controllers', 
                'energy_system_mcb_charge_controllers.energy_mcb_charge_controller_id', 
                'energy_mcb_charge_controllers.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_mcb_charge_controllers.cost', '>', 0)
            ->select(
                'energy_mcb_charge_controllers.model as model',
                DB::raw('SUM(energy_system_mcb_charge_controllers.mcb_controller_units) as total_units'),
                DB::raw('(energy_system_mcb_charge_controllers.cost / energy_system_mcb_charge_controllers.mcb_controller_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_mcb_charge_controllers.cost) as total_cost'),
            )
            ->groupBy('energy_mcb_charge_controllers.model')
            ->get();

        $inventerMcb = DB::table('energy_system_mcb_inverters')
            ->join('energy_systems', 'energy_system_mcb_inverters.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_inverters', 'energy_system_mcb_inverters.energy_mcb_inverter_id', 
                'energy_mcb_inverters.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_mcb_inverters.cost', '>', 0)
            ->select(
                'energy_mcb_inverters.inverter_MCB_model as model',
                DB::raw('SUM(energy_system_mcb_inverters.mcb_inverter_units) as total_units'),
                DB::raw('(energy_system_mcb_inverters.cost / energy_system_mcb_inverters.mcb_inverter_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_mcb_inverters.cost) as total_cost'),
            )
            ->groupBy('energy_mcb_inverters.inverter_MCB_model')
            ->get();

        $airConditioner = DB::table('energy_system_air_conditioners')
            ->join('energy_systems', 'energy_system_air_conditioners.energy_system_id', 
                'energy_systems.id')
            ->join('energy_air_conditioners', 'energy_system_air_conditioners.energy_air_conditioner_id', 
                'energy_air_conditioners.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_air_conditioners.cost', '>', 0)
            ->select(
                'energy_air_conditioners.model as model',
                DB::raw('SUM(energy_system_air_conditioners.energy_air_conditioner_units) as total_units'),
                DB::raw('(energy_system_air_conditioners.cost / energy_system_air_conditioners.energy_air_conditioner_units) as cost_per_unit'),
                DB::raw('SUM(energy_system_air_conditioners.cost) as total_cost'),
            )
            ->groupBy('energy_air_conditioners.model')
            ->get();

        $houseWiring = DB::table('energy_system_wiring_houses')
            ->join('energy_systems', 'energy_system_wiring_houses.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_wiring_houses.cost', '>', 0)
            ->select(
                DB::raw("'House Wiring' as model"),
                DB::raw('SUM(energy_system_wiring_houses.unit) as total_units'),
                DB::raw('(energy_system_wiring_houses.cost / energy_system_wiring_houses.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_wiring_houses.cost) as total_cost'),
            )
            ->get();

        $electricityRoom = DB::table('grid_community_compounds')
            ->join('energy_systems', 'grid_community_compounds.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('grid_community_compounds.electricity_room_cost', '>', 0)
            ->select(
                DB::raw("'Elecricity room' as model"),
                DB::raw('SUM(grid_community_compounds.electricity_room_number) as total_units'),
                DB::raw('(grid_community_compounds.electricity_room_cost / grid_community_compounds.electricity_room_number) 
                    as cost_per_unit'),
                DB::raw('SUM(grid_community_compounds.electricity_room_cost) as total_cost'),
            )
            ->get();

        $electricityRoomBos = DB::table('grid_community_compounds')
            ->join('energy_systems', 'grid_community_compounds.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('grid_community_compounds.electricity_room_bos_cost', '>', 0)
            ->select(
                DB::raw("'Elecricity room BoS' as model"),
                DB::raw('SUM(grid_community_compounds.electricity_room_bos_number) as total_units'),
                DB::raw('(grid_community_compounds.electricity_room_bos_cost / grid_community_compounds.electricity_room_bos_number) 
                    as cost_per_unit'),
                DB::raw('SUM(grid_community_compounds.electricity_room_bos_cost) as total_cost'),
            )
            ->get();

        $grid = DB::table('grid_community_compounds')
            ->join('energy_systems', 'grid_community_compounds.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('grid_community_compounds.grid_cost', '>', 0)
            ->select(
                DB::raw("'Community grid' as model"),
                DB::raw('SUM(grid_community_compounds.grid_number) as total_units'),
                DB::raw('(grid_community_compounds.grid_cost / grid_community_compounds.grid_number) 
                    as cost_per_unit'),
                DB::raw('SUM(grid_community_compounds.grid_cost) as total_cost'),
            )
            ->get();

        $fbsWiring = DB::table('energy_system_fbs_wirings')
            ->join('energy_systems', 'energy_system_fbs_wirings.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_wirings.cost', '>', 0)
            ->select(
                DB::raw("'FBS Wiring' as model"),
                DB::raw('SUM(energy_system_fbs_wirings.unit) as total_units'),
                DB::raw('(energy_system_fbs_wirings.cost / energy_system_fbs_wirings.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_fbs_wirings.cost) as total_cost'),
            )
            ->get(); 

        $fbsLock = DB::table('energy_system_fbs_locks')
            ->join('energy_systems', 'energy_system_fbs_locks.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_locks.cost', '>', 0)
            ->select(
                DB::raw("'Locks for FBS' as model"),
                DB::raw('SUM(energy_system_fbs_locks.unit) as total_units'),
                DB::raw('(energy_system_fbs_locks.cost / energy_system_fbs_locks.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_fbs_locks.cost) as total_cost'),
            )
            ->get(); 

        $fbsFan = DB::table('energy_system_fbs_fans')
            ->join('energy_systems', 'energy_system_fbs_fans.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_fans.cost', '>', 0)
            ->select(
                DB::raw("'Fan for FBS' as model"),
                DB::raw('SUM(energy_system_fbs_fans.unit) as total_units'),
                DB::raw('(energy_system_fbs_fans.cost / energy_system_fbs_fans.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_fbs_fans.cost) as total_cost'),
            )
            ->get();

        $fbsCabinet = DB::table('energy_system_fbs_cabinets')
            ->join('energy_systems', 'energy_system_fbs_cabinets.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_system_fbs_cabinets.cost', '>', 0)
            ->select(
                DB::raw("'Cabinets' as model"),
                DB::raw('SUM(energy_system_fbs_cabinets.unit) as total_units'),
                DB::raw('(energy_system_fbs_cabinets.cost / energy_system_fbs_cabinets.unit) as cost_per_unit'),
                DB::raw('SUM(energy_system_fbs_cabinets.cost) as total_cost'),
            )
            ->get();
 
 
        $mergedData = $batteries->merge($batteryMounts)->merge($pvs)->merge($pvMounts)->merge($controllers)
            ->merge($inverters)->merge($relayDrivers)->merge($loadRelay)->merge($bsps)->merge($bts)
            ->merge($rccs)->merge($loggers)->merge($generators)->merge($turbines)->merge($pvMcb)
            ->merge($controllerMcb)->merge($inventerMcb)->merge($airConditioner)->merge($houseWiring)
            ->merge($electricityRoom)->merge($electricityRoomBos)->merge($grid)
            ->merge($fbsWiring)->merge($fbsLock)->merge($fbsFan)->merge($fbsCabinet);

        return $mergedData;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['Model', 'Units', 'Cost Per Unit', 'Total Cost'];
    }


    public function title(): string
    {
        return 'Components Cost';
    }

    public function startCell(): string
    {
        return 'A3';
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
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                $sheet = $event->sheet->getDelegate();
        
                for ($row = 1; $row <= $highestRow; $row++) {
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cell = $sheet->getCell($col . $row);
                        $value = $cell->getValue();

                        // Check if the cell value is numeric
                        if (is_numeric($value)) {

                            $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                        }
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
        $sheet->setAutoFilter('A1:D1');

        // $sheet->setCellValue('A1', '# of Families');
        // $sheet->setCellValue('A2', 'Component');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}