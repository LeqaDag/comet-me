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
                <form method="POST" enctype='multipart/form-data' id="waterSharedPublicForm"
                    action="{{url('water-public')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id[]" id="communityChanges" 
                                    class="selectpicker form-control" required
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Holder</label>
                                <select name="h2o_public_structure_id" id="selectedPublicHolder" 
                                    class="selectpicker form-control" disabled data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="h2o_public_structure_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Shared Public Structure</label>
                                <select name="public_structure_id" id="selectedPublic" 
                                    class="selectpicker form-control" data-live-search="true" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="public_structure_id_error" style="color: red;"></div>
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

<script>
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();

        $.ajax({
            url: "water-public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                var select = $('#selectedPublicHolder');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });

        $.ajax({
            url: "public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                var select = $('#selectedPublic');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    });

    $(document).ready(function() {

        $('#waterSharedPublicForm').on('submit', function (event) {

            var communityValue = $('#communityChanges').val();
            var publicValue = $('#selectedPublicHolder').val();
            var sharedValue = $('#selectedPublic').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (publicValue == null) {

                $('#h2o_public_structure_id_error').html('Please select a holder!'); 
                return false;
            } else if (publicValue != null){

                $('#h2o_public_structure_id_error').empty();
            }

            if (sharedValue == null) {

                $('#public_structure_id_error').html('Please select a shared Public!'); 
                return false;
            } else if (sharedValue != null){

                $('#public_structure_id_error').empty();
            }


            $(this).addClass('was-validated');  
            $('#public_structure_id_error').empty();  
            $('#h2o_public_structure_id_error').empty();
            $('#community_id_error').empty();

            this.submit();
        });
    });
</script>