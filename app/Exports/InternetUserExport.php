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

            if($holder["user_group_name"] == "Comet Employee" ||
                $holder["user_group_name"] == "أبو فلاح	") {
            } else {

                $community = Community::where("arabic_name", $holder["user_group_name"])->first();
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
                $internetUser->is_expire = $holder["is_expire"];
                $internetUser->last_purchase_date = $holder["last_purchase_date"];
                $internetUser->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                $internetUser->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                $internetUser->community_id = $community->id;
                
                $community->internet_service = "Yes";
                $community->save();
                
                if($household) {

                    $existInternetHolder = InternetUser::where("household_id", $household->id)->first();
                    if($existInternetHolder) {

                        $existInternetHolder->is_expire = $holder["is_expire"];
                        $existInternetHolder->active = $holder["active"];
                        $existInternetHolder->last_purchase_date = $holder["last_purchase_date"];
                        $existInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                        $existInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                        $existInternetHolder->save();

                    } else {
                        $household->internet_system_status = "Served";
                        $household->save();
                        $internetUser->household_id = $household->id;
                        $internetUser->save();
                    }
                } else if($public) {

                    $existInternetPublic = InternetUser::where("public_structure_id", $public->id)->first();
                    if($existInternetPublic) {

                        $existInternetPublic->is_expire = $holder["is_expire"];
                        $existInternetPublic->active = $holder["active"];
                        $existInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                        $existInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                        $existInternetPublic->last_purchase_date = $holder["last_purchase_date"];
                        $existInternetPublic->save();
                    } else {
                         
                        $internetUser->public_structure_id = $public->id;
                        $internetUser->save();
                    }
                } else { 

                    if($holder["is_public_entity"] == 0) {

                        $newHousehold = new Household();
                        $newHousehold->arabic_name = $holder["holder_full_name"];
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
            ->select('households.english_name as english_name', 
                'households.arabic_name as arabic_name', 
                'public_structures.english_name as public', 
                'communities.english_name as community_name',
                'internet_clusters.name as cluster_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'internet_users.start_date', 
                DB::raw('CASE WHEN internet_users.paid = 1 THEN "Yes" ELSE "No" END as paid'),
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
        return ["Internet Holder", "Arabic Name", "Public Name", "Community", "Cluster Name", 
            "Region", "Sub Region", "Start Date", "Is Paid", "Is Active", "Is Expire", 
            "Number of Contracts", "Last Purchase Date", "Expired > 30" , 
            "Expired > 60", "Donors"];
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
        $sheet->setAutoFilter('A1:O1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}