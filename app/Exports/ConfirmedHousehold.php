<?php

namespace App\Exports;

use App\Models\HouseholdStatus;
use App\Models\PublicStructureStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use \Carbon\Carbon;
use DB;

class ConfirmedHousehold implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $status = "Confirmed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
        $statusPublic = PublicStructureStatus::where('status', 'like', '%' . $status . '%')->first();

        $query = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('household_meters', 'household_meters.household_id', 'households.id')
            ->leftJoin('all_energy_meters as energy_users', 'energy_users.id', 'household_meters.energy_user_id')
            ->leftJoin('meter_cases', 'energy_users.meter_case_id', 'meter_cases.id')
            ->leftJoin('households as main_users', 'energy_users.household_id', 'main_users.id')
            ->leftJoin('household_meters as main_meters', 'main_meters.energy_user_id', 'energy_users.id')
            ->leftJoin('users', 'households.referred_by_id', 'users.id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->where('households.household_status_id', $statusHousehold->id)
            ->select(
                'households.english_name as english_name', 
                'households.arabic_name as arabic_name',
                'communities.english_name as community_name',
                'regions.english_name as region_name',
                DB::raw('CASE 
                        WHEN households.request_date IS NOT NULL THEN households.request_date 
                        ELSE DATE(households.created_at) 
                    END as created_at
                '), 
                'users.name as referred_by', 
                DB::raw("CASE WHEN all_energy_meters.is_main = 'No' THEN 'Served'
                    ELSE 'Service requested' END AS status"),
                'households.number_of_people', 'households.phone_number',
                'energy_system_types.name as type',
                'main_users.english_name as main_holder',
                'energy_users.meter_number', 'meter_cases.meter_case_name_english',
                DB::raw('COUNT(main_meters.id) as energy_user_id_count')
            )
            ->groupBy(
                'households.english_name', 
                'households.arabic_name', 
                'communities.english_name',
                'regions.english_name',
                'households.request_date', 
                'households.created_at',
                'users.name',
                'all_energy_meters.is_main',
                'households.number_of_people',
                'households.phone_number',
                'energy_system_types.name',
                'main_users.english_name',
                'energy_users.meter_number',
                'meter_cases.meter_case_name_english'
            );
 
        $queryPublic = DB::table('public_structures')
            ->join('communities', 'public_structures.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('household_meters', 'household_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('all_energy_meters as energy_users', 'energy_users.id', 'household_meters.energy_user_id')
            ->leftJoin('meter_cases', 'energy_users.meter_case_id', 'meter_cases.id')
            ->leftJoin('households as main_users', 'energy_users.household_id', 'main_users.id')
            //->leftJoin('household_meters as main_meters', 'main_meters.energy_user_id', 'energy_users.id')
            ->leftJoin('users', 'public_structures.referred_by_id', 'users.id')
            ->where('public_structures.is_archived', 0)
            ->where('public_structures.public_structure_status_id', $statusPublic->id)
            ->select(
                'public_structures.english_name as english_name', 
                'public_structures.arabic_name as arabic_name',
                'communities.english_name as community_name',
                'regions.english_name as region_name',
                'public_structures.created_at as created_at',
                'users.name as referred_by', 
                DB::raw("CASE WHEN all_energy_meters.is_main = 'No' THEN 'Served'
                    ELSE 'Service requested' END AS status"),
                DB::raw("false as number_of_people"), 'public_structures.phone_number',
                'energy_system_types.name as type', 
                'main_users.english_name as main_holder',
                'energy_users.meter_number', 'meter_cases.meter_case_name_english',
                DB::raw("false as energy_user_id_count")
                //DB::raw('COUNT(main_meters.id) as energy_user_id_count')
            );

        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
            $queryPublic->where("communities.id", $this->request->community_id);
        }
        if($this->request->status) {

            if($this->request->status == "served") {
                
                $query->where('all_energy_meters.is_main', 'No');
                $queryPublic->where('all_energy_meters.is_main', 'No');
            }
            else if($this->request->status == "service_requested") {

                $query->where(function ($q) {
                    $q->where('all_energy_meters.is_main', '!=', 'No')
                        ->orWhereNull('all_energy_meters.is_main');
                });
                $queryPublic->where(function ($q) {
                    $q->where('all_energy_meters.is_main', '!=', 'No')
                        ->orWhereNull('all_energy_meters.is_main');
                });
            }
        }
        if($this->request->energy_system_type_id) {

            $query->where("energy_system_types.id", $this->request->energy_system_type_id);
            $queryPublic->where("energy_system_types.id", $this->request->energy_system_type_id);
        }
        if($this->request->request_date) {

            $query->whereRaw('DATE(households.created_at) >= ?', [$this->request->request_date])
                ->orWhereRaw('households.request_date >= ?', [$this->request->request_date]);

            $queryPublic->whereRaw('DATE(public_structures.created_at) >= ?', [$this->request->request_date]);
        }

        $data = $query->unionAll($queryPublic)->latest();

        return $query->get();
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Household/Public (English)", "Household/Public (Arabic)", "Community", "Region", "Request Date", 
            "Referred By", "Household/Public Status", "Number of People", "Phone Number", "Energy Type", 
            "Meter Holder", "Meter Number", "Meter Case", "Number of people linked to shared meter"];
    }

    public function title(): string
    { 
        return 'MISC Confirmed Households/Publics';
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
              
                $event->sheet->getDelegate()->freezePane('A2');  
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
        $sheet->setAutoFilter('A1:N1');

        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->getStyle('A1:A' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:A' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('A1:A' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A1:A' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('B1:B' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('B1:B' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('B1:B' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B1:B' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('C1:C' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('F1:F' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('G1:G' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));


        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('J1:J' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('K1:K' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('L1:L' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('M1:M' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('M1:M' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('M1:M' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('M1:M' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('N1:N' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('N1:N' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('N1:N' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('N1:N' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}