<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
    
    .dropdown-toggle {
        height: 40px;
    }
</style>

<div id="linkYoungHolder" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Link the Young Holder to the Main User
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="linkYoungHolderForm">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" data-live-search="true" 
                                    name="community_id" required data-parsley-required="true"
                                    id="communitySelectedYoung">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select> 
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Young Holder</label>
                                <select name="household_id" id="selectedYoungHousehold" disabled
                                    class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="household_id_error" style="color: red;"></div>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Main Holder</label>
                                <select name="all_energy_meter_id" id="selectedMainUser" disabled
                                    class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="all_energy_meter_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Number (for main holder)</label>
                                <input type="text" id="meterNumberMainUser" class="form-control" disabled>
                            </fieldset>
                            <div id="all_energy_meter_id_error" style="color: red;"></div>
                        </div>
                    </div>
<br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
       
        $(document).on('change', '#communitySelectedYoung', function () {

            id = $('#communitySelectedYoung').val();
            
            $.ajax({
                url: "/young-holder/community/" +  id,
                method: 'GET',
                success: function(data) {

                    var select = $('#selectedYoungHousehold');
                    select.prop('disabled', false); 
                    select.html(data.young);
                    select.selectpicker('refresh');

                    var selectMain = $('#selectedMainUser');
                    selectMain.prop('disabled', false); 
                    selectMain.html(data.mainUsers);
                    selectMain.selectpicker('refresh');
                }
            });
        });

        $(document).on('change', '#selectedMainUser', function () {

            id = $('#selectedMainUser').val();

            $.ajax({
                url: "/young-holder/main_user/" +  id,
                method: 'GET',
                success: function(data) {

                    $('#meterNumberMainUser').html();
                    $('#meterNumberMainUser').val(data.meterNumber["meter_number"]);
                }
            });
        });

        $('#linkYoungHolderForm').on('submit', function (event) {
       
            var communityValue = $('#communitySelectedYoung').val();
            var youngValue = $('#selectedYoungHousehold').val();
            var mainValue = $('#selectedMainUser').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
            return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (youngValue == null) {

                $('#household_id_error').html('Please select a young holder!'); 
                return false;
            } else if (youngValue != null){

                $('#household_id_error').empty();
            }

            if (mainValue == null) {

                $('#all_energy_meter_id_error').html('Please select a main user!'); 
                return false;
            } else if (mainValue != null){

                $('#all_energy_meter_id_error').empty();
            }
            
                
            $(this).addClass('was-validated');  
            $('#community_id_error').empty();
            $('#household_id_error').empty();
            $('#all_energy_meter_id_error').empty();
        });
    }); 
</script>
