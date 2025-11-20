<?php

namespace App\Imports\Agriculture;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportAgricultureData implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Requested Agriculture' => new ImportRequested(),  
            'shared_user_info' => new ImportSharedUserInfo(), 
        ];
    }
}