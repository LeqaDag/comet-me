<?php

namespace App\Imports\Agriculture;

use App\Models\Household;
use App\Models\AgricultureSharedHolder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportSharedUserInfo implements ToModel, WithHeadingRow
{
    
    public function model(array $row)
    {
        $index = $row['index'] ?? null;
        $holderId = ImportContext::getHolderId($index);

        if (!$index) {
            \Log::warning('Shared row missing index', $row);
            return null;
        }

        if (!$holderId) {
            \Log::warning("No holder found for index {$index}");
            return null;
        }

        $household = Household::where('comet_id', $row["shared_user_name"])->first();

        if (!$household) {
            \Log::warning("Household not found for shared user: {$row["shared_user_name"]}");
            return null;
        }

        $shared = new AgricultureSharedHolder();
        $shared->household_id = $household->id;
        $shared->size_of_herds = $row["shared_user_herds"];
        $shared->agriculture_holder_id = $holderId;
        $shared->save();

        return $shared;
    }

}
