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
class FBSUpgradetoMGUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents
    
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('all_energy_meters')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('communities', 'all_energy_meters.community_id', 'communities.id')
            ->leftJoin('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_type_id', 'energy_systems.id')
            // join household_meters to produce one row per shared holder (hm may be null if no shared holders)
            ->leftJoin('household_meters as hm', function($join) {
                $join->on('hm.energy_user_id', '=', 'all_energy_meters.id')
                     ->where('hm.is_archived', 0);
            })
            ->leftJoin('households as hs2', 'hm.household_id', 'hs2.id')
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.installation_type_id', 7)
            ->select(
                'communities.english_name as community',
                'all_energy_meters.meter_number as meter_number',
                DB::raw('all_energy_meters.id as energy_user_id'),
                DB::raw('COALESCE(households.english_name, households.arabic_name, \'\') as household'),
                // produce one shared-holder per row: shared_household and flag_shared_old_new (hm joined above)
                DB::raw("COALESCE(hs2.english_name, hs2.arabic_name, '') as shared_household"),
                DB::raw("CASE WHEN hm.fbs_upgrade_new = 1 THEN 'New Shared' WHEN hm.fbs_upgrade_new = 0 THEN 'Old Shared' Else '' END as flag_shared_old_new"),
                DB::raw('COALESCE(energy_systems.name, \'\') as system_type'),
                DB::raw("(SELECT GREATEST(1, COUNT(DISTINCT hh_id)) FROM (SELECT all_energy_meters.household_id AS hh_id UNION ALL SELECT household_meters.household_id FROM household_meters WHERE household_meters.energy_user_id = all_energy_meters.id AND household_meters.is_archived = 0) AS hh_count) as total_families_smg"),
                // count of families on SMG that are marked fbs_upgrade_new (includes main household if relevant)
                DB::raw("(SELECT COUNT(DISTINCT hh_id) FROM (SELECT all_energy_meters.household_id AS hh_id UNION ALL SELECT household_meters.household_id FROM household_meters WHERE household_meters.energy_user_id = all_energy_meters.id AND household_meters.is_archived = 0 AND household_meters.fbs_upgrade_new = 1) AS hh_new_count) as new_families_smg"),
                // previous_families_fbs = total_families_smg - new_families_smg
                DB::raw("((SELECT GREATEST(1, COUNT(DISTINCT hh_id)) FROM (SELECT all_energy_meters.household_id AS hh_id UNION ALL SELECT household_meters.household_id FROM household_meters WHERE household_meters.energy_user_id = all_energy_meters.id AND household_meters.is_archived = 0) AS hh_count) - COALESCE((SELECT COUNT(DISTINCT hh_id) FROM (SELECT all_energy_meters.household_id AS hh_id UNION ALL SELECT household_meters.household_id FROM household_meters WHERE household_meters.energy_user_id = all_energy_meters.id AND household_meters.is_archived = 0 AND household_meters.fbs_upgrade_new = 1) AS hh_new_count), 0)) as previous_families_fbs"),
                DB::raw('\'\' as old_donors'),
                DB::raw('\'\' as new_donors')
            )
            ->orderBy('communities.english_name')
            ->orderBy('households.english_name');

        $rows = $query->get();

        // Collapse repeated meter rows: for subsequent rows with the same energy_user_id
        // blank the repeating main columns so community/household aren't duplicated per shared holder.
        $lastEnergyUserId = null;
        foreach ($rows as $row) {
            if ($lastEnergyUserId !== null && $row->energy_user_id == $lastEnergyUserId) {
                $row->community = '';
                $row->meter_number = '';
                $row->household = '';
                $row->system_type = '';
                $row->total_families_smg = '';
                $row->new_families_smg = '';
                $row->previous_families_fbs = '';
                $row->old_donors = '';
                $row->new_donors = '';
            } else {
                $lastEnergyUserId = $row->energy_user_id;
            }
            // remove helper id before export so it doesn't become an extra column
            unset($row->energy_user_id);
        }

        return $rows;
    }

    /**
     * Headings for the sheet
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Community',
            'Meter Number',
            'Household',
            'Shared Households',
            'Flag for shared (old, new)',
            'system Name',
            '# of total families on SMG (with shared)',
            'New # of families on SMG',
            'Previous # of families on FBS',
            'Old Donors',
            'New Donors',
        ];
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'FBS Upgrade to MG/SMG';
    }

    /**
     * Register events such as freezing header row
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $highestCol = $sheet->getHighestColumn();
                $sheet->setAutoFilter('A1:'.$highestCol.'1');

                // Make shared households and flag columns wrap and give them a highlighted background
                $highestRow = $sheet->getHighestRow();
                // Shared column is D and flag is E
                $sharedRange = 'D2:E'.$highestRow;

                // wrap text and top-align
                $sheet->getStyle($sharedRange)->getAlignment()->setWrapText(true)->setVertical(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                );

                // apply a light teal fill to shared household name cells (column D)
                $sheet->getStyle('D2:D'.$highestRow)->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('BEF9F7');

                // set a reasonable width for shared and flag columns
                $sheet->getColumnDimension('D')->setWidth(40);
                $sheet->getColumnDimension('E')->setWidth(20);

                //  groups are rows with the same meter (community non-empty marks group start)
                $groupIndex = 0;
                $applyFill = false;
                for ($r = 2; $r <= $highestRow; $r++) {
                    $communityVal = trim((string)$sheet->getCell('A'.$r)->getValue());
                    if ($communityVal !== '') {
                        // new group starts
                        $groupIndex++;
                        $applyFill = ($groupIndex % 2) === 1; // odd groups get the teal fill
                    }

                    $rowRange = 'A'.$r.':K'.$r;
                    if ($applyFill) {
                        $sheet->getStyle($rowRange)->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C8CFCC');
                    } else {
                        // ensure no fill (white)
                        $sheet->getStyle($rowRange)->getFill()->setFillType(Fill::FILL_NONE);
                    }
                }
            },
        ];
    }

    /**
     * Basic styling for header row and autosize support
     */
    public function styles(Worksheet $sheet)
    {
        // header row style
        $sheet->getStyle('A1:K1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:K1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ADD8E6');

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}