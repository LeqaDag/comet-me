<?php

namespace App\Exports;

use App\Models\HouseholdStatus;
use App\Models\PublicStructureStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use \Carbon\Carbon;
use DB;

class AllWorkshopsExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents
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
        $query = DB::table('workshop_communities')
            ->join('communities', 'workshop_communities.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('compounds', 'workshop_communities.compound_id', 'compounds.id')
            ->join('workshop_types', 'workshop_communities.workshop_type_id', 'workshop_types.id')
            ->join('users as lead', 'workshop_communities.lead_by', 'lead.id')
            ->leftJoin('workshop_community_co_trainers', 'workshop_communities.id', 
                'workshop_community_co_trainers.workshop_community_id')
            ->leftJoin('users as co_trainers', 'workshop_community_co_trainers.user_id', 'co_trainers.id')
            ->where('workshop_communities.is_archived', 0)
            ->select(
                'workshop_types.english_name as workshop_type', 'communities.english_name as community', 
                'compounds.english_name as compound', 'regions.english_name as region',
                'workshop_communities.date', 'workshop_communities.number_of_hours', 
                DB::raw('workshop_communities.number_of_male + workshop_communities.number_of_female'), 
                'workshop_communities.number_of_male', 'workshop_communities.number_of_female', 
                'workshop_communities.number_of_youth', 'lead.name as lead_user_name', 
                DB::raw('group_concat(DISTINCT co_trainers.name) as co_trainer'),
                'workshop_communities.lawyer', 'workshop_communities.notes',
                'workshop_communities.stories'
            )
            ->groupBy('workshop_communities.id');
 

        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
        }
        if($this->request->workshop_type_id) {

            $query->where("workshop_types.id", $this->request->workshop_type_id);        
        }
        if($this->request->completed_date) {

            $query->where('workshop_communities.date', ">=", [$this->request->completed_date]);
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
        return ["Workshop Type", "Community", "Compound", "Region", "Worshop Date", "# of Hours", 
            "Total Participants", "# of Male", "# of Female", "# of Adult", "Lead By", 
            "Co-Trainers", "Lawyer", "Notes", "Focus group feedback/stories"];
    }

    public function title(): string
    { 
        return 'All Workshops';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
              
                $event->sheet->getDelegate()->freezePane('A2');  
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:O1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}