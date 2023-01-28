<div id="communityMap{{$community->id}}" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    {{$community->english_name}} Location
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    {!!html_entity_decode($community->location_gis)!!}
                </div>
            </div>
        </div>
    </div>
</div>