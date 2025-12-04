@extends('layouts/layoutMaster')

@section('title', 'Elc')

@include('layouts.all')

<style>
    label,
    input {
        display: block;
    }

    label {
        margin-top: 20px;
    }
</style>

@section('vendor-style')
@endsection

@section('content')
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Add </span> New Elctr.
    </h4>

    <div class="card">
        <div class="card-content collapse show">
            <div class="card-body">
                <form method="POST" enctype='multipart/form-data' id="elecUserForm"
                    action="{{ url('progress-household') }}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label>New/Old Community</label>
                                <select name="misc" id="selectedUserMisc" data-live-search="true"
                                    class="selectpicker form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($installationTypes as $installationType)
                                        <option value="{{ $installationType->id }}">{{ $installationType->type }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="misc_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label>Community</label>
                                <select class="selectpicker form-control" data-live-search="true" name="community_id"
                                    id="selectedUserCommunity" required>
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label>Compound (optional)</label>
                                <select class="selectpicker form-control" name="compound_id" id="selectedCompound"
                                    data-live-search="true">
                                    <option value="" selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label>Users</label>
                                <select name="household_id[]" id="selectedUserHousehold" class="selectpicker form-control"
                                    data-live-search="true" multiple disabled required>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="household_id_error" style="color: red;"></div>

                            
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label>Was the meter added to the system?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="meter_added" id="meter_added_yes" value="1">
                                        <p class="form-check-label" for="meter_added_yes">Yes</p>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="meter_added" id="meter_added_no" value="0">
                                        <p class="form-check-label" for="meter_added_no">No</p>
                                    </div>
                                </div>
                            </fieldset>
                            <div id="meter_added_error" style="color: red;"></div>
                        </div>

                        


                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label>Energy System Type</label>
                                <select name="energy_system_type_id" class="selectpicker form-control"
                                    id="selectedEnergySystemType" data-live-search="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystemTypes as $energySystemType)
                                        <option value="{{ $energySystemType->id }}">{{ $energySystemType->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="energy_system_type_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label>Energy System</label>
                                <select name="energy_system_id" id="selectedEnergySystem" class="form-control" disabled
                                    required>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <button type="button" class="btn btn-primary mt-1" name="generate_system_id"
                                id="generateSys">Generate a New Energy System Type</button>
                            <div id="energy_system_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label>Cycle Year</label>
                                <select name="energy_system_cycle_id" class="selectpicker form-control"
                                    id="energySystemCycleSelected" data-live-search="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystemCycles as $energySystemCycle)
                                        <option value="{{ $energySystemCycle->id }}">{{ $energySystemCycle->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="energy_system_cycle_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    

                    <div class="row mt-4">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Create New Household Modal --}}
    <div id="createNewHousehold" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Create New Household</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="csrf" value="{{ Session::token() }}">
                    <div class="row">
                        <div class="col-xl-6">
                            <fieldset class="form-group">
                                <label>Community</label>
                                <select name="community_id" id="selectedCommunity" class="selectpicker form-control"
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                        <option value="{{ $community->id }}">{{ $community->english_name }}</option>
                                    @endforeach
                                    <option value="other" style="color:red">Other</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6">
                            <fieldset class="form-group">
                                <label>Father/Husband Name</label>
                                <input type="text" name="english_name" id="english_name" placeholder="Write in English"
                                    class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4">
                            <fieldset class="form-group">
                                <label>Father/Husband Name (Arabic)</label>
                                <input type="text" name="arabic_name" id="arabic_name" placeholder="Write in Arabic"
                                    class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4">
                            <fieldset class="form-group">
                                <label>Wife/Mother Name</label>
                                <input type="text" name="women_name_arabic" id="women_name_arabic" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4">
                            <fieldset class="form-group">
                                <label>Profession</label>
                                <select name="profession_id" id="selectedProfession" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($professions as $profession)
                                        <option value="{{ $profession->id }}">{{ $profession->profession_name }}</option>
                                    @endforeach
                                    <option value="other" style="color:red">Other</option>
                                </select>
                            </fieldset>
                            @include('employee.household.profession')
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-xl-4"><input type="number" name="number_of_male" id="number_of_male"
                                class="form-control" placeholder="Male"></div>
                        <div class="col-xl-4"><input type="number" name="number_of_female" id="number_of_female"
                                class="form-control" placeholder="Female"></div>
                        <div class="col-xl-4"><input type="number" name="number_of_adults" id="number_of_adults"
                                class="form-control" placeholder="Adults"></div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-xl-4"><input type="number" name="number_of_children" id="number_of_children"
                                class="form-control" placeholder="Children under 16"></div>
                        <div class="col-xl-4"><input type="number" name="school_students" id="school_students"
                                class="form-control" placeholder="Children in school"></div>
                        <div class="col-xl-4"><input type="number" name="university_students" id="university_students"
                                class="form-control" placeholder="University students"></div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" id="newHouseholdButton">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {

            // Load Communities based on Misc selection
            $('#selectedUserMisc').on('change', function () {
                var installation_type = $(this).val();
                $.get(`household/get_community_type/${installation_type}`, function (data) {
                    $('#selectedUserCommunity').prop('disabled', false).html(data.html).selectpicker('refresh');
                });
            });

            // Handle Community selection logic
            $('#selectedUserCommunity').on('change', function () {
                var communityId = $(this).val();
                var $compoundSelect = $('#selectedCompound');
                var $householdSelect = $('#selectedUserHousehold');

                // Reset dropdowns
                $compoundSelect.empty().append('<option value="">Choose one...</option>').selectpicker('refresh');
                $householdSelect.empty().append('<option disabled selected>Choose one...</option>').selectpicker('refresh');

                if (communityId) {
                    // Fetch compounds for this community
                    $.get(`/compounds/by-community/${communityId}`, function (data) {
                        if (data.length > 0) {
                            // Show compound dropdown if compounds exist
                            $compoundSelect.closest('.form-group').show();
                            data.forEach(c => $compoundSelect.append(`<option value="${c.id}">${c.english_name}</option>`));
                            $compoundSelect.selectpicker('refresh');
                        } else {
                            // Hide compound dropdown if there are none
                            $compoundSelect.closest('.form-group').hide();

                            // Load all households directly by community
                            $.get(`household/get_un_user_by_community/${communityId}`, function (response) {
                                $householdSelect.prop('disabled', false).html(response.html).selectpicker('refresh');
                            });
                        }
                    });

                    // Update energy systems when community changes
                    changeEnergySystemType($('#selectedEnergySystemType').val(), communityId);
                }
            });

            // Handle compound selection logic
            $('#selectedCompound').on('change', function () {
                var compoundId = $(this).val();
                var $householdSelect = $('#selectedUserHousehold');

                if (compoundId) {
                    // Use the correct endpoint and handle HTML response
                    $.get(`/compound/get_households/get_by_compound/${compoundId}`, function (data) {
                        $householdSelect.html(data.htmlHouseholds).prop('disabled', false).selectpicker('refresh');
                    });
                } else {
                    $householdSelect.html('<option disabled selected>Choose one...</option>').selectpicker('refresh');
                }
            });

            // Handle Energy System Type change
            $('#selectedEnergySystemType').on('change', function () {
                var energy_type_id = $(this).val();
                var community_id = [1, 3, 4].includes(Number(energy_type_id)) ? $('#selectedUserCommunity').val() : 0;
                changeEnergySystemType(energy_type_id, community_id);
            });

            function changeEnergySystemType(energy_type_id, community_id) {
                $.get(`energy-user/get_by_energy_type/${energy_type_id}/${community_id}`, function (data) {
                    $('#selectedEnergySystem').prop('disabled', false).html(data.html);
                    // After energy systems list is updated, re-check generate button state
                    checkGenerateButtonState();
                });
            }

            // Build the display name used for generated Energy System (same logic as in generator)
            function buildDisplayName() {
                var communityText = $('#selectedUserCommunity option:selected').text().trim();
                var compoundVal = $('#selectedCompound').val();
                var compoundText = $('#selectedCompound option:selected').text().trim();
                var energyTypeText = $('#selectedEnergySystemType option:selected').text().trim();

                if (!$('#selectedUserCommunity').val() || !$('#selectedEnergySystemType').val()) return null;

                var parts = [];
                if (compoundVal && compoundVal !== '') {
                    if (compoundText && compoundText !== 'Choose one...') {
                        parts.push(compoundText);
                    } else {
                        parts.push(compoundVal);
                    }
                } else {
                    parts.push(communityText && communityText !== 'Choose one...' ? communityText : $('#selectedUserCommunity').val());
                }
                parts.push(energyTypeText && energyTypeText !== 'Choose one...' ? energyTypeText : $('#selectedEnergySystemType').val());
                return parts.join(' ').trim();
            }

            // Disable generate button if the generated display name already exists in the systems list
            function checkGenerateButtonState() {
                var displayName = buildDisplayName();
                var $btn = $('#generateSys');
                if (!displayName) {
                    $btn.prop('disabled', false).removeAttr('title');
                    return;
                }

                // Look for an option with exact text match
                var exists = $('#selectedEnergySystem option').filter(function () {
                    return $(this).text().trim() === displayName;
                }).length > 0;

                if (exists) {
                    $btn.prop('disabled', true).attr('title', 'Energy System already exists in the list');
                } else {
                    $btn.prop('disabled', false).removeAttr('title');
                }
            }

            // Form Validation
            $('#elecUserForm').on('submit', function (e) {
                var fields = [
                    { id: '#selectedUserMisc', error: '#misc_error', message: 'Please select an option!' },
                    { id: '#selectedUserCommunity', error: '#community_id_error', message: 'Please select a community!' },
                    { id: '#selectedUserHousehold', error: '#household_id_error', message: 'Please select at least one household!' },
                    { id: '#selectedEnergySystemType', error: '#energy_system_type_id_error', message: 'Please select an Energy System Type!' },
                    { id: '#selectedEnergySystem', error: '#energy_system_id_error', message: 'Please select an Energy System!' },
                    { id: '#energySystemCycleSelected', error: '#energy_system_cycle_id_error', message: 'Please select an Energy cycle!' }
                ];
                var valid = true;

                // Require meter_added radio to be selected
                if ($('input[name="meter_added"]:checked').length === 0) {
                    $('#meter_added_error').html('Please indicate if the meter was added!');
                    valid = false;
                } else {
                    $('#meter_added_error').empty();
                }

                fields.forEach(f => {
                    if (!$(f.id).val() || ($(f.id).is('select[multiple]') && $(f.id).val().length === 0)) {
                        $(f.error).html(f.message);
                        valid = false;
                    } else {
                        $(f.error).empty();
                    }
                });
                if (!valid) e.preventDefault();
            });

            // Create new Household via modal
            $('#newHouseholdButton').on('click', function () {
                $.get('household/new', {
                    _token: $("#csrf").val(),
                    community_id: $("#selectedCommunity").val(),
                    english_name: $("#english_name").val(),
                    arabic_name: $("#arabic_name").val(),
                    profession_id: $("#selectedProfession").val(),
                    university_students: $("#university_students").val(),
                    school_students: $("#school_students").val(),
                    number_of_children: $("#number_of_children").val(),
                    number_of_adults: $("#number_of_adults").val(),
                    number_of_female: $("#number_of_female").val(),
                    number_of_male: $("#number_of_male").val(),
                    phone_number: $("#phone_number").val(),
                    women_name_arabic: $("#women_name_arabic").val()
                }, function (data) {
                    $('body').removeClass('modal-open').css('padding-right', '');
                    $(".modal-backdrop").remove();
                    $("#createNewHousehold").modal("hide");
                    $('#selectedUserHousehold').prop('disabled', false).html(data.html).selectpicker('refresh');
                }, 'json');
            });

            // Hide compound dropdown by default until community is selected
            $('#selectedCompound').closest('.form-group').hide();

            // Generate System ID button handler: create server-side EnergySystem and set numeric id
            $('#generateSys').on('click', function (e) {
                e.preventDefault();
                console.log('Hello');

                var communityVal = $('#selectedUserCommunity').val();
                var communityText = $('#selectedUserCommunity option:selected').text().trim();
                var compoundVal = $('#selectedCompound').val();
                var compoundText = $('#selectedCompound option:selected').text().trim();
                var energyTypeVal = $('#selectedEnergySystemType').val();
                var energyTypeText = $('#selectedEnergySystemType option:selected').text().trim();

                // Basic validation: community and energy system type are required to build name
                if (!communityVal) {
                    $('#energy_system_id_error').html('Please select a Community first!');
                    return;
                }
                if (!energyTypeVal) {
                    $('#energy_system_id_error').html('Please select an Energy System Type first!');
                    return;
                }

                // Prevent duplicate generation if a system with same display name already exists
                var candidateName = buildDisplayName();
                if (candidateName) {
                    var already = $('#selectedEnergySystem option').filter(function () { return $(this).text().trim() === candidateName; }).length > 0;
                    if (already) {
                        $('#energy_system_id_error').html('Energy System already exists in the list.');
                        $('#generateSys').prop('disabled', true).attr('title', 'Already exists');
                        return;
                    }
                }

                // Build display name: Community [+ Compound] + System Type
                // Build display name: If compound exists → use it only, otherwise use community
                var parts = [];

                // If compound is selected, use compound ONLY
                if (compoundVal && compoundVal !== '') {
                    if (compoundText && compoundText !== 'Choose one...') {
                        parts.push(compoundText);
                    } else {
                        parts.push(compoundVal);
                    }
                } else {
                    // No compound → fallback to community
                    parts.push(communityText && communityText !== 'Choose one...' ? communityText : communityVal);
                }

                // Always add energy system type
                parts.push(energyTypeText && energyTypeText !== 'Choose one...' ? energyTypeText : energyTypeVal);

                var displayName = parts.join(' ');

                // Prepare payload for AJAX create. Include optional cycle if selected.
                var payload = {
                    name: displayName,
                    community_id: communityVal,
                    energy_system_type_id: energyTypeVal,
                    energy_system_cycle_id: $('#energySystemCycleSelected').val(),
                    installation_year: null,
                    notes: null
                };

                var token = $('input[name="_token"]').val();

                $.ajax({
                    url: '/energy-system/ajax-store',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    data: payload,
                    dataType: 'json'
                }).done(function (response) {
                    if (response && response.success && response.id) {
                        var id = response.id;
                        var name = response.name || displayName;
                        var $energySelect = $('#selectedEnergySystem');

                        // Remove any previous placeholder option with same id then add the new numeric option
                        $energySelect.find('option[value="' + id + '"]').remove();
                        $energySelect.append('<option value="' + id + '" selected>' + name + '</option>');
                        $energySelect.prop('disabled', false).val(id);
                        try { $energySelect.selectpicker ? $energySelect.selectpicker('refresh') : $energySelect.val(id); } catch (err) { $energySelect.val(id); }

                        $('#energy_system_id_error').empty();

                        // After adding a new system, disable the generate button to avoid duplicate creates
                        checkGenerateButtonState();
                    } else {
                        $('#energy_system_id_error').html('Could not create Energy System.');
                        console.error('AJAX create failed', response);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var msg = 'Server error while creating Energy System.';
                    if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.message) msg = jqXHR.responseJSON.message;
                    $('#energy_system_id_error').html(msg);
                    console.error('AJAX error', textStatus, errorThrown, jqXHR);
                });
            });

            // Re-evaluate generate button whenever relevant selections change
            $('#selectedUserCommunity, #selectedCompound, #selectedEnergySystemType').on('change', function () {
                // allow some time for selectpicker/ajax updates to finish
                setTimeout(checkGenerateButtonState, 50);
            });

            // Run initial check on page load
            checkGenerateButtonState();

        });
    </script>


@endsection