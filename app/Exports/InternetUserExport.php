<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\User;
use App\Models\Community;
use App\Models\AllEnergyMeter;
use App\Models\CommunityService;
use App\Models\InternetUser;
use App\Models\Household;
use App\Models\PublicStructure;
use Auth;
use DB;   
use Route; 

class InternetUserExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = Http::get('http://185.190.140.86/api/users/');

        $holders = json_decode($data, true) ;

        foreach($holders as $holder) {

            if($holder["user_group_name"] == "Comet Employee") {
            } else {

                $community = Community::where("arabic_name", $holder["user_group_name"])->first();
                if($community) {

                    $existCommunityService = CommunityService::where("community_id", $community->id)
                    ->where("service_id", 3)
                    ->first();

                    if($existCommunityService) {

                    } else {
                        
                        $communityService = new CommunityService();
                        $communityService->service_id = 3;
                        $communityService->community_id = $community->id;
                        $communityService->save();
                    }

                    // create new internet user 
                    $internetUser = new InternetUser();
                    $internetUser->internet_status_id = 1;
                    $internetUser->start_date = $holder["created_on"];
                    $internetUser->active = $holder["active"];
                    $internetUser->last_purchase_date = $holder["last_purchase_date"];
                    $internetUser->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                    $internetUser->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                    $internetUser->is_expire = $holder["is_expire"];
                    $internetUser->paid = $holder["paid"];
                    $internetUser->community_id = $community->id;
                    $internetUser->is_hotspot = $holder["is_hotspot"];
                    $internetUser->is_ppp = $holder["is_ppp"];

                    // main users 
                    if($holder["have_meter"] == 1) {

                        // first step is relaying on the meter number
                        $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                            ->where('meter_number', $holder["meters_list"][0]["sn"])
                            ->first();
        
                        if($allEnergyMeter) {

                            // retrieve the community 
                            $community = Community::findOrFail($allEnergyMeter->community_id);
                            $community->internet_service = "Yes";
                            $community->save();

                            $communityService = new CommunityService();
                            $communityService->service_id = 3;
                            $communityService->community_id = $community->id;
                            $communityService->save();

                            // Check if the meter is for user (new/existing main user)
                            if($allEnergyMeter->household_id != 0 || $allEnergyMeter->household_id != null) {

                                $household = Household::findOrFail($allEnergyMeter->household_id );
                                $household->phone_number = $holder["cardnum"];
                                $household->internet_system_status = "Served";
                                $household->save();

                                $exisiInternetHolder = InternetUser::where('household_id', $allEnergyMeter->household_id)->first();
                                if($exisiInternetHolder) {

                                    $exisiInternetHolder->active = $holder["active"];
                                    $exisiInternetHolder->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetHolder->is_expire = $holder["is_expire"];
                                    $exisiInternetHolder->paid = $holder["paid"];
                                    $exisiInternetHolder->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetHolder->is_ppp = $holder["is_ppp"];
                                    $exisiInternetHolder->save();
                                } else {

                                    $internetUser->household_id = $allEnergyMeter->household_id;
                                    $internetUser->community_id = $allEnergyMeter->community_id;
                                }

                            // new/existing main public 
                            } else if($allEnergyMeter->public_structure_id != 0 || $allEnergyMeter->public_structure_id != null) {

                                $publicStructure = PublicStructure::findOrFail($allEnergyMeter->public_structure_id);
                                $publicStructure->phone_number = $holder["cardnum"];
                                $publicStructure->save();

                                $exisiInternetPublic = InternetUser::where('public_structure_id', $allEnergyMeter->public_structure_id)->first();
                                if($exisiInternetPublic) {

                                    $exisiInternetPublic->active = $holder["active"];
                                    $exisiInternetPublic->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetPublic->is_expire = $holder["is_expire"];
                                    $exisiInternetPublic->paid = $holder["paid"];
                                    $exisiInternetPublic->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetPublic->is_ppp = $holder["is_ppp"];
                                    $exisiInternetPublic->save();
                                } else {

                                    $internetUser->public_structure_id = $allEnergyMeter->public_structure_id;
                                    $internetUser->community_id = $allEnergyMeter->community_id;
                                }
                            }
                        }

                        // should send a message called "you've a new meter number not registering on the DB" 
                        if(!$allEnergyMeter) {

                        } 

                    } else if($holder["have_meter"] == 0) {

                        // shared users / young holders 
                        $household = Household::where("is_archived", 0)
                            ->where("arabic_name", $holder["holder_full_name"])
                            ->first();
                        $public = PublicStructure::where("is_archived", 0)
                            ->where("arabic_name", $holder["holder_full_name"])
                            ->first();
                        
                        $internetUser = new InternetUser();
                        $internetUser->internet_status_id = 1;
                        $internetUser->start_date = $holder["created_on"];
                        $internetUser->active = $holder["active"];
                        $internetUser->last_purchase_date = $holder["last_purchase_date"];
                        $internetUser->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                        $internetUser->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                        $internetUser->is_expire = $holder["is_expire"];
                        $internetUser->paid = $holder["paid"];
                        $internetUser->community_id = $community->id;
                        $internetUser->is_hotspot = $holder["is_hotspot"];
                        $internetUser->is_ppp = $holder["is_ppp"];

                        $community->internet_service = "Yes";
                        $community->save();
                    
                        if($household) {

                            $household->phone_number = $holder["holder_mobile"];
                            $household->save();

                            $existInternetHolder = InternetUser::where("household_id", $household->id)->first();
                            if($existInternetHolder) {

                                $existInternetHolder->is_expire = $holder["is_expire"];
                                $existInternetHolder->active = $holder["active"];
                                $existInternetHolder->paid = $holder["paid"];
                                $existInternetHolder->last_purchase_date = $holder["last_purchase_date"];
                                $existInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                $existInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                $existInternetHolder->is_hotspot = $holder["is_hotspot"];
                                $existInternetHolder->is_ppp = $holder["is_ppp"];
                                $existInternetHolder->save();
                            } else {

                                $household->internet_system_status = "Served";
                                $household->phone_number = $holder["holder_mobile"];
                                $household->save();
                                $internetUser->household_id = $household->id;
                                $internetUser->save();
                            }
                        } else if($public) {

                            $public->phone_number = $holder["holder_mobile"];
                            $public->save();

                            $existInternetPublic = InternetUser::where("public_structure_id", $public->id)->first();
                            if($existInternetPublic) {

                                $existInternetPublic->is_expire = $holder["is_expire"];
                                $existInternetPublic->active = $holder["active"];
                                $existInternetPublic->paid = $holder["paid"];
                                $existInternetPublic->last_purchase_date = $holder["last_purchase_date"];
                                $existInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                $existInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                $existInternetPublic->is_hotspot = $holder["is_hotspot"];
                                $existInternetPublic->is_ppp = $holder["is_ppp"];
                                $existInternetPublic->save();
                            } else {
                                
                                $internetUser->public_structure_id = $public->id;
                                $internetUser->save();
                            }
                        } else { 

                            if($holder["is_public_entity"] == 0 || $holder["is_young"]) {

                                $newHousehold = new Household();
                                $newHousehold->arabic_name = $holder["holder_full_name"];
                                $newHousehold->phone_number = $holder["holder_mobile"];
                                $newHousehold->internet_holder_young = 1;
                                $newHousehold->community_id = $community->id;
                                $newHousehold->internet_system_status = "Served";
                                $newHousehold->profession_id = 1;
                                $newHousehold->save();
            
                                $internetUser->household_id = $newHousehold->id;
                                $internetUser->save();
                            } else if($holder["is_public_entity"] == 1) {

                            }
                        }
                    }
                }
            }
        }

        $data = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('households', 'internet_users.household_id', '=', 'households.id')
            ->leftJoin('public_structures', 'internet_users.public_structure_id', '=', 
                'public_structures.id')
            ->join('internet_statuses', 'internet_users.internet_status_id', '=', 'internet_statuses.id')
            ->LeftJoin('internet_user_donors', 'internet_users.id', 
                '=', 'internet_user_donors.internet_user_id')
            ->LeftJoin('internet_cluster_communities', 'internet_cluster_communities.community_id', 
                'communities.id')
            ->LeftJoin('internet_clusters', 'internet_cluster_communities.internet_cluster_id', 
                'internet_clusters.id')
            ->LeftJoin('donors', 'internet_user_donors.donor_id', '=', 'donors.id')
            ->where('internet_users.is_archived', 0)
            ->select(
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) 
                    as exported_value_arabic'),
                'communities.english_name as community_name',
                DB::raw('IFNULL(households.phone_number, public_structures.phone_number) 
                    as phone_number'),
                'internet_clusters.name as cluster_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'internet_users.start_date', 
                DB::raw('CASE WHEN internet_users.paid = 1 THEN "Yes" ELSE "No" END as paid'),
                DB::raw('CASE 
                    WHEN internet_users.is_hotspot = 1 AND internet_users.is_ppp = 0 THEN "Hotspot" 
                    WHEN internet_users.is_hotspot = 0 AND internet_users.is_ppp = 1 THEN "Broadband" 
                    ELSE NULL END AS system_type'),
                DB::raw('CASE WHEN internet_users.active = 1 THEN "Yes" ELSE "No" END as active'),
                DB::raw('CASE WHEN internet_users.is_expire = 1 THEN "Yes" ELSE "No" END as expire'),
                'internet_users.number_of_contract', 'internet_users.last_purchase_date',
                DB::raw('CASE WHEN internet_users.expired_gt_than_30d = 1 THEN "Yes" 
                    ELSE "No" END as expired_gt_than_30d'),
                DB::raw('CASE WHEN internet_users.expired_gt_than_60d = 1 THEN "Yes" 
                    ELSE "No" END as expired_gt_than_60d'),
                DB::raw('group_concat(donors.donor_name) as donors')
            )->groupBy('internet_users.id'); 

        if($this->request->community) {

            $data->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $data->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->start_date) {
            
            $data->where("internet_users.start_date", ">=", $this->request->start_date);
        } 

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Internet Holder", "Arabic Name", "Community", "Phone Number", "Cluster Name", 
            "Region", "Sub Region", "Start Date", "Is Paid", "System Type", "Is Active", "Is Expire", 
            "Number of Contracts", "Last Purchase Date", "Expired > 30", "Expired > 60", "Donors"];
    }

    public function title(): string
    {
        return 'Internet Users';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:Q1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}