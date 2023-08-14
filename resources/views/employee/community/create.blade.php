<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

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
                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h6>General Details</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                    required>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'># of Families</label>
                                <input type="number" name="number_of_household" class="form-control"
                                    required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name="region_id" id="selectedRegion" 
                                    class="selectpicker form-control" data-live-search="true"  required>
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
                                class="form-control"  disabled required>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Products type</label>
                                <select name="product_type_id" id="product_type_id" 
                                    class="selectpicker form-control" data-live-search="true"  
                                    multiple >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
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
                                <label class='col-md-12 control-label'>Cellular Reception?</label>
                                <select name="reception" class="form-control">
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
                                <label class='col-md-12 control-label'>Energy Sources</label>
                                <select class="form-control" name="energy_source">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Grid">Grid</option>
                                    <option value="Old Solar System">Old Solar System</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Sources</label>
                                <select class="selectpicker form-control" multiple data-live-search="true" 
                                    name="waters[]">
                                    @foreach($waterSources as $waterSource)
                                    <option value="{{$waterSource->id}}">
                                        {{$waterSource->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
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
                                <label class='col-md-12 control-label'>Nearby Towns</label>
                                <select class="selectpicker form-control" multiple data-live-search="true" 
                                    name="towns[]" id="nearbyTowns">
                                    @foreach($towns as $town)
                                    <option value="{{$town->id}}">
                                        {{$town->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Nearby Settlements</label>
                                <select class="selectpicker form-control" multiple data-live-search="true" 
                                    name="settlement[]" id="nearbySettlements">
                                    @foreach($settlements as $settlement)
                                    <option value="{{$settlement->id}}">
                                        {{$settlement->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structures</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" 
                                    name="public_structures[]" id="publicStructures">
                                    @foreach($publicCategories as $publicCategorie)
                                    <option value="{{$publicCategorie->id}}">
                                        {{$publicCategorie->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <!-- <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label' id=""></label>
                                <select class="selectpicker form-control" multiple data-live-search="true" 
                                    name="public_structures[]" id="publicStructures">
                                    @foreach($publicCategories as $publicCategorie)
                                    <option value="{{$publicCategorie->id}}">
                                        {{$publicCategorie->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> -->

                        <!-- <div class="col-xl-4 col-lg-4 col-md-4 mb-1" 
                            id="schoolShared">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label' id="schoolSharedLabel"
                                style="visiblity:hidden; display:none" >Is School shared?</label>
                                <select name="sharedSchool" id="schoolSharedSelect"
                                style="visiblity:hidden; display:none" class="form-control">
                                    <option selected disabled>Choose ...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </fieldset>
                        </div> -->
                    </div>


                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                            <fieldset class="form-group">
                                <input type="text" name="description" class="form-control"
                                    id="percentageInputQuestion1" 
                                    style="visiblity:hidden; display:none">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recommended system type</label>
                                <select name="recommended_energy_system_id[]" 
                                    class="selectpicker form-control" multiple data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energyTypes as $energyType)
                                    <option value="{{$energyType->id}}">{{$energyType->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="1"></textarea>
                            </fieldset>
                        </div>
                    </div> 
                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h6>Second Name for community</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Second Name in English</label>
                                <input name="second_name_english" type="text" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Second Name in Arabic</label>
                                <input name="second_name_arabic" type="text" class="form-control">
                            </fieldset>
                        </div> 
                    </div> 
                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h6>Compounds</h6>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
   
   $(document).on('change', '#publicStructures', function () {
        publicStructure = $(this).val();

        if(publicStructure == 1) {
            $("#schoolSharedLabel").css("visibility", "visible");
            $("#schoolSharedLabel").css('display', 'block');
            $("#schoolSharedSelect").css("visibility", "visible");
            $("#schoolSharedSelect").css('display', 'block');
        }
 
    });

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