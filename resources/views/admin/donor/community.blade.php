<?php
    $communityDonors = \App\Models\CommunityDonor::where('donor_id', $donor->id)->get();
?>
<div id="donorCommunity{{$donor->id}}" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    @if($donor->donor_name == "0") 
                        Not yet attributed
                    @else
                        {{ $donor->donor_name }}
                    @endif Served 
                    <span style="font-size:15px">
                        @if(count($communityDonors)) 
                            {{count($communityDonors)}}
                        @endif
                    </span>
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
           
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($communityDonors))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">English Name</th>
                                    <th class="text-center"># of People</th>
                                    <th class="text-center">Region</th>
                                    <th class="text-center">Service</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($communityDonors as $community)
                                <tr> 
                                    <td class="text-center">
                                        {{ $community->Community->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $community->Community->number_of_people }}
                                    </td>
                                    <td class="text-center">
                                        {{ $community->Community->Region->english_name }} 
                                    </td>
                                    <td class="text-center">
                                        {{ $community->ServiceType->service_name }} 
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>