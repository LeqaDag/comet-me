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

<div id="createCommunityRepresentative" class="modal fade" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"> 
                <h1 class="modal-title fs-5">
                    Create New Community Representative	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="representativeForm"
                    action="{{url('representative')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="communityChangesRep" 
                                    class="selectpicker form-control" data-live-search="true" 
                                    required data-parsley-required="true"> 
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
                                <label class='col-md-12 control-label'>Compound</label>
                                <select name="compound_id" id="compoundRepresentitive" 
                                    class="selectpicker form-control" data-live-search="true"> 
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household</label>
                                <select name="household_id" id="selectedHouseholdRepor" 
                                    class="selectpicker form-control" required disabled
                                    data-live-search="true" data-parsley-required="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="household_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="text" name="phone_number" id="phoneNumber"
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>
               
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Role</label>
                                <select name="community_role_id" data-live-search="true" 
                                    class="selectpicker form-control" required 
                                    data-parsley-required="true" id="community_role_id">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communityRoles as $communityRoles)
                                    <option value="{{$communityRoles->id}}">{{$communityRoles->role}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_role_id_error" style="color: red;"></div>
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
    
    $(document).on('change', '#communityChangesRep', function () {
        community_id = $(this).val();
   
        $.ajax({ 
            url: "community-compound/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#compoundRepresentitive').prop('disabled', false);

                var select = $('#compoundRepresentitive'); 

                select.html(data.htmlCompounds);
                select.selectpicker('refresh');
            }
        }); 

        $(document).on('change', '#compoundRepresentitive', function () {

            var compound_id = $('#compoundRepresentitive').val(); 

            if(compound_id) {

                $.ajax({ 
                    url: "compound/get_households/get_by_compound/" + compound_id,
                    method: 'GET',
                    success: function(data) {
                        
                        $('#selectedHouseholdRepor').prop('disabled', false);

                        var select = $('#selectedHouseholdRepor'); 

                        select.html(data.htmlHouseholds);
                        select.selectpicker('refresh');
                    }
                }); 
            } 
        });

        $.ajax({ 
            url: "household/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#selectedHouseholdRepor').prop('disabled', false);

                var select = $('#selectedHouseholdRepor'); 

                select.html(data.html);
                select.selectpicker('refresh');
            }
        }); 
    });

    $(document).ready(function () {

        $('#representativeForm').on('submit', function (event) {

            var communityValue = $('#communityChangesRep').val();
            var roleValue = $('#community_role_id').val();
            var householdValue = $('#selectedHouseholdRepor').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }
            
            if (householdValue == null) {

                $('#household_id_error').html('Please select a household!'); 
                return false;
            } else if (householdValue != null){

                $('#household_id_error').empty();
            }

            if (roleValue == null) {

                $('#community_role_id_error').html('Please select a role!'); 
                return false;
            } else if (roleValue != null) {

                $('#community_role_id_error').empty();
            }
            
            $(this).addClass('was-validated');  
            $('#community_id_error').empty();
            $('#community_role_id_error').empty();
            $('#household_id_error').empty();

            this.submit();
        });
    });

    $(document).on('change', '#selectedHouseholdRepor', function () {
        household_id = $(this).val();
   
        $.ajax({
            url: "household/" + household_id,
            method: 'GET',
            success: function(response) {
                
                $("#phoneNumber").val(response['household'].phone_number);
            }
        });
    });

</script>