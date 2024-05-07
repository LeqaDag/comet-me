<?php

namespace App\Exports\Household;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Carbon\Carbon;

class CommunitySummary implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('households')
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->whereNull('communities.energy_system_cycle_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->groupBy('communities.id')
            ->select(
                'communities.english_name', 
                DB::raw('COUNT(households.id) as total_households'),
                DB::raw('COUNT(DISTINCT CASE 
                    WHEN households.number_of_male IS NULL 
                        AND households.number_of_female IS NULL
                        AND households.number_of_children IS NULL 
                        AND households.number_of_adults IS NULL 
                    THEN households.id END) as no_info'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.number_of_male + households.number_of_female !=
                    households.number_of_children + households.number_of_adults THEN households.id END) as discrepancy'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.number_of_children IS NULL 
                    AND households.number_of_adults IS NOT NULL THEN households.id END) as no_children'),
                'communities.water_service',
                'communities.internet_service'
            )
            ->get();

        // Calculate total sums
        $totalHouseholds = $data->sum('total_households');
        $totalNoInfo = $data->sum('no_info');
        $totalDiscrepancy = $data->sum('discrepancy');
        $totalNoChildren = $data->sum('no_children');

        $data->push([
            'english_name' => 'Total',
            'total_households' => $totalHouseholds,
            'no_info' => $totalNoInfo,
            'discrepancy' => $totalDiscrepancy,
            'no_children' => $totalNoChildren,
        ]);

        return $data;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community", "# of Household", "No Information", "Discrepancy", "No Children listed", 
            "Water Service", "Internet Service"];
    }

    public function title(): string
    {
        return 'Communities Summary (not including new communities)';
    }


    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:G1');

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A' . $lastRow . ':E' . $lastRow)->applyFromArray([

            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '333333']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}