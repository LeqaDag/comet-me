<div id="communitiesMgSmgNotDCInstallations" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                Not Yet Completed DC installations (FBS Communities)
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($communitiesMgSmgNotDCInstallations))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">English Name</th>
                                    <th class="text-center"># of Households</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($communitiesMgSmgNotDCInstallations as $community)
                                <tr> 
                                    <td class="text-center">
                                        {{ $community->community }}
                                    </td>
                                    <td class="text-center">
                                        {{ $community->number_of_household }}
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