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

<div id="createWaterPublic" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Water Public Facility
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
                                <select name="community_id" id="communityChanges" 
                                    class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structure</label>
                                <select name="public_structure_id" id="selectedPublic" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Meter?</label>
                                <input type="text" name="meter_public" id="meter_public"
                                    class="form-control" disabled>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Grid Access</label>
                                <select name="grid_access" id="selectedGridAccess" 
                                    class="form-control" disabled>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>H2O System</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of H2O</label>
                                <input type="number" name="number_of_h20" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>H2O Status</label>
                                <select name="h2o_status_id" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($h2oStatus as $h2oStatus)
                                    <option value="{{$h2oStatus->id}}">{{$h2oStatus->status}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of BSF</label>
                                <input type="number" name="number_of_bsf" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>BSF Status</label>
                                <select name="bsf_status_id" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($bsfStatus as $bsfStatu)
                                    <option value="{{$bsfStatu->id}}">{{$bsfStatu->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>H2O Request Date</label>
                                <input type="date" name="h2o_request_date" 
                                    class="form-control">
                            </fieldset>
                        </div> 

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Year</label>
                                <input type="number" name="installation_year" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Grid System</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Request Date</label>
                                <input type="date" name="request_date" 
                                    class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Large</label>
                                <input type="number" name="grid_integration_large" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Large Date</label>
                                <input type="date" name="large_date" 
                                    class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Small</label>
                                <input type="number" name="grid_integration_small" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Small Date</label>
                                <input type="date" name="small_date" 
                                    class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Confirmation</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Delivery</label>
                                <select name="is_delivery" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Paid</label>
                                <select name="is_paid" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="NA">NA</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Complete</label>
                                <select name="is_complete" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
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
    
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "water-user/get_water_source/" + community_id,
            method: 'GET',
            success: function(data) {
                if(data.val == "Yes") {
                    $('#selectedGridAccess').prop('disabled', false);
                    $('#selectedGridAccess').html(data.html);

                } else if(data.val == "New") {
                    $('#selectedGridAccess').prop('disabled', false);
                    $('#selectedGridAccess').html(data.html);
                }
                
            }
        });

        // Get public structures from communtiy
        $.ajax({
            url: "energy-public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedPublic').prop('disabled', false);
                $('#selectedPublic').html(data.html);
            }
        });

        $(document).on('change', '#selectedPublic', function () {
            public_id = $(this).val();

            $.ajax({
                url: "energy_public/get_by_public/" + public_id,
                method: 'GET',
                success: function(data) {

                    $('#meter_public').val(data.meter_number);
                }
            });
        });

    });

</script>