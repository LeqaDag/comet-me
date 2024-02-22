<div id="notYetCompletedSafteyCheckedMg" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                Not Yet Completed Saftey Check (MG)
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($notYetCompletedSafteyCheckedMg))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Holder</th>
                                    <th class="text-center">Community</th>
                                    <th class="text-center">Energy System</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($notYetCompletedSafteyCheckedMg as $holder)
                                <tr> 
                                    <td class="text-center">
                                        {{ $holder->holder }}
                                    </td>
                                    <td class="text-center">
                                        {{ $holder->community }}
                                    </td>
                                    <td class="text-center">
                                        {{ $holder->energy_system }}
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