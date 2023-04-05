<?php

namespace App\Imports;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EnergyMaintenanceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EnergyUser([
            'household_id' => $row['household_id'],
            'community_id' => $row['community_id'], 
        ]);
    }
}