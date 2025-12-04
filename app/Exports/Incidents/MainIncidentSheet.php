<?php
namespace App\Exports\Incidents;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use DB;

class MainIncidentSheet implements WithMultipleSheets
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Generate the sheets for the export.
     *
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $type = $this->request->file_type;

        // Map file types to sheet classes
        $typeMap = [
            'all'        => AllIncidents::class,
            'aggregated' => AllAggregatedIncidents::class,
            'swo'        => AllSWOIncidents::class,
        ];

        // Add the main sheet based on the file type
        if (isset($typeMap[$type])) {
           
            $sheets[] = new $typeMap[$type]($this->request);
        }
  
        // Handle donor sheets only if the file type is 'donor'
        if ($type == 'donor') {

            $donors = DB::table('donors')
                ->join('community_donors', 'community_donors.donor_id', '=', 'donors.id')
                ->join('all_incidents', 'all_incidents.community_id', '=', 'community_donors.community_id')
                ->where('all_incidents.is_archived', 0)
                ->select('donors.id', 'donors.donor_name')
                ->distinct()
                ->get();


            foreach ($donors as $donor) {
                
                $sheets[] = new AllIncidentsByDonor($this->request, $donor->donor_name);
            }
        } 

        return $sheets;
    }


    /**
     * Check if a donor has data associated with it.
     *
     * @param int $donorId
     * @return bool
     */
    private function donorHasData($donorId)
    {
        return DB::table('all_incidents')
            ->join('community_donors', 'community_donors.community_id', '=', 'all_incidents.community_id')
            ->where('all_incidents.is_archived', 0)
            ->where('community_donors.donor_id', $donorId)
            ->exists();
    }
}
