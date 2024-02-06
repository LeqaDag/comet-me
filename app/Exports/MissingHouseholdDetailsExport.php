<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MissingHouseholdDetailsExport implements WithMultipleSheets, ShouldAutoSize
{
    use Exportable;

    protected $request;
    protected $data;

    function __construct($request, $data) {
        $this->request = $request;
        $this->data = $data;
    }

    /**
     * @return array
     */ 
    public function sheets(): array
    {
        $sheets = [
            new MissingPhoneNumber($this->request, $this->data),
            new MissingAdults($this->request, $this->data),
            new MissingMale($this->request, $this->data),
            new MissingFemale($this->request, $this->data),
            new MissingChildren($this->request, $this->data)
        ];

        return $sheets;
    }
}