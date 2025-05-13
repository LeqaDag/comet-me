<?php

namespace App\Exports\Maintenance;

use App\Models\EnergyUser;
use App\Models\AllMaintenanceTicket;
use App\Models\AllMaintenanceTicketAction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Carbon\Carbon;
use DB;

class MaintenanceSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell, WithEvents
{

    // Start with service with categories

    // Energy with categories
    private $energySocialRequest = 0, $energyUpgrade = 0, $energyRepairReplacement = 0, 
        $energyRefillBatteries = 0, $energyChargeBatteries = 0, $energyPvActions = 0, 
        $energySafety = 0, $energyRoutine = 0, $energyCometPolicy = 0, 
        $energyUserSupport = 0, $energyUpdating = 0, $energyTransfer = 0, 
        $energySoftware = 0, $energySetup = 0, $energyNewCycle = 0, 
        $energyGenerator = 0, $energyTotal = 0;
        
    // Refrigerator with categories
    private $refrigeratorSocialRequest = 0, $refrigeratorUpgrade = 0, $refrigeratorRepairReplacement = 0, 
        $refrigeratorSafety = 0, $refrigeratorRoutine = 0, $refrigeratorCometPolicy = 0, 
        $refrigeratorUserSupport = 0, $refrigeratorUpdating = 0, $refrigeratorTransfer = 0, 
        $refrigeratorSoftware = 0, $refrigeratorSetup = 0, $refrigeratorNewCycle = 0;

    // Water with categories
    private $waterSocialRequest = 0, $waterUpgrade = 0, $waterRepairReplacement = 0, 
        $waterSafety = 0, $waterRoutine = 0, $waterCometPolicy = 0, 
        $waterUserSupport = 0, $waterUpdating = 0, $waterTransfer = 0, 
        $waterSoftware = 0, $waterSetup = 0, $waterNewCycle = 0, $waterTotal = 0;

    // Internet with categories
    private $internetSocialRequest = 0, $internetUpgrade = 0, $internetRepairReplacement = 0, 
        $internetSafety = 0, $internetRoutine = 0, $internetCometPolicy = 0, 
        $internetUserSupport = 0, $internetUpdating = 0, $internetTransfer = 0, 
        $internetSoftware = 0, $internetSetup = 0, $internetNewCycle = 0, $internetTotal = 0;

    // Routine Maintenance & Management 
    private $completedEnergyPhone = 0, $completedWaterPhone = 0, $completedInternetPhone = 0, 
        $completedRefrigerator = 0, 
        $completedTurbine = 0, $completedGenerator = 0;

    // // FBS Maintenance 
    // private $replacedFbsBatteries = 0, $upgradeFbsPv = 0, $replacedFbsElectronics = 0, $MovedFbsSystem = 0;

    // // MG Upgrades
    // private $upgradeMgPv = 0, $replacedMgBatteries = 0, $upgradeMgElectronics = 0, $installedMgGenerator = 0;

    // // MG-extension
    // private $mgNewMeters = 0, $mgExistingMeters = 0;

    protected $request;

