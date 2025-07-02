<div id="affectedHouseholdModal" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">
                    Affected Households - Contract Holders
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($allInternetIncident->affectedHouseholds))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Community</th>
                                    <th class="text-center"># of Male</th>
                                    <th class="text-center"># of Female</th>
                                    <th class="text-center"># of Adults</th>
                                    <th class="text-center"># of Children</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($allInternetIncident->affectedHouseholds as $affectedHousehold)
                                <tr> 
                                    <td class="text-center">
                                        {{ $affectedHousehold->Household->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $affectedHousehold->Household->Community->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $affectedHousehold->Household->number_of_male }}
                                    </td>
                                    <td class="text-center">
                                        {{ $affectedHousehold->Household->number_of_female }}
                                    </td>
                                    <td class="text-center">
                                        {{ $affectedHousehold->Household->number_of_adults }}
                                    </td>
                                    <td class="text-center">
                                        {{ $affectedHousehold->Household->number_of_children }}
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