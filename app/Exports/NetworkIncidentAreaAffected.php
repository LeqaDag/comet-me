<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class NetworkIncidentAreaAffected implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles
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
        $query = DB::table('internet_network_affected_areas')
            ->join('communities', 'internet_network_affected_areas.affected_community_id', 
                'communities.id')
            ->join('internet_network_incidents', 'internet_network_incidents.id',
                'internet_network_affected_areas.internet_network_incident_id') 
            ->join('internet_users', 'internet_users.community_id', '=', 'communities.id')
            ->leftJoin('internet_network_affected_households', 
                'internet_network_affected_households.internet_network_incident_id', 
                'internet_network_affected_areas.internet_network_incident_id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('incidents', 'internet_network_incidents.incident_id', '=', 'incidents.id')
            ->select(
                'communities.english_name as community_name',
                'regions.english_name as region', 
                'incidents.english_name as incident',
                'communities.number_of_household',
                DB::raw('count(DISTINCT internet_users.id) as internet_users'),
               // DB::raw('count(DISTINCT internet_network_affected_households.internet_network_incident_id) as affected')
            )
            ->groupBy('internet_network_affected_areas.id');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
                ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
                ->where('community_donors.service_id', 3)
                ->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("internet_network_incidents.date", ">=", $this->request->date);
        }

        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community", "Region", "Incident", "# of Households", "# of Contract Holders"];
    }

    public function title(): string
    {
        return 'Area Affected';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:E1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}