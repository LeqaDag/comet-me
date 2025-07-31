<?php

namespace App\Exports\Maintenance;

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

abstract class BaseServiceSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell, WithEvents, WithHeadings
{
    protected $request;
    protected $allCategories = [];
    protected $pivotedCollection = null;

    // Child classes must set these:
    protected string $issueTable;
    protected string $issueCometIdColumn;
    protected string $actionTable;
    protected string $actionIdColumn;
    protected string $categoryAlias;
    protected string $titleName;

    public function __construct($request) {
        $this->request = $request;
    }

    protected function fetchTicketsAndCountCategories()
    {
        if ($this->pivotedCollection !== null) {
            return $this->pivotedCollection;
        }

        $tickets = DB::table('all_maintenance_tickets as t')
            ->join('all_maintenance_ticket_actions as a', 't.id', 'a.all_maintenance_ticket_id')
            ->leftJoin($this->issueTable, "{$this->issueTable}.{$this->issueCometIdColumn}", 'a.action_id')
            ->leftJoin($this->actionTable, "{$this->issueTable}.{$this->actionIdColumn}", "{$this->actionTable}.id")
            ->leftJoin('action_categories as categories', 'categories.id', "{$this->actionTable}.action_category_id")
            ->where('t.is_archived', 0)
            ->where('t.is_duplicated', 0)
            ->whereNotNull('categories.english_name')
            ->select([
                DB::raw("{$this->actionTable}.english_name as action"),
                DB::raw("{$this->issueTable}.english_name as issue"),
                DB::raw('categories.english_name as category'),
                DB::raw('COUNT(*) as total')
            ])
            ->groupBy('action', 'issue', 'category')
            ->get();

        $pivoted = [];
        $categoryTotals = [];

        foreach ($tickets as $ticket) {
            if (empty($ticket->category)) {
                continue;
            }

            $key = "{$ticket->action} - {$ticket->issue}";

            if (!isset($pivoted[$key])) {
                $pivoted[$key] = [
                    'Action Issue' => $key,
                    'Total' => 0
                ];
            }

            $pivoted[$key][$ticket->category] = $ticket->total;
            $pivoted[$key]['Total'] += $ticket->total;

            if (!in_array($ticket->category, $this->allCategories)) {
                $this->allCategories[] = $ticket->category;
                $categoryTotals[$ticket->category] = 0;
            }

            $categoryTotals[$ticket->category] += $ticket->total;
        }

        sort($this->allCategories);

        $finalRows = [];

        foreach ($pivoted as $row) {
            foreach ($this->allCategories as $cat) {
                if (!isset($row[$cat])) {
                    $row[$cat] = 0;
                }
            }
            $ordered = ['Action Issue' => $row['Action Issue']];
            foreach ($this->allCategories as $cat) {
                $ordered[$cat] = $row[$cat];
            }
            $ordered['Total'] = $row['Total'];
            $finalRows[] = $ordered;
        }

        // Total row
        $totalRow = ['Action Issue' => 'TOTAL'];
        $grandTotal = 0;

        foreach ($this->allCategories as $cat) {
            $columnTotal = array_reduce($finalRows, function ($carry, $row) use ($cat) {
                return $carry + ($row[$cat] ?? 0);
            }, 0);
            $totalRow[$cat] = $columnTotal;
            $grandTotal += $columnTotal;
        }

        $totalRow['Total'] = $grandTotal;
        $finalRows[] = $totalRow;

        $this->pivotedCollection = collect($finalRows);

        return $this->pivotedCollection;
    }

    public function collection()
    {
        return $this->fetchTicketsAndCountCategories();
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = chr(65 + count($this->allCategories) + 1);
        $sheet->setAutoFilter("A1:{$lastColumn}1");

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A{$lastRow}:{$lastColumn}{$lastRow}")
                    ->getFont()->setBold(true);

                $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A1:{$lastColumn}1")->getAlignment()->setWrapText(true);
            }
        ];
    }

    public function headings(): array
    {
        // Make sure categories are loaded
        $this->fetchTicketsAndCountCategories();
        return array_merge(['Action Issue'], $this->allCategories, ['Total']);
    }

    public function title(): string
    {
        return $this->titleName ?? 'Service Summary';
    }

    public function startCell(): string
    {
        return 'A1';
    }
}
