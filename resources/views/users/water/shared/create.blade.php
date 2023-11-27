<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}

</style>

<div id="createSharedWaterUser" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalWaterUser">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalWaterUser">
                    Create New Water System Holder	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('shared-h2o')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id[]" id="communityChanges" 
                                    class="selectpicker form-control" 
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Holder</label>
                                <select name="h2o_user_id" id="selectedWaterHolderHousehold" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Shared Household</label>
                                <select name="household_id" id="selectedHousehold" 
                                    class="form-control" disabled>
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
    $(document).on('change', '#communityChanges', function () {
        community_id = $(this).val();

        $.ajax({
            url: "shared-h2o/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedWaterHolderHousehold').prop('disabled', false);
                $('#selectedWaterHolderHousehold').html(data.html);
            }
        });

        $.ajax({
            url: "household/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedHousehold').prop('disabled', false);
                $('#selectedHousehold').html(data.html);
            }
        });
    });
</script>