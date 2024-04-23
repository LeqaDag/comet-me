<div id="{{$modalWaterIncidentDetailsId}}" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                @if($incidentStatus == 8)
                Not Repaired
                @else @if($incidentStatus == 5)
                Response In Progress
                @else @if($incidentStatus == 1)
                Not Retrieved
                @endif
                @endif
                @endif
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            
            <?php

                $filteredWaterIncidentDetails = $waterIncidentDetails->filter(function ($item) use ($incidentStatus) {
                    return $item->incident_status_id == $incidentStatus;
                });
            ?>

            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($filteredWaterIncidentDetails) > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Water Holder</th>
                                    <th class="text-center">Community</th>
                                    <th class="text-center">Incident</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($filteredWaterIncidentDetails as $missing)
                                <tr> 
                                    <td class="text-center">
                                    {{ $missing->exported_value }}
                                    </td>
                                    <td class="text-center">
                                    {{ $missing->community_name }}
                                    </td>
                                    <td class="text-center">
                                    {{ $missing->incident }}
                                    </td>
                                    <td class="text-center">
                                    {{ $missing->date }}
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