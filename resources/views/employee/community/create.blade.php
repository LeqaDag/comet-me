<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<div id="createCommunity" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Create New Community
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' action="{{url('community')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Products type</label>
                                <select name="school" id="schoolchanges" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name="region_id" id="selectedRegion" 
                                    class="form-control" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($regions as $region)
                                    <option value="{{$region->id}}">
                                        {{$region->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Sub Region</label>
                                <select name="sub_region_id" id="selectedSubRegions" 
                                class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                    
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Latitude</label>
                                <input type="text" name="latitude" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Longitude</label>
                                <input type="text" name="longitude" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Fallah</label>
                                <select name="is_fallah" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Bedouin</label>
                                <select name="is_bedouin" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Nearby Settlements</label>
                                <input type="text" name="settlement" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Demolition orders/demolitions </label>
                                <input type="text" name="demolition" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Land Status</label>
                                <input type="text" name="land_status" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Lawyer</label>
                                <input type="text" name="lawyer" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> School</label>
                                <select name="school" id="schoolchanges" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> Clinic </label>
                                <select name="clinic" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> Mosque </label>
                                <select name="mosque" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> Kindergarten</label>
                                <select name="school" id="schoolchanges" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> Community Center </label>
                                <select name="clinic" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recommended system type</label>
                                <select name="clinic" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energyTypes as $energyType)
                                    <option value="{{$energyType->id}}">{{$energyType->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                            <fieldset class="form-group">
                                <input type="text" name="description" class="form-control"
                                    id="percentageInputQuestion1" style="visiblity:hidden; display:none">
                            </fieldset>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Nearby town1 </label>
                                <input type="text" name="hospital_town" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Nearby town2 </label>
                                <input type="text" name="hospital_town" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <input type="text" name="notes" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                <tr>
                                    <th>Compound Name</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                        placeholder="Enter Copmound Name" class="target_point form-control" 
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
    
    $(document).on('change', '#selectedRegion', function () {
        region_id = $(this).val();
   
        $.ajax({
            url: "community/get_by_region/" + region_id,
            method: 'GET',
            success: function(data) {
                $('#selectedSubRegions').prop('disabled', false);
                $('#selectedSubRegions').html(data.html);
            }
        });

    });

    $(document).on('change', '#schoolchanges', function () {
        selectValueQuestion1 = $(this).val();

        if(selectValueQuestion1 == "yes") {
           
            $("#percentageInputQuestion1").css("visibility", "visible");
            $("#percentageInputQuestion1").css('display', 'block');
            $("#percentageInputQuestion1").attr("placeholder", "What Grades");
        } else if(selectValueQuestion1 == "no") {

            $("#percentageInputQuestion1").css("visibility", "visible");
            $("#percentageInputQuestion1").css('display', 'block');
            $("#percentageInputQuestion1").attr("placeholder", "What town do children go to school in?");
        }
    });

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