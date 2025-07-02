<div id="affectedCommunitModal" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">
                    Affected Areas - Communities
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($allInternetIncident->affectedAreas))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Community Name</th>
                                    <th class="text-center">Region</th>
                                    <th class="text-center"># of Households</th>
                                    <th class="text-center"># of Contract Holders</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($allInternetIncident->affectedAreas as $affectedArea)
                                <tr> 
                                    <td class="text-center">
                                        {{ $affectedArea->AffectedCommunity->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $affectedArea->AffectedCommunity->Region->english_name }}
                                    </td>
                                    <td class="text-center">
                                    <?php
                                        $householdNumber = App\Models\Household::where('is_archived', 0)
                                            ->where('community_id', $affectedArea->affected_community_id)->count();
                                    ?>
                                        {{$householdNumber}}
                                    </td>
                                    <td class="text-center">
                                    <?php
                                        $contractHolders = App\Models\InternetUser::where('is_archived', 0)
                                            ->where('community_id', $affectedArea->affected_community_id)->count();
                                    ?>
                                        {{$contractHolders}}
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