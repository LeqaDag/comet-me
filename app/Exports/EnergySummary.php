<?php

namespace App\Exports;

use App\Models\Community;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergySummary implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('households')
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw("count(households.community_id) AS total_household"))
            ->groupBy('households.community_id')
            ->get();
       
        
        foreach($data as $d) {
            $community = Community::findOrFail($d->id);
            $community->number_of_household = $d->total_household;
            $community->save();
        }

        $households = DB::table('households')
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw("sum(households.number_of_male + households.number_of_female) AS total_people"))
            ->groupBy('households.community_id')
            ->get();

        foreach($households as $household) {
            $community = Community::findOrFail($household->id);
            //$community->number_of_household = NULL;
            $community->number_of_people = $household->total_people;
            $community->save();
        }

        $initalHouseholds = DB::table('households')
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->where('households.household_status_id', 1)
            ->select(
                'households.community_id AS id',
                DB::raw("count(households.community_id) AS initial_survey_number"))
            ->groupBy('households.community_id')
            ->get();

        foreach($initalHouseholds as $initalHousehold) {
            $community = Community::findOrFail($initalHousehold->id);
            $community->initial_survey_number = $initalHousehold->initial_survey_number;
            $community->save();
        }

        $acHouseholds = DB::table('households')
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->where('households.household_status_id', 2)
            ->select(
                'households.community_id AS id',
                DB::raw("count(households.community_id) AS ac_survey_number"))
            ->groupBy('households.community_id')
            ->get();

        foreach($acHouseholds as $acHousehold) {
            $community = Community::findOrFail($acHousehold->id);
            $community->ac_survey_number = $acHousehold->ac_survey_number;
            $community->save();
        }

        $acCompleted = DB::table('all_energy_meters')
            ->join('communities', 'communities.id', '=', 'all_energy_meters.community_id')
            ->where('all_energy_meters.meter_case_id', 12)
            ->select(
                'all_energy_meters.community_id AS id',
                DB::raw("count(all_energy_meters.community_id) AS ac_completed_number"))
            ->groupBy('all_energy_meters.community_id')
            ->get();

        die($acCompleted);
        
        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->LeftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->select([
                'communities.english_name as community_name', 'communities.number_of_household',
                'energy_system_types.name as energy_type_name', 
                'all_energy_meters.installation_date',
               // DB::raw('group_concat(energy_system_types.name) as types'),
            ])
            ->groupBy('communities.id', 'energy_system_types.name');
 
        die($query->get());

        if($this->request->misc) {

            if($this->request->misc == "misc") {

                $query->where("all_energy_meters.misc", 1);
            } else if($this->request->misc == "new") {

                $query->where("all_energy_meters.misc", 0);
            } else if($this->request->misc == "maintenance") {

                $query->where("all_energy_meters.misc", 2);
            }
        }

        if($this->request->date_from) {
            $query->where("all_energy_meters.installation_date", ">=", $this->request->date_from);
        }

        if($this->request->date_to) {
            $query->where("all_energy_meters.installation_date", "<=", $this->request->date_to);
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
        return ["Community", "# of Famailies", "Energy System Type", "Planned Survey",
            "Completed AC", "Remains", "Completed DC", "Delta", "Activation Date"];
    }

    public function title(): string
    {
        return 'Energy Summary';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:R1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}