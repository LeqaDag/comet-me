<?php

namespace App\Exports\DataCollection\Incidents;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class Choices implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $regions = DB::table('regions')
            ->where('regions.is_archived', 0)
            ->select(
                DB::raw('"region" as list_name'), 
                'regions.english_name as name',
                'regions.english_name as label',
                'regions.english_name as label_en',
                'regions.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community'),
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();

        $sub_regions = DB::table('sub_regions')
            ->join('regions', 'sub_regions.region_id', 'regions.id')
            ->where('sub_regions.is_archived', 0)
            ->select(
                DB::raw('"sub_region" as list_name'), 
                'sub_regions.english_name as name',
                'sub_regions.english_name as label',
                'sub_regions.english_name as label_en',
                'sub_regions.arabic_name as label_ar',
                'regions.english_name as region',
                DB::raw('false as sub_region'),
                DB::raw('false as community'),
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();

        $communities = DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->where('communities.is_archived', 0)
            ->select(
                DB::raw('"community" as list_name'), 
                'communities.english_name as name',
                'communities.english_name as label',
                'communities.english_name as label_en',
                'communities.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                DB::raw('false as community'),
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();

        $mgSystems = DB::table('energy_systems')
            ->join('communities', 'energy_systems.community_id', 'communities.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_systems.energy_system_type_id', '!=', 2)
            ->select(
                DB::raw('"mg_system" as list_name'), 
                'energy_systems.name as name',
                'energy_systems.name as label',
                'energy_systems.name as label_en',
                'energy_systems.name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community',
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();

        $households = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->select(
                DB::raw('"households" as list_name'), 
                'households.comet_id as name',
                'households.english_name as label',
                'households.english_name as label_en',
                'households.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community',
                DB::raw('false as service'),
                'households.energy_system_status as energy',
                'households.water_system_status as water',
                'households.internet_system_status as internet',
            )
            ->get(); 

 

        $waterSystems = DB::table('water_systems')
            ->join('communities', 'communities.id', 'water_systems.community_id')
            ->select(
                DB::raw('"water_system" as list_name'), 
                'water_systems.name as name',
                'water_systems.name as label',
                'water_systems.name as label_en',
                'water_systems.name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community',
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();

        $internetSystems = DB::table('communities')
            ->where('communities.is_archived', 0)
            ->where('communities.internet_service', 'Yes')
            ->select(
                DB::raw('"internet_network" as list_name'), 
                'communities.english_name as name',
                'communities.english_name as label',
                'communities.english_name as label_en',
                'communities.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community'),
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();

        $incidents = DB::table('incidents')
            ->where('incidents.is_archived', 0)
            ->select(
                DB::raw('"incident_type" as list_name'), 
                'incidents.english_name as name',
                'incidents.english_name as label',
                'incidents.english_name as label_en',
                'incidents.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community'),
                DB::raw('false as service'),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();
        
        $equipmentsDamaged = DB::table('incident_equipment')
            ->join('incident_equipment_types', 'incident_equipment_types.id', 'incident_equipment.incident_equipment_type_id')
            ->where('incident_equipment.is_archived', 0)
            ->select(
                DB::raw('"equipments_damaged" as list_name'), 
                'incident_equipment.id as name',
                'incident_equipment.name as label',
                'incident_equipment.name as label_en',
                'incident_equipment.name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community'),
                DB::raw("
                    CASE
                        WHEN incident_equipment_types.name IN ('MG', 'FBS') THEN 'Energy'
                        WHEN incident_equipment_types.name IN ('camera', 'internet') THEN 'Internet'
                        WHEN incident_equipment_types.name = 'Water' THEN 'Water'
                    END as service"),
                DB::raw('false as energy'),
                DB::raw('false as water'),
                DB::raw('false as internet')
            )
            ->get();
        

        $fixedList = [
            // Service type
            [
                'list_name' => 'service_type', 
                'name' => 'Energy',
                'label' => 'Energy',
                'label_en' => 'Energy',
                'label_ar' => 'الكهرباء',
                'community' => false,
            ],
            [
                'list_name' => 'service_type', 
                'name' => 'Water',
                'label' => 'Water',
                'label_en' => 'Water',
                'label_ar' => 'الماء',
                'community' => false,
            ],
            [
                'list_name' => 'service_type', 
                'name' => 'Internet',
                'label' => 'Internet',
                'label_en' => 'Internet',
                'label_ar' => 'الانترنت',
                'community' => false,
            ],

            // Incident type for Energy (system, household, or public)
            [
                'list_name' => 'energy_incident_type', 
                'name' => 'MG System',
                'label' => 'MG System',
                'label_en' => 'MG System',
                'label_ar' => 'أنظمة الكهرباء',
                'community' => false,
            ],
            [
                'list_name' => 'energy_incident_type', 
                'name' => 'Household',
                'label' => 'Household',
                'label_en' => 'Household',
                'label_ar' => 'مستخدم',
                'community' => false,
            ],
            [
                'list_name' => 'energy_incident_type', 
                'name' => 'Public Structure',
                'label' => 'Public Structure',
                'label_en' => 'Public Structure',
                'label_ar' => 'مرافق عامة',
                'community' => false,
            ],

            // Incident type for Water (system, household, or public)
            [
                'list_name' => 'water_incident_type', 
                'name' => 'Water system',
                'label' => 'Water system',
                'label_en' => 'Water system',
                'label_ar' => 'أنظمة الماء',
                'community' => false,
            ],
            [
                'list_name' => 'water_incident_type', 
                'name' => 'Household',
                'label' => 'Household',
                'label_en' => 'Household',
                'label_ar' => 'مستخدم',
                'community' => false,
            ],
            [
                'list_name' => 'water_incident_type', 
                'name' => 'Public Structure',
                'label' => 'Public Structure',
                'label_en' => 'Public Structure',
                'label_ar' => 'مرافق عامة',
                'community' => false,
            ],

            // Incident type for Internet (system, household, or public)
            [
                'list_name' => 'internet_incident_type', 
                'name' => 'Internet Network',
                'label' => 'Internet Network',
                'label_en' => 'Internet Network',
                'label_ar' => 'شبكة الانترنت',
                'community' => false,
            ],
            [
                'list_name' => 'internet_incident_type', 
                'name' => 'Household',
                'label' => 'Household',
                'label_en' => 'Household',
                'label_ar' => 'مستخدم',
                'community' => false,
            ],
            [
                'list_name' => 'internet_incident_type', 
                'name' => 'Public Structure',
                'label' => 'Public Structure',
                'label_en' => 'Public Structure',
                'label_ar' => 'مرافق عامة',
                'community' => false,
            ],
            [
                'list_name' => 'internet_incident_type', 
                'name' => 'Camera',
                'label' => 'Camera',
                'label_en' => 'Camera',
                'label_ar' => 'الكاميرات',
                'community' => false,
            ],

            // Incident statuses
            [
                'list_name' => 'incident_status', 
                'name' => 'replaced',
                'label' => 'System Replaced',
                'label_en' => 'System Replaced',
                'label_ar' => 'تم تبديل النظام',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'not_replaced',
                'label' => 'System Not Replaced',
                'label_en' => 'System Not Replaced',
                'label_ar' => 'لم يتم تبديل النظام',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'connected_other_user',
                'label' => 'Connected to other system/user',
                'label_en' => 'Connected to other system/user',
                'label_ar' => 'متصل بنظام/مستخدم آخر',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'family_left',
                'label' => 'Family Left',
                'label_en' => 'Family Left',
                'label_ar' => 'العائلة رحلت',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'family_stayed',
                'label' => 'Family Stayed',
                'label_en' => 'Family Stayed',
                'label_ar' => 'العائلة لم ترحل',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'house_destroyed',
                'label' => 'House destroyed',
                'label_en' => 'House destroyed',
                'label_ar' => 'تم تدمير البيت',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'in_progress',
                'label' => 'In Progress',
                'label_en' => 'In Progress',
                'label_ar' => 'قيد التنفيذ',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'repaired',
                'label' => 'System Repaired',
                'label_en' => 'System Repaired',
                'label_ar' => 'تم إصلاح النظام',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'not_repaired',
                'label' => 'System Not Repaired',
                'label_en' => 'System Not Repaired',
                'label_ar' => 'لم يتم إصلاح النظام',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'retrieved',
                'label' => 'Retrieved',
                'label_en' => 'Retrieved',
                'label_ar' => 'تم ارجاعه',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'not_retrieved',
                'label' => 'Not Retrieved',
                'label_en' => 'Not Retrieved',
                'label_ar' => 'لم يتم استرجاعه',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'partially_retrieved',
                'label' => 'Partially retrieved',
                'label_en' => 'Partially retrieved',
                'label_ar' => 'تم ارجاعه جزئيا',
                'community' => false,
            ],
            [
                'list_name' => 'incident_status', 
                'name' => 'tot_retrieved_installed_new',
                'label' => 'Not retrieved, installed new',
                'label_en' => 'Not retrieved, installed new',
                'label_ar' => 'لم يتم استرجاعه، تم تثبيت الجديد',
                'community' => false,
            ],
        ];

        $query = collect($communities)
            ->merge($mgSystems)
            ->merge($waterSystems)
            ->merge($households)
            ->merge($incidents)
            ->merge($equipmentsDamaged)
            ->merge($fixedList); 
        
        return $query;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['list_name', 'name', 'label', 'label_en', 'label_ar', 'community', 'service', 
            'energy', 'water', 'internet'];
    }


    public function title(): string
    {
        return 'choices';
    }

    public function startCell(): string
    {
        return 'A1';
    } 


    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:H1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}