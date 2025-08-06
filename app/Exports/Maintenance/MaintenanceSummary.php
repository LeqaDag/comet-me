<?php

namespace App\Exports\Maintenance;

use App\Models\AllMaintenanceTicket;
use App\Models\AllMaintenanceTicketAction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class MaintenanceSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell, WithEvents
{
    protected $request;

    // this is for all categories
    protected array $categoryKeywords = [
        'Social request' => 'SocialRequest',
        'Repair or Replacement' => 'RepairReplacement',
        'Upgrade' => 'Upgrade',
        'Setup' => 'Setup',
        'Comet policy' => 'CometPolicy',
        'User support' => 'UserSupport',
        'Routine' => 'Routine',
        'Software' => 'Software',
        'Safety' => 'Safety',
        'Updating' => 'Updating',
        'New cycle' => 'NewCycle',
        'PV action' => 'PvActions',
        'Charge batteries' => 'ChargeBatteries',
        'Refill battery water' => 'RefillBatteries',
        'Generator' => 'Generator',
        'Transfer' => 'Transfer',
        'All' => 'all'
    ];

    protected array $serviceNameToKey = [

        'Energy' => 'energy',
        'Refrigerator' => 'refrigerator',
        'Water' => 'water',
        'Internet' => 'internet',
        'All' => 'all'
    ];

    // this for not having a duplicated code
    protected array $services = [
        'energy' => [
            'issue_table' => 'energy_issues',
            'action_table' => 'energy_actions',
            'action_field' => 'energy_action_id',
            'prefix' => 'E',
        ],
        'refrigerator' => [
            'issue_table' => 'refrigerator_issues',
            'action_table' => 'refrigerator_actions',
            'action_field' => 'refrigerator_action_id',
            'prefix' => 'R',
        ],
        'water' => [
            'issue_table' => 'water_issues',
            'action_table' => 'water_actions',
            'action_field' => 'water_action_id',
            'prefix' => 'W',
        ],
        'internet' => [
            'issue_table' => 'internet_issues',
            'action_table' => 'internet_actions',
            'action_field' => 'internet_action_id',
            'prefix' => 'I',
        ],
    ];

    // This for response time 
    protected array $responseTimeBuckets = [
        '# of tickets resolved in under 1 day' => [0, 24],              // 0 to 24 hours
        '# of tickets resolved between 1-3 days' => [24, 72],           // 24 to 72 hours
        '# of tickets resolved between 3 days and 1 week' => [72, 168], // 72 to 168 hours
        '# of tickets resolved between 1 week and 2 weeks' => [168, 336], // 168 to 336 hours
        '# of tickets resolved between 2 weeks and 1 month' => [336, 720], // 336 to 720 hours
        '# of tickets resolved between 1 month and 2 months' => [720, 1440], // 720 to 1440 hours
        '# of tickets resolved between 2 months and 3 months' => [1440, 2160], // 1440 to 2160 hours
        '# of tickets resolved in over 3 months' => [2160, PHP_INT_MAX],    // 2160+ hours
    ];

    // This array is for created_by
    protected array $createdByGroups = [

        'Tickets uploaded by user' => [''],
        'Tickets entered by user support officer' => ['الدعم الفني'],
        'Tickets entered by team' => ['*'],
    ];

    // This array is for resolved method
    protected array $resolvedMethodGroups = [

        'Tickets resolved by visit' => 'Visit',
        'Tickets resolved remotely' => 'Phone',
        'Tickets resolved by chatbot' => 'Chatbot',
    ];

    protected array $responseTimeByCategoryCounts  = [];
    protected array $responseTimeByServiceCounts  = [];
    protected array $createdByServiceCounts = [];
    protected array $resolvedMethodCounts = [];
    protected array $counts = [];

    public function __construct($request) {
        $this->request = $request;

        foreach (array_keys($this->services) as $service) {
            foreach ($this->categoryKeywords as $keyword => $property) {
                $this->counts[$service][$property] = 0;
            }
            $this->counts[$service]['Total'] = 0;
        }
    }

    // Fetch tickets by category
    protected function fetchTicketsAndCountCategories() {

        foreach ($this->services as $service => $config) {

            $tickets = DB::table('all_maintenance_ticket_actions')
                ->join($config['issue_table'], "{$config['issue_table']}.comet_id", '=', 'all_maintenance_ticket_actions.action_id')
                ->join($config['action_table'], "{$config['issue_table']}.{$config['action_field']}", '=', "{$config['action_table']}.id")
                ->join('action_categories', 'action_categories.id', '=', "{$config['action_table']}.action_category_id")
                ->where('all_maintenance_ticket_actions.is_archived', 0)
                ->get();

            foreach ($tickets as $ticket) {

                foreach ($this->categoryKeywords as $keyword => $property) {
                    if (strpos($ticket->english_name, $keyword) !== false) {
                        $this->counts[$service][$property]++;
                    }
                }
            }

            $this->counts[$service]['Total'] = AllMaintenanceTicketAction::where('is_archived', 0)
                ->where('action_id', 'like', '%' . $config['prefix'] . '%')
                ->count();
        }
    }

    // Fetch tickets by routine
    protected function fetchRoutineMaintenanceCounts() {

        $this->counts['routine'] = [
            'Energy' => $this->routineTicketCount(1),
            'Water' => $this->routineTicketCount(2),
            'Internet' => $this->routineTicketCount(3),
        ];

        $this->counts['refrigerator']['Total'] = AllMaintenanceTicketAction::where("is_archived", 0)
            ->where("action_id", "like", "%R%")
            ->count();
    }

    // Get the tickets 
    protected function routineTicketCount(int $serviceTypeId): int {

        return AllMaintenanceTicket::where([
            ['is_archived', 0],
            ['is_duplicated', 0],
            ['maintenance_type_id', 2],
            ['maintenance_status_id', 3],
            ['service_type_id', $serviceTypeId],
        ])->count();
    }

    // Get the tickets by Response time
    protected function fetchResponseTimeCounts() {

        // Initialize counts to zero
        foreach ($this->responseTimeBuckets as $bucketName => $range) {

            foreach ($this->categoryKeywords as $keyword => $property) {

                $this->responseTimeByCategoryCounts[$bucketName][$property] = 0;
            }
            $this->responseTimeByCategoryCounts[$bucketName]['all'] = 0; 
        }

        // Query tickets with joined categories (coalescing category names from different tables)
        $tickets = DB::table('all_maintenance_tickets as t')
            ->join('all_maintenance_ticket_actions as a', 't.id', 'a.all_maintenance_ticket_id')
            ->leftJoin('energy_actions', function ($join) {
                $join->on('a.action_id', 'energy_actions.id');
            })
            ->leftJoin('action_categories as energy_categories', 'energy_categories.id', 'energy_actions.action_category_id')

            ->leftJoin('water_actions', function ($join) {
                $join->on('a.action_id', 'water_actions.id');
            })
            ->leftJoin('action_categories as water_categories', 'water_categories.id', 'water_actions.action_category_id')

            ->leftJoin('refrigerator_actions', function ($join) {
                $join->on('a.action_id', 'refrigerator_actions.id');
            })
            ->leftJoin('action_categories as refrigerator_categories', 'refrigerator_categories.id', 'refrigerator_actions.action_category_id')

            ->leftJoin('internet_actions', function ($join) {
                $join->on('a.action_id', 'internet_actions.id');
            })
            ->leftJoin('action_categories as internet_categories', 'internet_categories.id', 'internet_actions.action_category_id')

            ->where('t.is_archived', 0)
            ->where('t.is_duplicated', 0)

            ->select([
                't.support_created_at',
                't.supported_updated_at',
                DB::raw('COALESCE(
                    energy_categories.english_name,
                    water_categories.english_name,
                    refrigerator_categories.english_name,
                    internet_categories.english_name
                ) as category_name')
            ])
            ->get();

        foreach ($tickets as $ticket) {
            if ($ticket->support_created_at && $ticket->supported_updated_at) {

                // Calculate difference in hours
                $diffHours = (strtotime($ticket->supported_updated_at) - strtotime($ticket->support_created_at)) / 3600;

                // Find the correct response time bucket
                foreach ($this->responseTimeBuckets as $bucketName => [$minHours, $maxHours]) {

                    if ($diffHours >= $minHours && $diffHours <= $maxHours) {

                        $categoryName = $ticket->category_name ?? '';

                        // Match category name to your keywords
                        foreach ($this->categoryKeywords as $keyword => $property) {

                            if (stripos($categoryName, $keyword) !== false) {

                                $this->responseTimeByCategoryCounts[$bucketName][$property]++;
                                $this->responseTimeByCategoryCounts[$bucketName]['all']++;
                                break;
                            }
                        }
                        break; 
                    }
                }
            }
        }

    }

    // Get tickets by service
    protected function fetchResponseTimeByServiceCounts()
    {
        // Initialize counts to zero for all buckets and services
        foreach ($this->responseTimeBuckets as $bucketName => $range) {

            foreach (array_keys($this->services) as $serviceKey) {

                $this->responseTimeByServiceCounts[$bucketName][$serviceKey] = 0;
            }
            $this->responseTimeByServiceCounts[$bucketName]['all'] = 0; 
        }

        $tickets = DB::table('all_maintenance_tickets as t')
            ->join('all_maintenance_ticket_actions as a', 't.id', 'a.all_maintenance_ticket_id')

            ->leftJoin('energy_issues', function ($join) {
                $join->on('a.action_id', 'energy_issues.comet_id');
            })
            ->leftJoin('water_issues', function ($join) {
                $join->on('a.action_id', 'water_issues.comet_id');
            })
            ->leftJoin('refrigerator_issues', function ($join) {
                $join->on('a.action_id', 'refrigerator_issues.comet_id');
            })
            ->leftJoin('internet_issues', function ($join) {
                $join->on('a.action_id', 'internet_issues.comet_id');
            })

            ->where('t.is_archived', 0)
            ->where('t.is_duplicated', 0)

            ->select([
                't.support_created_at',
                't.supported_updated_at',
                DB::raw('
                    CASE
                        WHEN energy_issues.id IS NOT NULL THEN "energy"
                        WHEN water_issues.id IS NOT NULL THEN "water"
                        WHEN refrigerator_issues.id IS NOT NULL THEN "refrigerator"
                        WHEN internet_issues.id IS NOT NULL THEN "internet"
                        ELSE NULL
                    END as detected_service
                ')
            ])
        ->get();


        foreach ($tickets as $ticket) {

            if ($ticket->support_created_at && $ticket->supported_updated_at && $ticket->detected_service) {

                $diffHours = (strtotime($ticket->supported_updated_at) - strtotime($ticket->support_created_at)) / 3600;

                foreach ($this->responseTimeBuckets as $bucketName => [$min, $max]) {

                    if ($diffHours >= $min && $diffHours <= $max) {

                        $serviceKey = $ticket->detected_service;

                        if (isset($this->responseTimeByServiceCounts[$bucketName][$serviceKey])) {

                            $this->responseTimeByServiceCounts[$bucketName][$serviceKey]++;
                            $this->responseTimeByServiceCounts[$bucketName]['all']++;
                        }
                        break;
                    }
                }
            }
        }
    }

    // Get tickets by reated_by
    protected function fetchCreatedByServiceCounts(): void
    {
        // Initialize counts
        $services = array_keys($this->services);
        $methods = array_keys($this->createdByGroups);

        foreach ($methods as $method) {

            foreach ($services as $service) {

                $this->createdByServiceCounts[$method][$service] = 0;
            }
            
            $this->createdByServiceCounts[$method]['all'] = 0;
        }

        // Get all relevant tickets
        $tickets = DB::table('all_maintenance_tickets as t')
            ->join('all_maintenance_ticket_actions as a', 't.id', 'a.all_maintenance_ticket_id')

            ->leftJoin('energy_issues', function ($join) {
                $join->on('a.action_id', 'energy_issues.comet_id');
            })
            ->leftJoin('refrigerator_issues', function ($join) {
                $join->on('a.action_id', 'refrigerator_issues.comet_id');
            })
            ->leftJoin('water_issues', function ($join) {
                $join->on('a.action_id', 'water_issues.comet_id');
            })
            ->leftJoin('internet_issues', function ($join) {
                $join->on('a.action_id', 'internet_issues.comet_id');
            })

            ->where('t.is_archived', 0)
            ->where('t.is_duplicated', 0)

            ->select([
                't.created_by',
                DB::raw('
                    CASE
                        WHEN energy_issues.id IS NOT NULL THEN "energy"
                        WHEN refrigerator_issues.id IS NOT NULL THEN "refrigerator"
                        WHEN water_issues.id IS NOT NULL THEN "water"
                        WHEN internet_issues.id IS NOT NULL THEN "internet"
                        ELSE NULL
                    END as detected_service
                ')
            ])
            ->get();


        foreach ($tickets as $ticket) {

            $createdBy = trim($ticket->created_by ?? '');
            $service = $ticket->detected_service;

            if (!$service) continue;

            $matchedGroup = null;
            foreach ($this->createdByGroups as $label => $values) {
                if (in_array($createdBy, $values)) {
                    $matchedGroup = $label;
                    break;
                }
            }
            if (!$matchedGroup) {
                // Assign to group that has ['*'] as value
                foreach ($this->createdByGroups as $label => $values) {
                    if (in_array('*', $values)) {
                        $matchedGroup = $label;
                        break;
                    }
                }
            }


            $this->createdByServiceCounts[$matchedGroup][$service]++;
            $this->createdByServiceCounts[$matchedGroup]['all']++;
        }
    }

    // Get tickets by reolved method
    protected function fetchResolvedMethodCounts(): void
    {
        // Initialize counts
        $services = array_keys($this->services);
        $methods = array_keys($this->resolvedMethodGroups);

        foreach ($methods as $method) {

            foreach ($services as $service) {

                $this->resolvedMethodCounts[$method][$service] = 0;
            }
            
            $this->resolvedMethodCounts[$method]['all'] = 0;
        }

        // Get all relevant tickets
        $tickets = DB::table('all_maintenance_tickets as t')
            ->join('all_maintenance_ticket_actions as a', 't.id', 'a.all_maintenance_ticket_id')
            ->join('maintenance_types', 't.maintenance_type_id', 'maintenance_types.id')

            ->leftJoin('energy_issues', function ($join) {
                $join->on('a.action_id', 'energy_issues.comet_id');
            })
            ->leftJoin('refrigerator_issues', function ($join) {
                $join->on('a.action_id', 'refrigerator_issues.comet_id');
            })
            ->leftJoin('water_issues', function ($join) {
                $join->on('a.action_id', 'water_issues.comet_id');
            })
            ->leftJoin('internet_issues', function ($join) {
                $join->on('a.action_id', 'internet_issues.comet_id');
            })

            ->where('t.is_archived', 0)
            ->where('t.is_duplicated', 0)

            ->select([
                'maintenance_types.type',
                DB::raw('
                    CASE
                        WHEN energy_issues.id IS NOT NULL THEN "energy"
                        WHEN refrigerator_issues.id IS NOT NULL THEN "refrigerator"
                        WHEN water_issues.id IS NOT NULL THEN "water"
                        WHEN internet_issues.id IS NOT NULL THEN "internet"
                        ELSE NULL
                    END as detected_service
                ')
            ])
            ->get();


        foreach ($tickets as $ticket) {
            $methodType = trim($ticket->type ?? '');
            $service = $ticket->detected_service;

            if (!$service) continue;

            $matchedGroup = null;

            foreach ($this->resolvedMethodGroups as $label => $value) {
                if ($methodType === $value) {
                    $matchedGroup = $label;
                    break;
                }
            }

            if (!$matchedGroup) continue; // Skip if no group matched

            $this->resolvedMethodCounts[$matchedGroup][$service]++;
            $this->resolvedMethodCounts[$matchedGroup]['all']++;
        }
    }

    public function collection() {
        
        //Fetch all counts
        $this->fetchTicketsAndCountCategories();
        $this->fetchRoutineMaintenanceCounts();
        $this->fetchResponseTimeByServiceCounts();
        $this->fetchResponseTimeCounts();
        $this->fetchCreatedByServiceCounts();
        $this->fetchResolvedMethodCounts();

        $routineRow = [];

        return collect([$routineRow]);
    }

    protected function writeResponseTimeSection(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,
        array $arrayBuckets, array $counts, array $services, int $startRow): int {

        $totals = [];

        foreach ($arrayBuckets as $bucketLabel => $_) {

            $sheet->setCellValue("A{$startRow}", "# of {$bucketLabel}");

            foreach ($services as $i => $serviceKey) {

                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 2); // +2 since A is 1
                $value = $counts[$bucketLabel][$serviceKey] ?? 0;
                $sheet->setCellValue("{$colLetter}{$startRow}", $value);

                $totals[$colLetter] = ($totals[$colLetter] ?? 0) + $value;
            }

            
            $startRow++;
        }

        // Write Total row
        $sheet->setCellValue("A{$startRow}", 'Total');
        foreach ($totals as $col => $sum) {

            $sheet->setCellValue("{$col}{$startRow}", $sum);
        }

        // Bold the total row
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($services) + 1);
        $sheet->getStyle("A{$startRow}:{$lastCol}{$startRow}")->getFont()->setBold(true);

        return $startRow + 1;
    }

    public function styles(Worksheet $sheet)
    {
        // Write headers for main categories table
        $sheet->setCellValue('A1', 'Category Name');
        $sheet->setCellValue('B1', 'Energy');
        $sheet->setCellValue('C1', 'Water');
        $sheet->setCellValue('D1', 'Internet');
        $sheet->setCellValue('E1', 'Refrigerator');

        // Map columns to services
        $columns = ['B' => 'energy', 'C' => 'water', 'D' => 'internet', 'E' => 'refrigerator'];

        $row = 2;
        foreach ($this->categoryKeywords as $keyword => $property) {
            $sheet->setCellValue("A{$row}", "# of {$keyword}");

            foreach ($columns as $col => $service) {
                $value = $this->counts[$service][$property] ?? 0;
                $sheet->setCellValue("{$col}{$row}", $value);
            }
            $row++;
        }

        // Totals row (make it bold)
        $sheet->setCellValue("A{$row}", 'Total');
        foreach ($columns as $col => $service) {

            $total = $this->counts[$service]['Total'] ?? 0;
            $sheet->setCellValue("{$col}{$row}", $total);
        }
        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);


        // Response times by service
        $row = 22;
        $this->writeResponseTimeSection($sheet, $this->responseTimeBuckets, $this->responseTimeByServiceCounts, 
            ['energy', 'refrigerator', 'water', 'internet', 'all'], $row);

        // Response times by category
        $row = 33;
        $this->writeResponseTimeSection($sheet, $this->responseTimeBuckets, $this->responseTimeByCategoryCounts,
            array_values($this->categoryKeywords), $row);

        // Ticket upload method
        $row = 45;
        $this->writeResponseTimeSection($sheet, $this->createdByGroups, $this->createdByServiceCounts, 
            ['energy', 'refrigerator', 'water', 'internet', 'all'], $row);

        // Ticket resolved method
        $row = 51;
        $this->writeResponseTimeSection($sheet, $this->resolvedMethodGroups, $this->resolvedMethodCounts, 
            ['energy', 'refrigerator', 'water', 'internet', 'all'], $row);
       
        // Style headers and routine section header
        return [
            1 => ['font' => ['bold' => true]],
            44 => ['font' => ['bold' => true,]],
        ];
    }


    protected function writeResponseTimeMatrix(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet,
        string $title, array $headerMap, array $dataCounts, int $startRow): int {

       // Write section title
        $sheet->setCellValue("A{$startRow}", $title);

        // Write header row
        $colCount = count($headerMap);
        $colIndex = 2;
        foreach ($headerMap as $header => $_) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue("{$col}{$startRow}", $header);
            $colIndex++;
        }

        // Apply styling to header row
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount + 1); // +1 because A=1 is label column
        $headerRange = "A{$startRow}:{$lastCol}{$startRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($headerRange)->getAlignment()->setWrapText(true); 
        $sheet->getRowDimension($startRow)->setRowHeight(-1); 

        // Write data rows
        $row = $startRow + 1;
        foreach ($dataCounts as $bucketName => $counts) {
            // Optional: add bucket name label in column A
            $sheet->setCellValue("A{$row}", $bucketName);

            $colIndex = 2;
            foreach ($headerMap as $_ => $key) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $value = $counts[$key] ?? 0;
                $sheet->setCellValue("{$col}{$row}", $value);
                $colIndex++;
            }
            $row++;
        }

        return $row;
    }


    public function registerEvents(): array {

        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Center headers for main table (your existing code)
                $sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:R1')->getAlignment()->setWrapText(true); 
                $sheet->getRowDimension(32)->setRowHeight(-1);             

                $lastDataRow = count($this->categoryKeywords) + 2;
                for ($row = 2; $row <= $lastDataRow; $row++) {
                    $sheet->getStyle("A{$row}:R{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

    
                $this->writeResponseTimeMatrix(
                    $sheet,
                    'Response Times by service',
                    $this->serviceNameToKey,
                    $this->responseTimeByServiceCounts,
                    21
                );

                $this->writeResponseTimeMatrix(
                    $sheet,
                    'Response Times by category',
                    $this->categoryKeywords,
                    $this->responseTimeByCategoryCounts,
                    32
                );

                $this->writeResponseTimeMatrix(
                    $sheet,
                    'Ticket upload method',
                    $this->serviceNameToKey,
                    $this->createdByServiceCounts,
                    44
                );

                $this->writeResponseTimeMatrix(
                    $sheet,
                    'Tickets resolved by Visit vs. Remote vs. Chatbot',
                    $this->serviceNameToKey,
                    $this->resolvedMethodCounts,
                    50
                );
            }
        ];
    }



    public function title(): string
    {
        return 'Overview';
    }

    public function startCell(): string
    {
        return 'A2';
    }
}