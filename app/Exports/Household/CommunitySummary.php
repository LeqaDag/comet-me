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
            ->join('communities', 'communities.id', 'households.community_id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->whereNull('communities.energy_system_cycle_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->groupBy('communities.id')
            ->select(
                'communities.english_name', 
                'regions.english_name as region',
                DB::raw('COUNT(households.id) as total_households'),
                'communities.is_surveyed',
                'communities.last_surveyed_date',
                DB::raw('COUNT(DISTINCT CASE 
                    WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND
                    households.number_of_male IS NULL 
                        AND households.number_of_female IS NULL
                        AND households.number_of_children IS NULL 
                        AND households.number_of_adults IS NULL 
                    THEN households.id END) as no_info'),
                        
                DB::raw('COUNT(DISTINCT CASE WHEN
                 (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND
                (households.number_of_male + households.number_of_female) !=
                    (households.number_of_children + households.number_of_adults) AND 
                    (households.number_of_children > 0)
                    THEN households.id END) as discrepancy'),
                     
                DB::raw('COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND (households.number_of_children IS NULL OR households.number_of_children = 0)
                    AND households.number_of_adults IS NOT NULL THEN households.id END) as no_children'),

                DB::raw('(COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND households.number_of_male IS NULL AND households.number_of_female IS NULL
                        AND households.number_of_children IS NULL AND households.number_of_adults IS NULL THEN households.id END)) +
                    (COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND (households.number_of_male + households.number_of_female) !=
                    (households.number_of_children + households.number_of_adults) AND (households.number_of_children > 0) 
                        THEN households.id END)) +
                    (COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND (households.number_of_children IS NULL OR households.number_of_children = 0)
                        AND households.number_of_adults IS NOT NULL THEN households.id END)) as total_issue'),
                    
                DB::raw('( ((COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND households.number_of_male IS NULL AND households.number_of_female IS NULL
                        AND households.number_of_children IS NULL AND households.number_of_adults IS NULL THEN households.id END)) +
                    (COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND (households.number_of_male + households.number_of_female) !=
                    (households.number_of_children + households.number_of_adults) AND (households.number_of_children > 0) 
                        THEN households.id END)) +
                    (COUNT(DISTINCT CASE WHEN (households.is_surveyed IS NULL OR households.is_surveyed = 0)
                    AND (households.number_of_children IS NULL OR households.number_of_children = 0)
                        AND households.number_of_adults IS NOT NULL THEN households.id END)) ) / 
                    (COUNT(households.id)) ) * 100 as percentage'),
            
                'communities.water_service',
                'communities.internet_service'
            )
            ->get();

        $totalHouseholds = $data->sum('total_households');
        $totalNoInfo = $data->sum('no_info');
        $totalDiscrepancy = $data->sum('discrepancy');
        $totalNoChildren = $data->sum('no_children');
        $totalIssued = $data->sum('total_issue');

        $data->push([
            'english_name' => 'Total',
            'region' => '',
            'total_households' => $totalHouseholds,
            'is_surveyed' => '',
            'last_surveyed_date' => '',
            'no_info' => $totalNoInfo,
            'discrepancy' => $totalDiscrepancy,
            'no_children' => $totalNoChildren,
            'total_issue' => $totalIssued,
            'percentage' => ''
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
        return ["Community", "Region", "# of Household", "Is Surveyed", "Last Surveyed Date", "No Information", "Discrepancy", "No Children listed", 
            "Total Households with Issue", "% of Issued Households", "Water Service", "Internet Service"];
    }

    public function title(): string
    {
        return 'Old Communities Summary';
    }


    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:L1');

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A' . $lastRow . ':L' . $lastRow)->applyFromArray([

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