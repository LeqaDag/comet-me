<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 

<div id="createGeneratorEnergy" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Energy Generator Community	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="energyGeneratorForm"
                    action="{{url('energy-generator-turbine')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Generator Name</label>
                                <input type="text" name="name" class="form-control"
                                required> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" data-live-search="true"
                                    class="selectpicker form-control" required
                                    id="energyCommunityValue">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Generator Type</label>
                                <select name="energy_generator_id" data-live-search="true"
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($generators as $generator)
                                    <option value="{{$generator->id}}">
                                        {{$generator->generator_model}}
                                    </option>
                                    @endforeach
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

<script>

    $(document).ready(function() {

        $('#energyGeneratorForm').on('submit', function (event) {

            var communityValue = $('#energyCommunityValue').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            $(this).addClass('was-validated'); 
            $('#community_id_error').empty();
            
            this.submit();
        });
    });
</script>