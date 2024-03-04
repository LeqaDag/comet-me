<div id="missingSchoolDetails" class="modal fade" aria-hidden="true" 
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
                    @if (count($missingSchoolDetails))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Public "School"</th>
                                    <th class="text-center">Community</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($missingSchoolDetails as $missingSchoolDetails)
                                <tr> 
                                    <td class="text-center">
                                        {{ $missingSchoolDetails->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $missingSchoolDetails->community }}
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