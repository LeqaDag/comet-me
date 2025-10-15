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


<div id="createRefrigeratorHolder" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Refrigerator Holder	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="refrigeratorForm"
                    action="{{url('refrigerator-user')}}">
                    @csrf
                    <div class="row">
                        
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" name="community_id" data-live-search="true" 
                                    id="communityChanges" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->arabic_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household/Public Structure</label>
                                <select name="is_household" id="isHousehold" 
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Household</option>
                                    <option value="no">Public Structure</option>
                                </select>
                            </fieldset>
                            <div id="public_user_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Refrigerator Holder</label>
                                <select name="" id="selectedRefrigeratorHolder" 
                                    class="selectpicker form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="holder_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="number" name="phone_number" id="householdPhoneNumber"
                                    class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Refrigerator Type</label>
                                <select name="refrigerator_type_id" id="refrigeratorType"
                                    class="selectpicker form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="No frost">No frost</option>
                                    <option value="De frost">De frost</option>
                                </select>
                            </fieldset>
                            <div id="refrigerator_type_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Refrigerator</label>
                                <input type="number" name="number_of_fridge" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date</label>
                                <input type="date" name="date" required
                                    class="form-control">
                            </fieldset>
                        </div>
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Year</label>
                                <input type="number" name="year" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Is Paid?</label>
                                <select name="is_paid" class="selectpicker form-control" 
                                    id="refrigeratorPaid">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Free">Free</option>
                                </select>
                            </fieldset>
                            <div id="is_paid_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Payment</label>
                                <input type="number" name="payment" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Receipt Number</label>
                                <input type="text" name="receive_number" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
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

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();
   
        $('#isHousehold').prop('disabled', false);
        $('#selectedRefrigeratorHolder').empty();
        $('#chooseUserOrPublic').prop('disabled', false);
        UserOrPublic(community_id);
    });

    function UserOrPublic(community_id) {
        $(document).on('change', '#isHousehold', function () {

            is_household = $(this).val();
            
            if(is_household == "yes") {

                $("#selectedRefrigeratorHolder").attr('name', 'household_id');
                $.ajax({
                    url: "household/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {

                        var select = $('#selectedRefrigeratorHolder');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });

            } else if(is_household == "no"){

                $("#selectedRefrigeratorHolder").attr('name', 'public_structure_id');
                $.ajax({
                    url: "energy_public/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {

                        var select = $('#selectedRefrigeratorHolder');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    }

    $(document).on('change', '#selectedRefrigeratorHolder', function () {
        household_id = $(this).val();
   
        $.ajax({
            url: "refrigerator-user/household/" + household_id,
            method: 'GET',
            success: function(data) {
              
                $('#householdPhoneNumber').html(data.household.phone_number);
            }
        });
    });

    $(document).ready(function() {

        $('#refrigeratorForm').on('submit', function (event) {

            var communityValue = $('#communityChanges').val();
            var userOrPublicValue = $('#isHousehold').val();
            var refrigeratorValue = $('#selectedRefrigeratorHolder').val();
            var refrigeratorTypeValue = $('#refrigeratorType').val();
            var refrigeratorPaid = $('#refrigeratorPaid').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (userOrPublicValue == null) {

                $('#public_user_error').html('Please select an option!'); 
                return false;
            } else if (userOrPublicValue != null){

                $('#public_user_error').empty();
            }

            if (refrigeratorValue == null) {

                $('#holder_id_error').html('Please select a holder!'); 
                return false;
            } else if (refrigeratorValue != null){

                $('#holder_id_error').empty();
            }

            if (refrigeratorTypeValue == null) {

                $('#refrigerator_type_id_error').html('Please select a type!'); 
                return false;
            } else if (refrigeratorTypeValue != null){

                $('#refrigerator_type_id_error').empty();
            }

            if (refrigeratorPaid == null) {

                $('#is_paid_error').html('Please select an option!'); 
                return false;
            } else if (refrigeratorPaid != null){

                $('#is_paid_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#holder_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();
            $('#refrigerator_type_id_error').empty();
            $('#is_paid_error').empty();

            this.submit();
        });
    });

</script>