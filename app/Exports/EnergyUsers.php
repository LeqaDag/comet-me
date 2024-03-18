<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use DB;

class EnergyUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('energy_users')
            ->join('all_energy_meters', 'all_energy_meters.id', 'energy_users.all_energy_meter_id')
            ->join('communities', 'energy_users.community_id', 'communities.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->where('energy_users.amount', '>', 0)
            ->whereNotNull('energy_users.payment_date')
            ->select(
                'communities.english_name', 
                'communities.electricity_before',
                'all_energy_meters.installation_date',
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                DB::raw('GROUP_CONCAT(energy_users.meter_number) as meter_numbers'),
                DB::raw('GROUP_CONCAT(DISTINCT DATE(energy_users.payment_date)) as payment_dates'),
                DB::raw('GROUP_CONCAT(energy_users.amount) as amounts')
            )
            ->groupBy('communities.english_name', 'energy_users.meter_number')
            ->get();
        
        $processedData = [];
        foreach ($data as $row) {
            $meterNumbers = explode(',', $row->meter_numbers);
            $paymentDates = explode(',', $row->payment_dates);
            $amounts = explode(',', $row->amounts);
        
            $numEntries = max(count($paymentDates), count($amounts));
            for ($i = 0; $i < $numEntries; $i++) {
                $exportedValue = null;
        
                if ($i === 0) {
                    $exportedValue = $row->exported_value;
        
                    // Check if exported_value is from public_structures and add the condition
                    if ($exportedValue === $row->exported_value && 
                        $row->exported_value === 'public_structures.english_name') {
                        $exportedValueCondition = DB::table('public_structures')
                            ->where('public_structures.english_name', $exportedValue)
                            ->where('public_structures.comet_meter', 0)
                            ->exists();
        
                        if (!$exportedValueCondition) {
                            $exportedValue = null; // Reset exported_value if condition is not met
                        }
                    }
                }
        
                $processedData[] = new Collection([
                    'english_name' => $i === 0 ? $row->english_name : null,
                    'electricity_before' => $i === 0 ? $row->electricity_before : null,
                    'installation_date' => $i === 0 ? $row->installation_date : null,
                    'exported_value' => $exportedValue,
                    'meter_number' => isset($meterNumbers[$i]) ? $meterNumbers[$i] : null,
                    'payment_date' => isset($paymentDates[$i]) ? date('Y-m-d', strtotime($paymentDates[$i])) : null,
                    'amount' => isset($amounts[$i]) ? $amounts[$i] : null,
                ]);
            }
        }
        
        
        return new Collection($processedData);
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community", "Electricity before Comet", "Installation Date", 
            "Holder", "Account Number", "Payment Date", "Amount"];
    }

    public function title(): string
    {
        return 'Data for research paper';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:G1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}
