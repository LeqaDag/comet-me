<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}


</style>

<div id="createWaterResult" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Water Quality Result
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('quality-result')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="communityChanges" 
                                    class="selectpicker form-control"
                                    data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>User/Public Structure</label>
                                <select name="public_user" id="userOrPublicSelected" 
                                    class="form-control" disabled required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="user">Water User</option> 
                                    <option value="shared">Shared Water User</option>
                                    <option value="public">Public Structure</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Holder</label>
                                <select name="household_id" id="selectedWaterHolder" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Visiting Date</label>
                                <input type="date" name="date" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Results</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Electrical Conductivity "EC"</label>
                                <input type="text" name="ec" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Free Chlorine "F.CL"</label>
                                <input type="text" name="fci" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>PH</label>
                                <input type="text" name="ph" 
                                    class="form-control">
                            </fieldset>
                        </div>  
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Biological Contamination "CFU"</label>
                                <input type="text" name="cfu" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <label class='col-md-12 headingLabel'>Notes</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Notes</label>
                                    <textarea name="notes" style="resize:none" class="form-control">
                                       
                                    </textarea>
                                </fieldset>
                            </div>  
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
   
        $('#userOrPublicSelected').prop('disabled', false);

        UserOrPublic(community_id);

    });

    function UserOrPublic(community_id) {
        $(document).on('change', '#userOrPublicSelected', function () {
            publicUser = $('#userOrPublicSelected').val();
            
            if(publicUser == "user") {
            
                getWaterHolders(community_id, publicUser);
                
            } else if(publicUser == "shared") {
                
                $('#selectedWaterHolder').prop('disabled', true);
                getWaterHolders(community_id, publicUser);
                
            } else if(publicUser == "public") {

                $('#selectedWaterHolder').prop('disabled', true);
                getWaterHolders(community_id, publicUser);
            }
        });
    }

    function getWaterHolders(community_id, publicUser) {
        $.ajax({
            url: "water_holder/get_by_community/" + community_id + "/" + publicUser,
            method: 'GET',
            success: function(data) {
                
                $('#selectedWaterHolder').prop('disabled', false);
                $('#selectedWaterHolder').html(data.html);
            }
        });
    }
</script>