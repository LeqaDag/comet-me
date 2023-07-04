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
                <form method="POST" enctype='multipart/form-data' action="{{url('representative')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="communityChangesRep" 
                                    class="selectpicker form-control"
                                    data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household</label>
                                <select name="household_id" id="selectedHouseholdRep" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="text" name="phone_number" id="phoneNumber"
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>
               
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Role</label>
                                <select name="community_role_id" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communityRoles as $communityRoles)
                                    <option value="{{$communityRoles->id}}">{{$communityRoles->role}}</option>
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
    
    $(document).on('change', '#communityChangesRep', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "ac-household/household/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#selectedHouseholdRep').prop('disabled', false);
                $('#selectedHouseholdRep').html(data.html);
            }
        });
    });

    $(document).on('change', '#selectedHouseholdRep', function () {
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