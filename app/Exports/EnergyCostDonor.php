<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EnergyCostDonor implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function collection()
    {
        $energyDonorCost = DB::table('energy_donor_fund_costs')
            ->join('donors', 'energy_donor_fund_costs.donor_id', 'donors.id')
            ->where('energy_donor_fund_costs.is_archived', 0)
            ->select(
                'donors.donor_name as donor', 'energy_donor_fund_costs.year',
                'energy_donor_fund_costs.fund', 'energy_donor_fund_costs.household',
                'energy_donor_fund_costs.commitment_fund', 'energy_donor_fund_costs.remaining_fund', 
                'energy_donor_fund_costs.commitment_household', 'energy_donor_fund_costs.remaining_household',
                DB::raw('energy_donor_fund_costs.commitment_fund / energy_donor_fund_costs.commitment_household as household_cost')
            )->get();

            // Calculate totals
        $totals = $energyDonorCost->reduce(function ($carry, $item) {
            $carry['fund'] += $item->fund;
            $carry['household'] += $item->household;
            $carry['commitment_fund'] += $item->commitment_fund;
            $carry['remaining_fund'] += $item->remaining_fund;
            $carry['commitment_household'] += $item->commitment_household;
            $carry['remaining_household'] += $item->remaining_household;
            return $carry;
        }, [
            'fund' => 0,
            'household' => 0,
            'commitment_fund' => 0,
            'remaining_fund' => 0,
            'commitment_household' => 0,
            'remaining_household' => 0,
        ]);

        // Append totals as a new row 
        $energyDonorCost->push([
            'donor' => 'Total',
            'year' => '',
            'fund' => $totals['fund'],
            'household' => $totals['household'],
            'commitment_fund' => $totals['commitment_fund'],
            'remaining_fund' => $totals['remaining_fund'],
            'commitment_household' => $totals['commitment_household'],
            'remaining_household' => $totals['remaining_household'],
            'household_cost' => '', 
        ]);

        return $energyDonorCost;
    }

    public function headings(): array
    {
        return ['Donor', 'Year', 'Cost from survey file with donor attributions', 
            '# households', 'Commitment (funds)', 'Remaining (funds)', 'Commitment (households)', 
            'Extra (or remaining) households', 'Cost by Household'];
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function startCell(): string
    {
        return 'B3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                $sheet = $event->sheet->getDelegate();

                // Iterate over all cells containing numeric values and apply number format
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

                // Apply number formatting
                $sheet->getStyle('F2:' . $highestColumn . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('H2:' . $highestColumn . $highestRow)->getNumberFormat()->setFormatCode('#,##0.00');

                // Apply conditional formatting
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell('F' . $row)->getValue();
                    $cellValue1 = $sheet->getCell('H' . $row)->getValue();

                    $conditional = new Conditional();
                    $conditional->setConditionType(Conditional::CONDITION_CELLIS);
                    $conditional->setOperatorType(Conditional::OPERATOR_LESSTHAN);
                    $conditional->addCondition(0);

                    $conditional1 = new Conditional();
                    $conditional1->setConditionType(Conditional::CONDITION_CELLIS);
                    $conditional1->setOperatorType(Conditional::OPERATOR_LESSTHAN);
                    $conditional1->addCondition(0);

                    if ($cellValue < 0) {

                        $conditional->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_RED);
                    } else if ($cellValue > 0){

                        $conditional->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_GREEN);
                    }

                    if ($cellValue1 < 0) {

                        $conditional1->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_RED);
                    } else if ($cellValue1 > 0) {

                        $conditional1->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_GREEN);
                    }

                    $sheet->getStyle('F' . $row)->setConditionalStyles([$conditional]);
                    $sheet->getStyle('H' . $row)->setConditionalStyles([$conditional1]);
                }

                // Apply different formatting for the total row
                foreach ($sheet->getRowIterator() as $row) {
                    $cell = $sheet->getCell('A' . $row->getRowIndex());
                    $value = $cell->getValue();

                    if ($value === 'Total') {
                        $sheet->getStyle('A' . $row->getRowIndex() . ':' . $highestColumn . $row->getRowIndex())->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
                        ]);
                    }
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:I1');

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
