<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\AllEnergyMeter;
use App\Models\AllEnergyPurchaseMeter;
use Carbon\Carbon;

class PurchaseEnergyImport1 implements ToCollection, WithHeadingRow
{
    protected $type;

    function __construct($type)
    {
        $this->type = $type;
    }

    public function collection(Collection $rows)
    {
        if ($this->type == 1) {
            $this->processFirstFile($rows);
        }

        // Add other file types here if needed
    }

    private function parseDate($date)
    {
        if (!$date || $date === '0000-00-00') {
            return null;
        }

        try {
            if (is_numeric($date)) {
                return Carbon::createFromDate(1900, 1, 1)->addDays($date - 2)->format('Y-m-d');
            }

            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }


    private function processFirstFile(Collection $rows)
    {
        foreach ($rows as $row) {
            $excelDate = $this->parseDate($row["date"]);
        
            if (!$excelDate || !isset($row["meter_number"])) continue;
        
            $record = AllEnergyPurchaseMeter::where('meter_number', $row["meter_number"])->first();
        
            if ($record) {
                if ($record->purchase_date1 == $excelDate) {
                    $record->payment1 = $row["total_amount"];
                } elseif ($record->purchase_date2 == $excelDate) {
                    $record->payment2 = $row["total_amount"];
                } elseif ($record->purchase_date3 == $excelDate) {
                    $record->payment3 = $row["total_amount"];
                }
        
                $record->save();
            }
        }
        







        // $grouped = $rows->groupBy('meter_number');

        // foreach ($grouped as $meterNumber => $entries) {
        //     // First, parse and attach a valid Carbon date object
        //     // $parsedEntries = $entries->map(function ($item) {
        //     //     $item['parsed_date'] = $this->parseDate($item['last_date'] ?? null);
        //     //     return $item;
        //     // })->filter(function ($item) {
        //     //     return $item['parsed_date'] !== null;
        //     // });

        //     // Then sort by parsed_date descending
        //     // $sorted = $parsedEntries->sortByDesc(function ($item) {
        //     //     return $item['parsed_date'];
        //     // })->values();

        //     // Proceed if we have a valid meter
        //     $allEnergyMeter = AllEnergyMeter::where('meter_number', $meterNumber)->first();

        //     if ($allEnergyMeter) {
        //         $meterPurchase = new AllEnergyPurchaseMeter();
        //         $meterPurchase->meter_number = $meterNumber;
        //         $meterPurchase->all_energy_meter_id = $allEnergyMeter->id;

        //         // $meterPurchase->purchase_date1 = $sorted[0]['parsed_date'] ?? null;
        //         // $meterPurchase->purchase_date2 = $sorted[1]['parsed_date'] ?? null;
        //         // $meterPurchase->purchase_date3 = $sorted[2]['parsed_date'] ?? null;

        //         $meterPurchase->save();
        //     }
        // }
    }
}
