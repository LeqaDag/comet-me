<div id="missingYesInWaterServiceForCommunity" class="modal fade" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5">
                Need to Update Water Service to "Yes"
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($communityWaterService))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Community Name</th>
                                    <th class="text-center"># of People</th>
                                    <th class="text-center"># of Households</th>
                                    <th class="text-center">Water Service</th>
                                    <th class="text-center">Water Year</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($communityWaterService as $communityWater)
                                <tr> 
                                    <td class="text-center">
                                        {{ $communityWater->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $communityWater->number_of_people }}
                                    </td>
                                    <td class="text-center">
                                        {{ $communityWater->number_of_household }}
                                    </td>
                                    <td class="text-center">
                                        {{ $communityWater->water_service }}
                                    </td>
                                    <td class="text-center">
                                        {{ $communityWater->water_service_beginning_year }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>