<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class EnergyMcbPv implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('energy_systems')
            ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->LeftJoin('energy_system_mcb_pvs', 'energy_systems.id', '=', 
                'energy_system_mcb_pvs.energy_system_id')
            ->LeftJoin('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', '=', 
                'energy_mcb_pvs.id')
            ->where('energy_systems.is_archived', 0)
            ->select(
                'energy_systems.name', 
                'energy_mcb_pvs.model', 'energy_system_mcb_pvs.mcb_pv_units'
            );

       // die($query->get());

        if($this->request->community_id) {

            $query->LeftJoin('communities', 'energy_systems.community_id', '=', 
                'communities.id')
                ->where("energy_systems.community_id", $this->request->community_id);
        }
        if($this->request->energy_type_id) {

            $query->where("energy_systems.energy_system_type_id", $this->request->energy_type_id);
        }
        if($this->request->year_from) {

            $query->where("energy_systems.installation_year", ">=", $this->request->year_from);
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
        return ["Energy System", "MCB PV Model", "MCB PV Units"
        ];
    }

    public function title(): string
    {
        return 'MCB PV';
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
        $sheet->setAutoFilter('A1:C1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}