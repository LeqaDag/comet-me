<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>

<div id="createWaterLogframe" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add New Water Logframe
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="waterLogframeForm"
                    action="{{url('water-log')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System</label>
                                <select name="water_system_id" data-live-search="true"
                                    class="selectpicker form-control" id="waterSystemSelected">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($waterSystems as $waterSystem)
                                        <option value="{{$waterSystem->id}}">
                                            {{$waterSystem->name}}
                                        </option>
                                    @endforeach
                                </select> 
                            </fieldset>
                            <div id="water_system_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Test Date</label>
                                <input type="date" name="test_date" class="form-control" required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Leakage</label>
                                <select name="leakage" class="selectpicker form-control" data-live-search="true"
                                    id="leakage">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </fieldset>
                            <div id="leakage_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Reachability</label>
                                <input type="text" name="reachability" class="form-control" required>
                            </fieldset>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Free Chlorine "PPM"</label>
                                <input type="text" name="free_chlorine" 
                                class="form-control">
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>PH</label>
                                <input type="text" name="ph" 
                                    class="form-control">
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Electrical Conductivity - EC - (MC)</label>
                                <input type="text" name="ec" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Reading (m3)</label>
                                <input type="text" name="meter_reading" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Daily Avg Cluster Consumption (m3/cluster)</label>
                                <input type="number" name="daily_avg_cluster_consumption" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Daily Avg Capita Consumption (L/day)</label>
                                <input type="number" name="daily_avg_capita_consumption" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
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

        $('#waterLogframeForm').on('submit', function (event) {

            var waterSystemValue = $('#waterSystemSelected').val();
            var leakage = $('#leakage').val();

            if (waterSystemValue == null) {

                $('#water_system_id_error').html('Please select a water system!');
                return false;
            } else {

                $('#water_system_id_error').empty();
            }

            if (leakage == null) {

                $('#leakage_error').html('Please select a leakage!'); 
                return false;
            } else if (leakage != null){

                $('#leakage_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#water_system_id_error').empty();
            $('#leakage_error').empty();

            this.submit();
        });
    });
</script>