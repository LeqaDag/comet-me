<style>
    label, input {
        display: block;
    } 

    label, table {
        margin-top: 20px;
    }
</style>


<div id="createSubCommunityHousehold" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Sub Community Household</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <form method="POST" enctype='multipart/form-data' action="{{url('sub-community-household')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="communityChanges" 
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

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household</label>
                                <select name="household_id" id="selectedHousehold" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Sub Community</label>
                                <select name="sub_community_id" id="selectedSubCommunity" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($subCommunities as $subCommunity)
                                    <option value="{{$subCommunity->id}}">
                                        {{$subCommunity->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm">Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
     
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "progress-household/household/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#selectedHousehold').prop('disabled', false);
                $('#selectedHousehold').html(data.html);
            }
        });

        $.ajax({
            url: "sub-community/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#selectedSubCommunity').prop('disabled', false);
                $('#selectedSubCommunity').html(data.html);
            }
        });
    });

</script>