    function __construct($request) {

        $this->request = $request; 
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $socialRequest = "Social request";
        $upgrade = "Upgrade";
        $repairOrReplacement = "Repair or Replacement";
        $chargeBatteries = "Charge batteries";
        $refillBatteryWater = "Refill battery water";
        $pvAction = "PV action";
        $safety = "Safety";
        $routine = "Routine";
        $cometPolicy = "Comet policy";
        $userSupport = "User support";
        $updating = "Updating";
        $transfer = "Transfer";
        $software = "Software";
        $setup = "Setup";
        $newCycle = "New cycle";
        $generator = "Generator";

        $energyDataTickets = DB::table('all_maintenance_ticket_actions')
            ->join('energy_issues', 'energy_issues.comet_id', 'all_maintenance_ticket_actions.action_id')
            ->join('energy_actions', 'energy_issues.energy_action_id', 'energy_actions.id')
            ->join('action_categories as energy_categories', 'energy_categories.id', 'energy_actions.action_category_id')
            ->where("all_maintenance_ticket_actions.is_archived", 0)
            ->get();

        foreach ($energyDataTickets as $energyDataTicket) {
           
            if (strpos($energyDataTicket->english_name, $socialRequest) !== false) $this->energySocialRequest++;
            if (strpos($energyDataTicket->english_name, $upgrade) !== false) $this->energyUpgrade++;
            if (strpos($energyDataTicket->english_name, $repairOrReplacement) !== false) $this->energyRepairReplacement++;
            if (strpos($energyDataTicket->english_name, $refillBatteryWater) !== false) $this->energyRefillBatteries++;
            if (strpos($energyDataTicket->english_name, $chargeBatteries) !== false) $this->energyChargeBatteries++;
            if (strpos($energyDataTicket->english_name, $pvAction) !== false) $this->energyPvActions++;
            if (strpos($energyDataTicket->english_name, $safety) !== false) $this->energySafety++;
            if (strpos($energyDataTicket->english_name, $routine) !== false) $this->energyRoutine++;
            if (strpos($energyDataTicket->english_name, $cometPolicy) !== false) $this->energyCometPolicy++;
            if (strpos($energyDataTicket->english_name, $userSupport) !== false) $this->energyUserSupport++;
            if (strpos($energyDataTicket->english_name, $updating) !== false) $this->energyUpdating++;
            if (strpos($energyDataTicket->english_name, $transfer) !== false) $this->energyTransfer++;
            if (strpos($energyDataTicket->english_name, $setup) !== false) $this->energySetup++;
            if (strpos($energyDataTicket->english_name, $software) !== false) $this->energySoftware++;
            if (strpos($energyDataTicket->english_name, $newCycle) !== false) $this->energyNewCycle++;
            if (strpos($energyDataTicket->english_name, $generator) !== false) $this->energyGenerator++;
        } 

        $refrigeratorDataTickets = DB::table('all_maintenance_ticket_actions')
            ->join('refrigerator_issues', 'refrigerator_issues.comet_id', 'all_maintenance_ticket_actions.action_id')
            ->join('refrigerator_actions', 'refrigerator_issues.refrigerator_action_id', 'refrigerator_actions.id')
            ->join('action_categories as refrigerator_categories', 'refrigerator_categories.id', 'refrigerator_actions.action_category_id')
            ->where("all_maintenance_ticket_actions.is_archived", 0)
            ->get();

        foreach ($refrigeratorDataTickets as $refrigeratorDataTicket) {
           
            if (strpos($refrigeratorDataTicket->english_name, $socialRequest) !== false) $this->refrigeratorSocialRequest++;
            if (strpos($refrigeratorDataTicket->english_name, $upgrade) !== false) $this->refrigeratorUpgrade++;
            if (strpos($refrigeratorDataTicket->english_name, $repairOrReplacement) !== false) $this->refrigeratorRepairReplacement++;
            if (strpos($refrigeratorDataTicket->english_name, $safety) !== false) $this->refrigeratorSafety++;
            if (strpos($refrigeratorDataTicket->english_name, $routine) !== false) $this->refrigeratorRoutine++;
            if (strpos($refrigeratorDataTicket->english_name, $cometPolicy) !== false) $this->refrigeratorCometPolicy++;
            if (strpos($refrigeratorDataTicket->english_name, $userSupport) !== false) $this->refrigeratorUserSupport++;
            if (strpos($refrigeratorDataTicket->english_name, $updating) !== false) $this->refrigeratorUpdating++;
            if (strpos($refrigeratorDataTicket->english_name, $transfer) !== false) $this->refrigeratorTransfer++;
            if (strpos($refrigeratorDataTicket->english_name, $setup) !== false) $this->refrigeratorSetup++;
            if (strpos($refrigeratorDataTicket->english_name, $software) !== false) $this->refrigeratorSoftware++;
            if (strpos($refrigeratorDataTicket->english_name, $newCycle) !== false) $this->refrigeratorNewCycle++;
        } 

        $waterDataTickets = DB::table('all_maintenance_ticket_actions')
            ->join('water_issues', 'water_issues.comet_id', 'all_maintenance_ticket_actions.action_id')
            ->join('water_actions', 'water_issues.water_action_id', 'water_actions.id')
            ->join('action_categories as water_categories', 'water_categories.id', 'water_actions.action_category_id')
            ->where("all_maintenance_ticket_actions.is_archived", 0)
            ->get();

        foreach ($waterDataTickets as $waterDataTicket) {
        
            if (strpos($waterDataTicket->english_name, $socialRequest) !== false) $this->waterSocialRequest++;
            if (strpos($waterDataTicket->english_name, $upgrade) !== false) $this->waterUpgrade++;
            if (strpos($waterDataTicket->english_name, $repairOrReplacement) !== false) $this->waterRepairReplacement++;
            if (strpos($waterDataTicket->english_name, $safety) !== false) $this->waterSafety++;
            if (strpos($waterDataTicket->english_name, $routine) !== false) $this->waterRoutine++;
            if (strpos($waterDataTicket->english_name, $cometPolicy) !== false) $this->waterCometPolicy++;
            if (strpos($waterDataTicket->english_name, $userSupport) !== false) $this->waterUserSupport++;
            if (strpos($waterDataTicket->english_name, $updating) !== false) $this->waterUpdating++;
            if (strpos($waterDataTicket->english_name, $transfer) !== false) $this->waterTransfer++;
            if (strpos($waterDataTicket->english_name, $setup) !== false) $this->waterSetup++;
            if (strpos($waterDataTicket->english_name, $software) !== false) $this->waterSoftware++;
            if (strpos($waterDataTicket->english_name, $newCycle) !== false) $this->waterNewCycle++;
        } 

        $internetDataTickets = DB::table('all_maintenance_ticket_actions')
            ->join('internet_issues', 'internet_issues.comet_id', 'all_maintenance_ticket_actions.action_id')
            ->join('internet_actions', 'internet_issues.internet_action_id', 'internet_actions.id')
            ->join('action_categories as internet_categories', 'internet_categories.id', 'internet_actions.action_category_id')
            ->where("all_maintenance_ticket_actions.is_archived", 0)
            ->get();

        foreach ($internetDataTickets as $internetDataTicket) {
        
            if (strpos($internetDataTicket->english_name, $socialRequest) !== false) $this->internetSocialRequest++;
            if (strpos($internetDataTicket->english_name, $upgrade) !== false) $this->internetUpgrade++;
            if (strpos($internetDataTicket->english_name, $repairOrReplacement) !== false) $this->internetRepairReplacement++;
            if (strpos($internetDataTicket->english_name, $safety) !== false) $this->internetSafety++;
            if (strpos($internetDataTicket->english_name, $routine) !== false) $this->internetRoutine++;
            if (strpos($internetDataTicket->english_name, $cometPolicy) !== false) $this->internetCometPolicy++;
            if (strpos($internetDataTicket->english_name, $userSupport) !== false) $this->internetUserSupport++;
            if (strpos($internetDataTicket->english_name, $updating) !== false) $this->internetUpdating++;
            if (strpos($internetDataTicket->english_name, $transfer) !== false) $this->internetTransfer++;
            if (strpos($internetDataTicket->english_name, $setup) !== false) $this->internetSetup++;
            if (strpos($internetDataTicket->english_name, $software) !== false) $this->internetSoftware++;
            if (strpos($internetDataTicket->english_name, $newCycle) !== false) $this->internetNewCycle++;
        } 

        $this->completedEnergyPhone = AllMaintenanceTicket::where("is_archived", 0)
            ->where("maintenance_type_id", 2)
            ->where('maintenance_status_id', 3)
            ->where('service_type_id', 1)
            ->count();
        
        $this->completedWaterPhone = AllMaintenanceTicket::where("is_archived", 0)
            ->where("maintenance_type_id", 2)
            ->where('maintenance_status_id', 3)
            ->where('service_type_id', 2)
            ->count();

        $this->completedInternetPhone = AllMaintenanceTicket::where("is_archived", 0)
            ->where("maintenance_type_id", 2)
            ->where('maintenance_status_id', 3)
            ->where('service_type_id', 3)
            ->count();

        // # of tickets for refrigerator
        $this->completedRefrigerator = AllMaintenanceTicketAction::where("is_archived", 0)
            ->where("action_id", "like", "%R%")  
            ->count();

        $this->energyTotal = AllMaintenanceTicketAction::where("is_archived", 0)
            ->where("action_id", "like", "%E%") 
            ->count(); 

        $this->waterTotal = AllMaintenanceTicketAction::where("is_archived", 0)
            ->where("action_id", "like", "%W%") 
            ->count(); 

        $this->internetTotal = AllMaintenanceTicketAction::where("is_archived", 0)
            ->where("action_id", "like", "%I%") 
            ->count();

        $data = [
            [
                '# of Issues Resolved over the phone (Energy)' => $this->completedEnergyPhone,
                '# of Issues Resolved over the phone (Water)' => $this->completedWaterPhone,
                '# of Issues Resolved over the phone (Internet)' => $this->completedInternetPhone,
            ],
        ];
    
        return collect($data); 
    }


