
<div id="createCommunityCompound" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create New Community Compound</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <form method="POST" enctype='multipart/form-data' 
                action="{{url('compound')}}">
                @csrf
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id"  
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

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Compound English Name</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr> 
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="Enter English Copmound Name" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
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
    var j = 0;
    $("#addCompoundNameButton").click(function () {
        ++j;
        $("#dynamicAddRemoveCompoundName").append('<tr><td><input type="text"' +
            'name="addMoreInputFieldsCompoundName[][subject]" placeholder="Enter Another one"' +
            'class="target_point form-control" data-id="'+ j +'" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-target-points">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove-input-field-target-points', function () {
        $(this).parents('tr').remove();
    });
</script>