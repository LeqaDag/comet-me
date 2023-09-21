
<div id="createCompoundHouseholds" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Compound Households</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <form method="POST" enctype='multipart/form-data' 
                action="{{url('community-compound')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="communityCompoundChanges" 
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
                                <label class='col-md-12 control-label'>Compound</label>
                                <select name="compound_id" id="selectedCompound" 
                                    class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Household</label>
                                <select name="household_id[]" id="selectedHouseholdComound" 
                                    class="selectedHouseholdComound form-control selectpicker" 
                                    data-live-search="true" multiple>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($households as $household)
                                    <option value="{{$household->id}}">
                                        {{$household->english_name}}
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
     
    $(document).on('change', '#communityCompoundChanges', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "community-compound/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                
                $('#selectedCompound').prop('disabled', false);
                $('#selectedCompound').html(data.htmlCompounds);

                // $("#selectedHouseholdComound").append('<option>Select</option>');
                // $.each(data.htmlHouseholds, function(key, value) {

                //     $("#selectedHouseholdComound").append('<option value="'+value['id']+'">'+value['english_name']+'</option>');
                // });
                // $('#selectedHouseholdComound').selectpicker();
            }
        });
    });

</script>