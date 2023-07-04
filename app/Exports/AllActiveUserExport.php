<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class AllActiveUserExport implements WithMultipleSheets, ShouldAutoSize
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
            new ActiveEnergyUsers($this->request),
           // new ActiveSharedUsers($this->request),
            new ActiveWaterUsers($this->request),
            new ActiveInternetUsers($this->request)
        ];

        return $sheets;
    }
}