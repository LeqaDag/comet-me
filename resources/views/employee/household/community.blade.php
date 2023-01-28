<div id="createCommunity" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Community
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <form enctype='multipart/form-data' id="communityFormInHousehold">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control" required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name="region_id" id="selectedRegionCommunity" 
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
                                <select name="sub_region_id" id="selectedSubRegionsCommunity" 
                                class="form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                    
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Location</label>
                                <input type="text" name="location_gis" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Compounds</label>
                                <input type="number" name="number_of_compound" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of People</label>
                                <input type="number" name="number_of_people" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Households</label>
                                <input type="number" name="number_of_households" class="form-control">
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
                                <label class='col-md-12 control-label'>Last Demolitions </label>
                                <input type="number" name="demolition" 
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
                                <label class='col-md-12 control-label'>Is there school?</label>
                                <select name="school" id="schoolchangesHousehold" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Is there clinic? </label>
                                <select name="clinic" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Is there mosque ? </label>
                                <select name="mosque" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                            <fieldset class="form-group">
                                <input type="text" name="description" class="form-control"
                                    id="percentageInputQuestionHousehold1" style="visiblity:hidden; display:none">
                            </fieldset>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Where do community members go to the hospital? </label>
                                <input type="text" name="hospital_town" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <input type="text" name="notes" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="addNewCommunityHouseholdButton" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    
    $(document).on('change', '#selectedRegionCommunity', function () {
        region_id = $(this).val();

        $.ajax({
            url: "community/" + region_id,
            method: 'GET',
            success: function(data) {
                $('#selectedSubRegionsCommunity').prop('disabled', false);
                $('#selectedSubRegionsCommunity').html(data.html);
            }
        });
    });

    $(document).on('change', '#schoolchangesHousehold', function () {
        selectValueQuestion1 = $(this).val();

        if(selectValueQuestion1 == "yes") {
           
            $("#percentageInputQuestionHousehold1").css("visibility", "visible");
            $("#percentageInputQuestionHousehold1").css('display', 'block');
            $("#percentageInputQuestionHousehold1").attr("placeholder", "What Grades");
        } else if(selectValueQuestion1 == "no") {

            $("#percentageInputQuestionHousehold1").css("visibility", "visible");
            $("#percentageInputQuestionHousehold1").css('display', 'block');
            $("#percentageInputQuestionHousehold1").attr("placeholder", "What town do children go to school in?");
        }
    });

    $("#addNewCommunityHouseholdButton").submit(function(e) {

        formData = $("#communityFormInHousehold").serialize();
        console.log(formData);

    });
    
</script>