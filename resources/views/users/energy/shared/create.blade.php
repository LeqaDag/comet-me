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
 
<div id="createHouseholdMeter" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Shared Meter Holder
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="sharedHolderForm"
                    action="{{url('household-meter')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" name="community_id" 
                                    data-live-search="true" id="communitySharedUser" required>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Meter</label>
                                <select name="energy_user_id" id="selectedEnergyUser" disabled
                                    class="selectpicker form-control" data-live-search="true"
                                    data-parsley-required="true" required>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="energy_user_id_error" style="color: red;"></div>
                        </div>
                        <div id="shared_error" style="color: red;"></div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Households "Shared"</label>
                                <select name="household_id[]" id="selectedAllHousehold" 
                                    class="selectpicker form-control" data-live-search="true" 
                                    multiple disabled required>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structures "Shared"</label>
                                <select name="public_id[]" id="selectedAllPublic" 
                                    class="selectpicker form-control" data-live-search="true" 
                                    multiple disabled required>
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
 
<script>

    $(document).on('change', '#communitySharedUser', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "household-meter/get_users/" + community_id,
            method: 'GET',
            success: function(data) { 

                var select = $('#selectedEnergyUser'); 
                select.prop('disabled', false);

                select.html(data.html);
                select.selectpicker('refresh');
            }
        });

        $(document).on('change', '#selectedEnergyUser', function () {
            user_id = $(this).val();
    
            $.ajax({
                url: "household-meter/get_households/" + user_id,
                method: 'GET',
                success: function(data) { 

                    $('#selectedAllHousehold').prop('disabled', false);
                    var selectHousehold = $('#selectedAllHousehold'); 

                    selectHousehold.html(data.html);
                    selectHousehold.selectpicker('refresh');
                }
            });

            $.ajax({
                url: "household-meter/get_publics/" + user_id,
                method: 'GET',
                success: function(data) { 

                    $('#selectedAllPublic').prop('disabled', false);
                    var select = $('#selectedAllPublic'); 

                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    });

    $(document).on('change', '#selectedEnergySystemType', function () {
        energy_type_id = $(this).val();
   
        $.ajax({
            url: "energy-user/get_by_energy_type/" + energy_type_id,
            method: 'GET',
            success: function(data) {
                $('#selectedEnergySystem').prop('disabled', false);
                $('#selectedEnergySystem').html(data.html);
            }
        });
    });

    $(document).ready(function () {
 
        $('#sharedHolderForm').on('submit', function (event) {

            var communityMain = $('#communitySharedUser').val();
            var mainUser = $('#selectedEnergyUser').val();
            var householdValue = $('#selectedAllHousehold').val();
            var publicValue = $('#selectedAllPublic').val();

            if (communityMain == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityMain != null) {

                $('#community_id_error').empty();
            }

            if (mainUser == null) {

                $('#energy_user_id_error').html('Please select a user!');
                return false;
            } else if (mainUser != null) {

                $('#energy_user_id_error').empty();
            }
            
            if (householdValue == null && publicValue == null) {

                $('#shared_error').html('Please select a household or public!');
                return false;
            } else {

                $('#shared_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#community_id_error').empty();  
            $('#energy_user_id_error').empty(); 

            this.submit();
        });
    });
</script>