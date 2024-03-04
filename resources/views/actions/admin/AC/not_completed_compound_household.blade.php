<div id="NotCompletedHouseholdsCompound{{$queryCompound->id}}" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                Not Yet Completed Households - (MG/SMG Compounds)
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <?php 

                $holders = DB::table('compounds')
                    ->join('communities', 'communities.id', 'compounds.community_id')
                    ->join('compound_households', 'compound_households.compound_id', 'compounds.id')
                    ->join('households', 'compound_households.household_id', 'households.id')
                    ->where('communities.is_archived', 0)
                    ->where('compound_households.compound_id', $queryCompound->id)
                    ->where('households.household_status_id', 2)
                    ->select(
                        'communities.english_name as community',
                        'compounds.english_name as compound',
                        'households.english_name as holder'
                    )
                    ->get();
            ?>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($holders))
                        <table class="table table-striped"> 
                            <thead>
                                <tr>
                                    <th class="text-center">Holder</th>
                                    <th class="text-center">Compound</th>
                                    <th class="text-center">Community</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($holders as $holder)
                                <tr> 
                                    <td class="text-center">
                                        {{ $holder->holder }}
                                    </td>
                                    <td class="text-center">
                                        {{ $holder->compound }}
                                    </td>
                                    <td class="text-center">
                                        {{ $holder->community }}
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