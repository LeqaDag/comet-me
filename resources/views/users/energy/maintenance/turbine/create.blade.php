<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 

<div id="createTurbineEnergy" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Energy Turbine Community	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="energyTurbineForm"
                    action="{{url('energy-turbine')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Turbine Name</label>
                                <input type="text" name="name" class="form-control"
                                required> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" data-live-search="true"
                                    class="selectpicker form-control" required
                                    id="energyTurbineCommunityValue">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_turbine_id_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Turbine Type</label>
                                <select name="energy_turbine_id" data-live-search="true"
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($turbines as $turbine)
                                    <option value="{{$turbine->id}}">
                                        {{$turbine->model}}
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

        $('#energyTurbineForm').on('submit', function (event) {

            var communityValue = $('#energyTurbineCommunityValue').val();

            if (communityValue == null) {

                $('#community_turbine_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_turbine_id_error').empty();
            }

            $(this).addClass('was-validated'); 
            $('#community_turbine_id_error').empty();
            
            this.submit();
        });
    });
</script>