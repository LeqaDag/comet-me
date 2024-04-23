<div id="viewMissingNewChildren" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                Missing # of Children For New Households
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($missingChildrenNewHouseholds) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Community</th>
                                </tr>
                            </thead> 
                            <tbody>
                            @foreach($missingChildrenNewHouseholds as $missing)
                                <tr> 
                                    <td class="text-center">
                                    {{ $missing->household_name }}
                                    </td>
                                    <td class="text-center">
                                    {{ $missing->community }}
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