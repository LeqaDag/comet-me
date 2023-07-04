<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}

.headingLabel {
    font-size:18px;
    font-weight: bold;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<div id="createWaterSharedPublic" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Shared H2O Public Facility	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('water-public')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id[]" id="communityChanges" 
                                    class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Holder</label>
                                <select name="h2o_public_structure_id" id="selectedPublicHolder" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Shared Public Structure</label>
                                <select name="public_structure_id" id="selectedPublic" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();

        $.ajax({
            url: "water-public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedPublicHolder').prop('disabled', false);
                $('#selectedPublicHolder').html(data.html);
            }
        });

        $.ajax({
            url: "public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedPublic').prop('disabled', false);
                $('#selectedPublic').html(data.html);
            }
        });
    });
</script>