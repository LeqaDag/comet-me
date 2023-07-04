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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "ac-household/household/get_by_community/" + community_id,
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