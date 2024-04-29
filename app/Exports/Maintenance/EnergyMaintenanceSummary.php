<?php

namespace App\Exports\Maintenance;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Carbon\Carbon;

class EnergyMaintenanceSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell, WithEvents
{
 
    // Routine Maintenance & Management 
    private $completedEnergyPhone = 0, $completedRefrigerator = 0, $completedRefrigeratorReplaced = 0,
        $completedTurbine = 0, $completedGenerator = 0, $replacedChrageController = 0, 
        $safteyChecks = 0;

    // FBS Maintenance 
    private $replacedFbsBatteries = 0, $upgradeFbsPv = 0, $replacedFbsElectronics = 0, $MovedFbsSystem = 0;

    // MG Upgrades
    private $upgradeMgPv = 0, $replacedMgBatteries = 0, $upgradeMgElectronics = 0, $installedMgGenerator = 0;

    // MG-extension
    private $mgNewMeters = 0, $mgExistingMeters = 0;

    protected $request;

    function __construct($request) {

        $this->request = $request; 
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $energyMaintenance =  DB::table('electricity_maintenance_calls')
            ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3);

        $energyPhoneMaintenance = $energyMaintenance->where('electricity_maintenance_calls.maintenance_type_id', 2);
        
        $refrigeratorMaintenance = DB::table('refrigerator_maintenance_calls')
            ->leftJoin('public_structures', 'refrigerator_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->leftJoin('refrigerator_maintenance_call_actions', 'refrigerator_maintenance_calls.id', 
                'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id')
            ->leftJoin('maintenance_refrigerator_actions', 
                'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                'maintenance_refrigerator_actions.id')
            ->where('refrigerator_maintenance_calls.is_archived', 0)
            ->where('refrigerator_maintenance_calls.maintenance_status_id', 3);

        $refrigeratorPhoneMaintenance = $refrigeratorMaintenance->where('refrigerator_maintenance_calls.maintenance_type_id', 2);

        $refrigeratorReplacedMaintenance = DB::table('refrigerator_maintenance_calls')
            ->leftJoin('public_structures', 'refrigerator_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->leftJoin('refrigerator_maintenance_call_actions', 'refrigerator_maintenance_calls.id', 
                'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id')
            ->leftJoin('maintenance_refrigerator_actions', 
                'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                'maintenance_refrigerator_actions.id')
            ->where('refrigerator_maintenance_calls.is_archived', 0)
            ->where('refrigerator_maintenance_calls.maintenance_status_id', 3)
            ->where('maintenance_refrigerator_actions.id', 11)
            ->orWhere('maintenance_refrigerator_actions.id', 21);

        $turbineMaintenance = DB::table('electricity_maintenance_calls')
            ->join('energy_turbine_communities', 'electricity_maintenance_calls.energy_turbine_community_id', 
                'energy_turbine_communities.id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->distinct();

        $generatorMaintenance = DB::table('electricity_maintenance_calls')
            ->join('energy_generator_communities', 'electricity_maintenance_calls.energy_generator_community_id', 
                'energy_generator_communities.id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->distinct();

        $replacedChrageControllerMaintenance = DB::table('electricity_maintenance_calls')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 37);
        
        $safteyChecksMaintenance =  DB::table('all_energy_meter_safety_checks')
            ->where('all_energy_meter_safety_checks.is_archived', 0)
            ->where('all_energy_meter_safety_checks.ph_loop', '<', 10)
            ->where('all_energy_meter_safety_checks.n_loop', '<', 10);

        // FBS Maintenance
        $energyFbsUserMaintenance = DB::table('electricity_maintenance_calls')
            ->join('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->join('all_energy_meters as energy_users', 'households.id', 'energy_users.household_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_users.energy_system_type_id', 2);

        $energyFbsPublicMaintenance = DB::table('electricity_maintenance_calls')
            ->join('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('all_energy_meters as energy_publics', 'public_structures.id', 'energy_publics.public_structure_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_publics.energy_system_type_id', 2);

        $energyFbsUserReplacedBatteries = $energyFbsUserMaintenance
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 39);

        $energyFbsPublicReplacedBatteries = $energyFbsPublicMaintenance
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 39);

        $energyFbsUserReplacedElectronics = DB::table('electricity_maintenance_calls')
            ->join('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->join('all_energy_meters as energy_users', 'households.id', 'energy_users.household_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_users.energy_system_type_id', 2)
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_issues.id', 6)
            ->where('energy_maintenance_actions.id', '!=', 39);

        $energyFbsPublicReplacedElectronics =  DB::table('electricity_maintenance_calls')
            ->join('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('all_energy_meters as energy_publics', 'public_structures.id', 'energy_publics.public_structure_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_publics.energy_system_type_id', 2)
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_issues.id', 6)
            ->where('energy_maintenance_actions.id', '!=', 39);

        $energyMovedFbsUser = 0;

        $energyMovedFbsPublic =  0;

        // MG
        $energyMgUserMaintenance = DB::table('electricity_maintenance_calls')
            ->join('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->join('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('all_energy_meters.energy_system_type_id', '!=', 2);

        $energyMgPublicMaintenance = DB::table('electricity_maintenance_calls')
            ->join('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('all_energy_meters as energy_publics', 'public_structures.id', 'energy_publics.public_structure_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_publics.energy_system_type_id', '!=', 2);

        $energyMgSystemMaintenance = DB::table('electricity_maintenance_calls')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->whereNotNull('electricity_maintenance_calls.energy_system_id');

        $energyMgUserReplacedBatteries = $energyMgUserMaintenance
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 39);

        $energyMgPublicReplacedBatteries = $energyMgPublicMaintenance
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 39);

        $energyMgSystemReplacedBatteries = $energyMgSystemMaintenance
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 39);

        $energyMgUserReplacedElectronics = DB::table('electricity_maintenance_calls')
            ->join('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->join('all_energy_meters as energy_users', 'households.id', 'energy_users.household_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_users.energy_system_type_id', '!=', 2)
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_issues.id', 6)
            ->where('energy_maintenance_actions.id', '!=', 39)
            ->distinct();

        $energyMgPublicReplacedElectronics =  DB::table('electricity_maintenance_calls')
            ->join('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('all_energy_meters as energy_publics', 'public_structures.id', 'energy_publics.public_structure_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('energy_publics.energy_system_type_id', '!=', 2)
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_issues.id', 6)
            ->where('energy_maintenance_actions.id', '!=', 39);

        $energyMgSystemReplacedElectronics = DB::table('electricity_maintenance_calls')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->whereNotNull('electricity_maintenance_calls.energy_system_id')
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_issues.id', 6)
            ->where('energy_maintenance_actions.id', '!=', 39);

        $lastWeekStart = Carbon::now()->subDays(7)->startOfDay();

        $newInstalledGenerators = DB::table('energy_generator_communities')
            ->where('created_at', '>=', $lastWeekStart);

        // MG Extension
        $mgUserExtensionNewStructures = DB::table('electricity_maintenance_calls')
            ->join('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->join('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->where('electricity_maintenance_calls.maintenance_status_id', 3)
            ->where('all_energy_meters.energy_system_type_id', '!=', 2)
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->where('energy_maintenance_actions.id', 9);

        // $mgUserExtensionNewMeters = DB::table('electricity_maintenance_calls')
        //     ->join('households', 'electricity_maintenance_calls.household_id', 
        //         'households.id')
        //     ->join('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
        //     ->where('electricity_maintenance_calls.is_archived', 0)
        //     ->where('electricity_maintenance_calls.maintenance_status_id', 3)
        //     ->where('all_energy_meters.energy_system_type_id', '!=', 2)
        //     ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
        //         'electricity_maintenance_call_actions.electricity_maintenance_call_id')
        //     ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
        //         'electricity_maintenance_call_actions.energy_maintenance_action_id')
        //     ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
        //         'energy_maintenance_actions.energy_maintenance_issue_id')
        //     ->where('energy_maintenance_actions.id', 12);

        $mgUserExtensionNewMeters =  DB::table('all_energy_meters')
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.installation_type_id', 3);


        if($this->request->public) {

            $energyPhoneMaintenance->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);

            $refrigeratorMaintenance->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);

            $refrigeratorPhoneMaintenance->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);

            $refrigeratorReplacedMaintenance->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        }
        if($this->request->community_id) {

            $energyPhoneMaintenance->where("electricity_maintenance_calls.community_id", $this->request->community_id);
            $refrigeratorMaintenance->where("refrigerator_maintenance_calls.community_id", $this->request->community_id);
            $refrigeratorPhoneMaintenance->where("refrigerator_maintenance_calls.community_id", $this->request->community_id);
            $refrigeratorReplacedMaintenance->where("refrigerator_maintenance_calls.community_id", $this->request->community_id);
            $turbineMaintenance->where("electricity_maintenance_calls.community_id", $this->request->community_id);
            $generatorMaintenance->where("electricity_maintenance_calls.community_id", $this->request->community_id);
        }
        if($this->request->date) {

            $energyPhoneMaintenance->where("electricity_maintenance_calls.date_completed", ">=", $this->request->date);
            $refrigeratorMaintenance->where("refrigerator_maintenance_calls.date_completed", ">=", $this->request->date);
            $refrigeratorPhoneMaintenance->where("refrigerator_maintenance_calls.date_completed", ">=", $this->request->date);
            $refrigeratorReplacedMaintenance->where("refrigerator_maintenance_calls.date_completed", ">=", $this->request->date);
            $turbineMaintenance->where("electricity_maintenance_calls.date_completed", ">=", $this->request->date);
            $generatorMaintenance->where("electricity_maintenance_calls.date_completed", ">=", $this->request->date);
            $safteyChecksMaintenance->where("all_energy_meter_safety_checks.visit_date", ">=", $this->request->date);
        } 

        $this->completedEnergyPhone = $energyPhoneMaintenance->count() + $refrigeratorPhoneMaintenance->count();
        $this->completedRefrigerator = $refrigeratorMaintenance->count();
        $this->completedRefrigeratorReplaced = $refrigeratorReplacedMaintenance->count();
        $this->completedTurbine = $turbineMaintenance->count();
        $this->completedGenerator = $generatorMaintenance->count();
        $this->replacedChrageController = $replacedChrageControllerMaintenance->count();
        $this->safteyChecks = $safteyChecksMaintenance->count();

        $this->replacedFbsBatteries = $energyFbsUserReplacedBatteries->count() + $energyFbsPublicReplacedBatteries->count(); 
        $this->upgradeFbsPv = 0;
        $this->replacedFbsElectronics = $energyFbsUserReplacedElectronics->count() + $energyFbsPublicReplacedElectronics->count(); 
        $this->MovedFbsSystem = 0;

        $this->upgradeMgPv = 0; 
        $this->replacedMgBatteries = $energyMgUserReplacedBatteries->count() + $energyMgPublicReplacedBatteries->count() +
            $energyMgSystemReplacedBatteries->count(); 
        $this->upgradeMgElectronics = $energyMgUserReplacedElectronics->count() + $energyMgPublicReplacedElectronics->count() +
            $energyMgSystemReplacedElectronics->count();
        $this->installedMgGenerator = $newInstalledGenerators->count();

        $this->mgNewMeters = $mgUserExtensionNewMeters->count();
        $this->mgExistingMeters = $mgUserExtensionNewStructures->count();

        $data = [
            [
                '# of Issues Resolved over the phone (Energy & Refrigerators)' => $this->completedEnergyPhone,
                '# of Issues Resolved for Refrigerators' => $this->completedRefrigerator,
                '# of Replaced Refrigerators' => $this->completedRefrigeratorReplaced,
                '# of Issues Resolved for Turbines' => $this->completedTurbine,
                '# of Issues Resolved for Generator' => $this->completedGenerator,
            ],
        ];
    
        return collect($data); 
    }


    public function title(): string
    {
        return 'Energy Maintenance Summary';
    }

    public function startCell(): string
    {
        return 'A2';
    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('A1:J1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A8:C8')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        // Routine Maintenance and Management
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->mergeCells('A4:I4');
        $sheet->mergeCells('A5:I5');
        $sheet->mergeCells('A6:I6');
        $sheet->mergeCells('A7:I7');
        $sheet->mergeCells('A8:I8');
        $sheet->mergeCells('A9:J9');

        $sheet->setCellValue('A1', 'Routine Maintenance and Management');
        $sheet->setCellValue('A2', '# of Issues Resolved over the phone');
        $sheet->setCellValue('A3', '# of Issues Resolved for Refrigerators');
        $sheet->setCellValue('A4', '# of Replaced Refrigerators');
        $sheet->setCellValue('A5', '# of Issues Resolved for Turbines');
        $sheet->setCellValue('A6', '# of Issues Resolved for Generator');
        $sheet->setCellValue('A7', '# of Replaced Broken Charge Controller');
        $sheet->setCellValue('A8', '# of Saftey Checks');

        $sheet->setCellValue('J2', $this->completedEnergyPhone);
        $sheet->setCellValue('J3', $this->completedRefrigerator);
        $sheet->setCellValue('J4', $this->completedRefrigeratorReplaced);
        $sheet->setCellValue('J5', $this->completedTurbine);
        $sheet->setCellValue('J6', $this->completedGenerator);
        $sheet->setCellValue('J7', $this->replacedChrageController);
        $sheet->setCellValue('J8', $this->safteyChecks);

        // Family-based Systems Maintenance
        $sheet->mergeCells('A10:J10');
        $sheet->mergeCells('A11:I11');
        $sheet->mergeCells('A12:I12');
        $sheet->mergeCells('A13:I13');
        $sheet->mergeCells('A14:I14');
        $sheet->mergeCells('A15:J15');

        $sheet->setCellValue('A10', 'Family-based Systems Maintenance');
        $sheet->setCellValue('A11', '# of Replaced batteries');
        $sheet->setCellValue('A12', '# of Upgraded PV Capacity');
        $sheet->setCellValue('A13', '# of Replaced Electronics');
        $sheet->setCellValue('A14', '# of Moved FBS or parts thereof for families that moved within or between communities.');

        $sheet->setCellValue('J11', $this->replacedFbsBatteries);
        $sheet->setCellValue('J12', $this->upgradeFbsPv);
        $sheet->setCellValue('J13', $this->replacedFbsElectronics);
        $sheet->setCellValue('J14', $this->MovedFbsSystem);

        // Micro-Grid Upgrades
        $sheet->mergeCells('A16:J16');
        $sheet->mergeCells('A17:I17');
        $sheet->mergeCells('A18:I18');
        $sheet->mergeCells('A19:I19');
        $sheet->mergeCells('A20:I20');
        $sheet->mergeCells('A21:J21');

        $sheet->setCellValue('A16', 'Micro-Grid Upgrades');
        $sheet->setCellValue('A17', '# of Upgraded PV Capacity');
        $sheet->setCellValue('A18', '# of Upgraded Electronics');
        $sheet->setCellValue('A19', '# of Replaced Batteries');
        $sheet->setCellValue('A20', '# of Installed New Generator (MG)');

        $sheet->setCellValue('J17', $this->upgradeMgPv);
        $sheet->setCellValue('J18', $this->upgradeMgElectronics);
        $sheet->setCellValue('J19', $this->replacedMgBatteries);
        $sheet->setCellValue('J20', $this->installedMgGenerator);

        // Grid Extensions
        $sheet->mergeCells('A22:J22');
        $sheet->mergeCells('A23:I23');
        $sheet->mergeCells('A24:I24');

        $sheet->setCellValue('A22', 'Grid Extensions');
        $sheet->setCellValue('A23', '# of Connected New Structures To Existing Meters/Users');
        $sheet->setCellValue('A24', '# of Connected New Households With New Meters');

        $sheet->setCellValue('J23', $this->mgNewMeters);
        $sheet->setCellValue('J24', $this->mgExistingMeters);

        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true, 'size' => 12]],
            10  => ['font' => ['bold' => true, 'size' => 12]],
            16  => ['font' => ['bold' => true, 'size' => 12]],
            22  => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}