<div id="communitySurveyed" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Communities - Surveyed
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    @if (count($communitiesSurvyed))
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">English Name</th>
                                    <th class="text-center"># of People</th>
                                    <th class="text-center">Region</th>
                                    <th class="text-center">Sub Region</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($communitiesSurvyed as $community)
                                <tr> 
                                    <td class="text-center">
                                        {{ $community->english_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $community->number_of_people }}
                                    </td>
                                    <td class="text-center">
                                        {{ $community->Region->english_name }} 
                                    </td>
                                    <td class="text-center">
                                        {{ $community->SubRegion->english_name }} 
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