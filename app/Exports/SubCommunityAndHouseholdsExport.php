<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;

class SubCommunityAndHouseholdsExport implements WithMultipleSheets 
{
    use Exportable;

    protected $request;

    function __construct($request) {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new SubCommunityExport($this->request),
            new SubCommunityHouseholdExport($this->request)
        ];

        return $sheets;
    }
}