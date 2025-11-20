<?php

namespace App\Exports\Incidents;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use DB;

class MainIncidentSheet implements WithMultipleSheets
{
    use Exportable;

    protected $request;

    function __construct($request) {
        $this->request = $request;
    }

    public function sheets(): array
    {
        $sheets = [ 
            
            new AllIncidents($this->request),
            //new AllSWOIncidents($this->request),
        ];

        // Get all unique donors with related incidents
        // $donors = DB::table('donors')
        //     ->join('community_donors', 'community_donors.donor_id', 'donors.id')
        //     ->join('all_incidents', 'all_incidents.community_id', 'community_donors.community_id')
        //     ->where('all_incidents.is_archived', 0)
        //     ->select('donors.id', 'donors.donor_name')
        //     ->distinct()
        //     ->get();

        // foreach ($donors as $donor) {
        //     $donorRequest = clone $this->request;
        //     $donorRequest->donor_id = $donor->id;

        //     // Check if the donor has data
        //     $hasData = DB::table('all_incidents')
        //         ->join('community_donors', 'community_donors.community_id', 'all_incidents.community_id')
        //         ->where('all_incidents.is_archived', 0)
        //         ->where('community_donors.donor_id', $donor->id)
        //         ->exists();

        //     if ($hasData) {
        //         $sheets[] = new AllIncidentsByDonor($donorRequest, $donor->donor_name);
        //     }
        // }

        return $sheets;
    }
}
