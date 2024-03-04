<div id="missingRepresentativesInCommunity" class="modal fade" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5">
                Community Missing Representatives
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($missingCommunityRepresentatives))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Community Name</th>
                                    <th class="text-center"># of People</th>
                                    <th class="text-center"># of Households</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($missingCommunityRepresentatives as $missingCommunityRepresentative)
                                <tr> 
                                    <td class="text-center">
                                        {{ $missingCommunityRepresentative->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $missingCommunityRepresentative->number_of_people }}
                                    </td>
                                    <td class="text-center">
                                        {{ $missingCommunityRepresentative->number_of_household }}
                                    </td>
                                    <td class="text-center">
                                       
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