    public function title(): string
    {
        return 'Maintenance Summary';
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
        // Categories
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');
        $sheet->mergeCells('A4:C4');
        $sheet->mergeCells('A5:C5');
        $sheet->mergeCells('A6:C6');
        $sheet->mergeCells('A7:C7');
        $sheet->mergeCells('A8:C8');
        $sheet->mergeCells('A9:C9');
        $sheet->mergeCells('A10:C10');
        $sheet->mergeCells('A11:C11');
        $sheet->mergeCells('A12:C12');
        $sheet->mergeCells('A13:C13');
        $sheet->mergeCells('A14:C14');
        $sheet->mergeCells('A15:C15');
        $sheet->mergeCells('A16:C16');
        $sheet->mergeCells('A17:C17');
        $sheet->mergeCells('A18:C18');
        
        $sheet->setCellValue('D1', 'Energy (not including refrigerator)');
        $sheet->setCellValue('E1', 'Water');
        $sheet->setCellValue('F1', 'Internet');
        $sheet->setCellValue('G1', 'Refrigerator');

        $sheet->setCellValue('A1', 'Category Name');
        $sheet->setCellValue('A2', '# of Social request');
        $sheet->setCellValue('A3', '# of Repair or Replacement');
        $sheet->setCellValue('A4', '# of Upgrade');
        $sheet->setCellValue('A5', '# of Setup');
        $sheet->setCellValue('A6', '# of Comet policy');
        $sheet->setCellValue('A7', '# of User support');
        $sheet->setCellValue('A8', '# of Routine');
        $sheet->setCellValue('A9', '# of Software');
        $sheet->setCellValue('A10', '# of Safety');
        $sheet->setCellValue('A11', '# of Updating');
        $sheet->setCellValue('A12', '# of New cycle');
        $sheet->setCellValue('A13', '# of PV action');
        $sheet->setCellValue('A14', '# of Charge batteries');
        $sheet->setCellValue('A15', '# of Refill battery water');
        $sheet->setCellValue('A16', '# of Generator');
        $sheet->setCellValue('A17', '# of Transfer');
        $sheet->setCellValue('A18', 'Total');

        $sheet->setCellValue('D2', $this->energySocialRequest);
        $sheet->setCellValue('D3', $this->energyRepairReplacement);
        $sheet->setCellValue('D4', $this->energyUpgrade);
        $sheet->setCellValue('D5', $this->energySetup);
        $sheet->setCellValue('D6', $this->energyCometPolicy);
        $sheet->setCellValue('D7', $this->energyUserSupport);
        $sheet->setCellValue('D8', $this->energyRoutine);
        $sheet->setCellValue('D9', $this->energySoftware);
        $sheet->setCellValue('D10', $this->energySafety);
        $sheet->setCellValue('D11', $this->energyUpdating);
        $sheet->setCellValue('D12', $this->energyNewCycle);
        $sheet->setCellValue('D13', $this->energyPvActions);
        $sheet->setCellValue('D14', $this->energyChargeBatteries);
        $sheet->setCellValue('D15', $this->energyRefillBatteries);
        $sheet->setCellValue('D16', $this->energyGenerator);
        $sheet->setCellValue('D17', $this->energyTransfer);
        $sheet->setCellValue('D18', $this->energyTotal);

        $sheet->setCellValue('E2', $this->waterSocialRequest);
        $sheet->setCellValue('E3', $this->waterRepairReplacement);
        $sheet->setCellValue('E4', $this->waterUpgrade);
        $sheet->setCellValue('E5', $this->waterSetup);
        $sheet->setCellValue('E6', $this->waterCometPolicy);
        $sheet->setCellValue('E7', $this->waterUserSupport);
        $sheet->setCellValue('E8', $this->waterRoutine);
        $sheet->setCellValue('E9', $this->waterSoftware);
        $sheet->setCellValue('E10', $this->waterSafety);
        $sheet->setCellValue('E11', $this->waterUpdating);
        $sheet->setCellValue('E12', $this->waterNewCycle);
        $sheet->setCellValue('E17', $this->waterTransfer);
        $sheet->setCellValue('E18', $this->waterTotal);

        $sheet->setCellValue('F2', $this->internetSocialRequest);
        $sheet->setCellValue('F3', $this->internetRepairReplacement);
        $sheet->setCellValue('F4', $this->internetUpgrade);
        $sheet->setCellValue('F5', $this->internetSetup);
        $sheet->setCellValue('F6', $this->internetCometPolicy);
        $sheet->setCellValue('F7', $this->internetUserSupport);
        $sheet->setCellValue('F8', $this->internetRoutine);
        $sheet->setCellValue('F9', $this->internetSoftware);
        $sheet->setCellValue('F10', $this->internetSafety);
        $sheet->setCellValue('F11', $this->internetUpdating);
        $sheet->setCellValue('F12', $this->internetNewCycle);
        $sheet->setCellValue('F17', $this->internetTransfer);
        $sheet->setCellValue('F18', $this->internetTotal);

        $sheet->setCellValue('G2', $this->refrigeratorSocialRequest);
        $sheet->setCellValue('G3', $this->refrigeratorRepairReplacement);
        $sheet->setCellValue('G4', $this->refrigeratorUpgrade);
        $sheet->setCellValue('G5', $this->refrigeratorSetup);
        $sheet->setCellValue('G6', $this->refrigeratorCometPolicy);
        $sheet->setCellValue('G7', $this->refrigeratorUserSupport);
        $sheet->setCellValue('G8', $this->refrigeratorRoutine);
        $sheet->setCellValue('G9', $this->refrigeratorSoftware);
        $sheet->setCellValue('G10', $this->refrigeratorSafety);
        $sheet->setCellValue('G11', $this->refrigeratorUpdating);
        $sheet->setCellValue('G12', $this->refrigeratorNewCycle);
        $sheet->setCellValue('G17', $this->refrigeratorTransfer);
        $sheet->setCellValue('G18', $this->completedRefrigerator);

        // Routine Maintenance and Management
        $sheet->mergeCells('A20:H20');
        $sheet->mergeCells('A21:G21');
        $sheet->mergeCells('A22:G22');
        $sheet->mergeCells('A23:G23');
        $sheet->mergeCells('A24:G24');
        $sheet->mergeCells('A25:G25');
        $sheet->mergeCells('A26:G26');
        $sheet->mergeCells('A27:G27');
        $sheet->mergeCells('A28:H28');

        $sheet->setCellValue('A20', 'Routine Maintenance and Management');
        $sheet->setCellValue('A21', '# of Issues Resolved over the phone (Energy)');
        $sheet->setCellValue('A22', '# of Issues Resolved over the phone (Water)');
        $sheet->setCellValue('A23', '# of Issues Resolved over the phone (Internet)');
        //$sheet->setCellValue('A24', '# of Issues Resolved for Refrigerators');
        //$sheet->setCellValue('A25', '# of Replaced Refrigerators');
        // $sheet->setCellValue('A26', '# of Replaced Broken Charge Controller');
        // $sheet->setCellValue('A27', '# of Saftey Checks');

        $sheet->setCellValue('H21', $this->completedEnergyPhone);
        $sheet->setCellValue('H22', $this->completedWaterPhone);
        $sheet->setCellValue('H23', $this->completedInternetPhone);
        //$sheet->setCellValue('J24', $this->completedRefrigerator);
        // $sheet->setCellValue('J6', $this->completedGenerator);
        // $sheet->setCellValue('J7', $this->replacedChrageController);
        // $sheet->setCellValue('J8', $this->safteyChecks);

        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true, 'size' => 12]],
            20  => ['font' => ['bold' => true, 'size' => 12]],
            18  => ['font' => ['bold' => true, 'size' => 12]],
            // 22  => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}