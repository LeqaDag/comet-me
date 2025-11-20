@extends('layouts/layoutMaster')

@section('title', 'Create Agriculture Holder')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<style>
/* Custom Select2 Styling */
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px !important;
    padding: 8px 12px !important;
    border: 1px solid #d9dee3 !important;
    border-radius: 0.375rem !important;
    font-size: 0.875rem;
    line-height: 1.5;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select2-container--bootstrap-5 .select2-selection--single:focus,
.select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
    border-color: #86b7fe !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}

.select2-container--bootstrap-5 .select2-selection__rendered {
    padding: 0 !important;
    color: #212529;
    line-height: 22px !important;
}

.select2-container--bootstrap-5 .select2-selection__arrow {
    height: 36px !important;
    right: 8px !important;
}

.select2-dropdown {
    border: 1px solid #d9dee3 !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 8px 12px;
    font-size: 0.875rem;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #0d6efd !important;
    color: white !important;
}

.select2-container--bootstrap-5 .select2-results__option--selected {
    background-color: #e7f3ff !important;
    color: #0d6efd !important;
}

/* Enhanced form styling */
.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Card enhancements */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

/* Section headers */
.section-header {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

/* Loading state for dropdowns */
.select2-container--loading .select2-selection__rendered:after {
    content: " Loading...";
    color: #6c757d;
    font-style: italic;
}

/* Auto-load indicators */
.auto-load-indicator {
    font-size: 0.75rem;
    font-weight: normal;
    color: #6c757d;
}

/* Success state for auto-selected items */
.auto-selected .select2-selection--single {
    border-color: #28a745 !important;
    background-color: #f8fff9 !important;
}

/* Loading animation for select boxes */
.loading-households .select2-selection--single {
    border-color: #007bff !important;
    background-color: #f0f8ff !important;
    position: relative;
}

.loading-households .select2-selection__rendered {
    color: #007bff !important;
}

/* Pulse animation for loading state */
@keyframes pulse-loading {
    0% { opacity: 0.6; }
    50% { opacity: 1; }
    100% { opacity: 0.6; }
}

.loading-households .select2-selection--single {
    animation: pulse-loading 1.5s ease-in-out infinite;
}
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Agriculture Holders / </span> Create
</h4>

@if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Create New Agriculture Holder</h5>
                <p class="card-text text-muted">
                    <i class="fa-solid fa-info-circle"></i> Fill in the agriculture holder information
                </p>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-2"></i>Automatic System Configuration
                    </h6>
                    <p class="mb-2">When creating a new agriculture holder:</p>
                    <ul class="mb-0">
                        <li>Status will be automatically set to <strong>"Requested"</strong></li>
                        <li>Azolla units will be calculated as <strong>1 unit per 25 sheep</strong></li>
                        <li>Existing agriculture systems will be assigned based on herd size</li>
                        <li>Systems will be selected from available Azolla 20, 50, and 100 unit systems</li>
                    </ul>
                </div>

                <form action="{{ route('argiculture-user.store') }}" method="POST">
                    @csrf
                    
                    <!-- Community and Household Selection -->
                    <div class="mb-4">
                        <h6 class="section-header">Basic Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                            <label class="form-label" for="community_id">
                                Community <span class="text-danger">*</span>
                                <small class="text-muted ms-1">(Auto-loads households)</small>
                            </label>
                            <select class="form-select @error('community_id') is-invalid @enderror" 
                                    id="community_id" 
                                    name="community_id" required>
                                <option value="">Select Community</option>
                                @foreach($communities as $community)
                                    <option value="{{ $community->id }}" 
                                            {{ old('community_id') == $community->id ? 'selected' : '' }}>
                                        {{ $community->english_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('community_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label" for="household_id">
                                Household <span class="text-danger">*</span>
                                <small class="text-muted ms-1">(Auto-populated)</small>
                            </label>
                            <select class="form-select @error('household_id') is-invalid @enderror" 
                                    id="household_id" 
                                    name="household_id" required>
                                <option value="" disabled selected>Select Community First</option>
                            </select>
                            @error('household_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label" for="requested_date">Requested Date</label>
                            <input type="date" 
                                   class="form-control @error('requested_date') is-invalid @enderror" 
                                   id="requested_date" 
                                   name="requested_date" 
                                   value="{{ old('requested_date', date('Y-m-d')) }}">
                            @error('requested_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">Date when the agriculture system was requested</small>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Installation Type and System Cycle -->
                    <div class="mb-4">
                        <h6 class="section-header">Installation Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="agriculture_installation_types_id">Type of Installation</label>
                                <select class="form-select @error('agriculture_installation_types_id') is-invalid @enderror" id="agriculture_installation_types_id" name="agriculture_installation_types_id">
                                    <option value="">Select Installation Type</option>
                                    @if(!empty($agricultureInstallationTypes) && count($agricultureInstallationTypes) > 0)
                                        @foreach($agricultureInstallationTypes as $inst)
                                            <option value="{{ $inst->id }}" {{ old('agriculture_installation_types_id') == $inst->id ? 'selected' : '' }}>{{ $inst->english_name ?? $inst->arabic_name ?? ('#'.$inst->id) }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No installation types available</option>
                                    @endif
                                </select>
                                @error('agriculture_installation_types_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="agriculture_system_cycle_id">System Cycle</label>
                                <select class="form-select @error('agriculture_system_cycle_id') is-invalid @enderror" id="agriculture_system_cycle_id" name="agriculture_system_cycle_id">
                                    <option value="">Select System Cycle</option>
                                    @foreach($agricultureSystemCycles as $cycle)
                                        <option value="{{ $cycle->id }}" {{ old('agriculture_system_cycle_id') == $cycle->id ? 'selected' : '' }}>{{ $cycle->name }}</option>
                                    @endforeach
                                </select>
                                @error('agriculture_system_cycle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <!-- Livestock Information -->
                    <div class="mb-4">
                        <h6 class="section-header">Livestock Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                            <label class="form-label" for="azolla_unit">Azolla Units (Auto-calculated)</label>
                            <input type="number" 
                                   class="form-control @error('azolla_unit') is-invalid @enderror" 
                                   id="azolla_unit" 
                                   name="azolla_unit" 
                                   value="{{ old('azolla_unit') }}"
                                   readonly
                                   placeholder="Will be calculated based on herd size">
                            @error('azolla_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">1 unit per 25 sheeps</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" for="size_of_herds">Size of Herds (Total Sheep) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('size_of_herds') is-invalid @enderror" 
                                   id="size_of_herds" 
                                   name="size_of_herds" 
                                   value="{{ old('size_of_herds') }}"
                                   min="1"
                                   placeholder="Enter total number of sheep"
                                   required>
                            @error('size_of_herds')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">This will automatically calculate the systems and azolla units needed.</small>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Calculated Systems Information -->
                    <div class="mb-4" id="calculated-systems" style="display: none;">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                        <i class="bx bx-calculator text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-primary">System Assignment</h6>
                                        <small class="text-muted">These existing systems will be assigned to the user</small>
                                    </div>
                                </div>
                                <div id="systems-breakdown">
                                    <p class="text-muted mb-0">Enter herd size to see calculated systems</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Shared Herd Information -->
                    <div class="mb-4">
                        <h6 class="section-header">Shared Herd Information</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="shared_herd" name="shared_herd">
                            <label class="form-check-label" for="shared_herd">
                                Have you shared your herd with others?
                            </label>
                        </div>

                        <div id="shared_herd_details" style="display: none;">
                            <div class="mt-3">
                                <label class="form-label" for="number_of_people">Number of Households</label>
                                <input type="number" class="form-control" id="number_of_people" name="number_of_people" min="0" placeholder="Enter number of households" onchange="previewSharedHerdsCreate(this.value)">
                            </div>

                            <div id="shared_people_details" class="mt-3"></div>
                        </div>
                    </div>

                    <!-- Additional Animals Information -->
                    <div class="mb-4">
                        <h6 class="section-header">Additional Animals (Optional)</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label" for="size_of_goat">Number of Goats</label>
                                <input type="number" 
                                       class="form-control @error('size_of_goat') is-invalid @enderror" 
                                       id="size_of_goat" 
                                       name="size_of_goat" 
                                       value="{{ old('size_of_goat') }}"
                                       min="0"
                                       placeholder="Enter number of goats">
                                @error('size_of_goat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label" for="size_of_cow">Number of Cows</label>
                                <input type="number" 
                                       class="form-control @error('size_of_cow') is-invalid @enderror" 
                                       id="size_of_cow" 
                                       name="size_of_cow" 
                                       value="{{ old('size_of_cow') }}"
                                       min="0"
                                       placeholder="Enter number of cows">
                                @error('size_of_cow')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label" for="size_of_camel">Number of Camels</label>
                                <input type="number" 
                                       class="form-control @error('size_of_camel') is-invalid @enderror" 
                                       id="size_of_camel" 
                                       name="size_of_camel" 
                                       value="{{ old('size_of_camel') }}"
                                       min="0"
                                       placeholder="Enter number of camels">
                                @error('size_of_camel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="size_of_chicken">Number of Chickens</label>
                                <input type="number" 
                                       class="form-control @error('size_of_chicken') is-invalid @enderror" 
                                       id="size_of_chicken" 
                                       name="size_of_chicken" 
                                       value="{{ old('size_of_chicken') }}"
                                       min="0"
                                       placeholder="Enter number of chickens">
                                @error('size_of_chicken')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Area and Contribution Information -->
                    <div class="mb-4">
                        <h6 class="section-header">Area & Contribution Details</h6>
                        <div class="col-12 mb-3">
        <label class="form-label" for="area_of_installation">Which area will you build the system in?</label>
        <select id="area_of_installation" name="area_of_installation" class="form-select @error('area_of_installation') is-invalid @enderror">
            <option value="">Select area</option>
            <option value="A" {{ old('area_of_installation') == 'A' ? 'selected' : '' }}>A</option>
            <option value="B" {{ old('area_of_installation') == 'B' ? 'selected' : '' }}>B</option>
            <option value="C" {{ old('area_of_installation') == 'C' ? 'selected' : '' }}>C</option>
        </select>
        @error('area_of_installation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
                        <div class="row">
                            <!-- Area selection: choose which area to use -->
                            <div class="col-12 mb-2">
                                <label class="form-label">Choose Area Type</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="area_type" id="area_type_area" value="area" {{ (old('area_type', 'area') == 'area') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="area_type_area">Area</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="area_type" id="area_type_alternative" value="alternative" {{ (old('area_type') == 'alternative') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="area_type_alternative">Alternative area </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 area-number-col" style="display: {{ (old('area_type', 'area') == 'area') ? 'block' : 'none' }};">
                                <label class="form-label" for="area">Area</label>
                                <input type="number" step="0.01"
                                       class="form-control @error('area') is-invalid @enderror"
                                       id="area"
                                       name="area"
                                       value="{{ old('area') }}"
                                       placeholder="Enter area ">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 alternative-area-col" style="display: {{ (old('area_type') == 'alternative') ? 'block' : 'none' }};">
                                <label class="form-label" for="alternative_area">Alternative Area</label>
                                <textarea class="form-control @error('alternative_area') is-invalid @enderror"
                                          id="alternative_area"
                                          name="alternative_area"
                                          rows="2"
                                          placeholder="Enter alternative area">{{ old('alternative_area') }}</textarea>
                                @error('alternative_area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <h6 class="section-header">Additional Information</h6>
                        <div class="row">
                            <div class="col-12">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="4" 
                                      placeholder="Enter any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                    </div>


                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('argiculture-user.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-x me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-check me-1"></i>Create Agriculture Holder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
</div>

@endsection

@section('page-script')
<script>
// Build a JS map of households grouped by community for client-side filtering
var householdsByCommunity = {};
// Also build a quick lookup map by household id to make autofill fast
var householdsById = {};
@foreach($households as $household)
    householdsByCommunity[{{ $household->community_id }}] = householdsByCommunity[{{ $household->community_id }}] || [];
    householdsByCommunity[{{ $household->community_id }}].push({ id: {{ $household->id }}, name: {!! json_encode($household->english_name) !!}, size_of_herd: {!! json_encode($household->size_of_herd) !!} });
    householdsById[{{ $household->id }}] = { id: {{ $household->id }}, community_id: {{ $household->community_id }}, name: {!! json_encode($household->english_name) !!}, size_of_herd: {!! json_encode($household->size_of_herd) !!} };
@endforeach

$(document).ready(function() {
    // toastr removed per request; using console logging for lightweight feedback
    // (Previously configured toastr options were here)

    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%',
        allowClear: false,
        minimumResultsForSearch: 5, // Show search box only if 5+ options
        escapeMarkup: function(markup) { return markup; }
    });

    // Area type toggle: show numeric area or alternative text area
    function setAreaVisibility() {
        var type = $('input[name="area_type"]:checked').val() || 'area';
        if (type === 'alternative') {
            $('.area-number-col').hide();
            $('.alternative-area-col').show();
        } else {
            $('.area-number-col').show();
            $('.alternative-area-col').hide();
        }
    }
    $('input[name="area_type"]').on('change', setAreaVisibility);
    // initialize visibility
    setAreaVisibility();

    // Automatic Cascading dropdown: Community -> Household
    $('#community_id').on('change', function() {
        var communityId = $(this).val();
        var householdSelect = $('#household_id');
        
        // Clear household dropdown and show loading state
        householdSelect.empty().append('<option value="" disabled selected>Select Community</option>');
        
        if (communityId) {
            // Add loading option with spinner
            householdSelect.empty().append('<option value="" disabled selected> Loading households...</option>');
            
            // Make AJAX request to get households for selected community
            $.ajax({
                url: '/household/get_by_community/' + communityId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear loading state
                    householdSelect.empty();
                    
                    if (response.html) {
                        // Replace "Choose One..." with proper placeholder
                        var htmlContent = response.html.replace(
                            'option selected disabled>Choose One...',
                            'option value="" >Select Household'
                        );
                        householdSelect.html(htmlContent);
                        // Also update any shared-household selects generated so far
                        updateSharedHouseholdOptions(communityId, htmlContent);
                        
                        // Count available options (excluding placeholder)
                        var options = householdSelect.find('option:not([disabled])');
                        
                        if (options.length === 1) {
                            // If only one household, auto-select it and trigger change so autofill runs
                            var onlyVal = options.first().val();
                            householdSelect.val(onlyVal).trigger('change');
                            // Show success feedback (toastr removed) — log instead
                            console.log('Auto-selected household: ' + options.first().text());
                        }
                        
                    } else {
                        householdSelect.html('<option value="" disabled selected>No households available</option>');
                        console.log('No households available in this community');
                    }
                    
                    // Refresh Select2 to update UI
                    householdSelect.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: false,
                        minimumResultsForSearch: 5
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    householdSelect.empty().html('<option value="" disabled selected>❌ Error loading households</option>');
                    console.error('Failed to load households. Please try again.');
                    
                    // Refresh Select2 even on error
                    householdSelect.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        allowClear: false,
                        minimumResultsForSearch: 5
                    });
                }
            });
        } else {
            // No community selected
            householdSelect.empty().append('<option value="" disabled selected>Select Community First</option>');
            // Reset any shared-household selects
            updateSharedHouseholdOptions(null, null);
        }
    });

    // If there's an old community_id value (after validation error), trigger the change event
    @if(old('community_id'))
        $('#community_id').trigger('change');
        
        // Set the old household_id value after a short delay
        setTimeout(function() {
            $('#household_id').val('{{ old('household_id') }}').trigger('change');
        }, 500);
    @endif

    // Autofill herd size when a household is selected (main household)
    $('#household_id').on('change', function() {
        var hid = $(this).val();
        if (!hid) return;
        var hh = householdsById[hid];
        if (hh && (hh.size_of_herd !== undefined && hh.size_of_herd !== null)) {
            // populate the main herd size field and trigger calculation
            $('#size_of_herds').val(hh.size_of_herd).trigger('change');
            console.log('Herd size auto-filled from selected household: ' + hh.size_of_herd);
        }
    });

    // Autofill shared-household sheep inputs when a shared household is chosen
    $(document).on('change', '.shared-household-select', function() {
        var hid = $(this).val();
        var $row = $(this).closest('.row');
        var $sheepInput = $row.find('input[id$="_sheep"]');
        if (hid && householdsById[hid] && $sheepInput.length) {
            // only set if the field is empty to avoid overwriting user's manual input
            if (!$sheepInput.val()) {
                $sheepInput.val(householdsById[hid].size_of_herd || '');
            }
        }
    });

    // Azolla System Calculator
    function calculateAzollaSystems(herdSize) {
        if (!herdSize || herdSize <= 0) {
            $('#azolla_unit').val('');
            $('#calculated-systems').hide();
            return;
        }

        // Calculate azolla units (1 unit per 25 sheep)
        const azollaUnits = Math.ceil(herdSize / 25);
        $('#azolla_unit').val(azollaUnits);

        // Calculate systems needed
        const systems = calculateSystemsNeeded(parseInt(herdSize));
        
        // Show calculated systems
        displayCalculatedSystems(systems, azollaUnits, herdSize);
        $('#calculated-systems').show();
    }

    function calculateSystemsNeeded(herdSize) {
        const systems = [];
        let remainingSheep = herdSize;

        while (remainingSheep > 0) {
            if (remainingSheep >= 51) {
                // Use Azolla 100 System (supports 51-100 sheep)
                const sheepCovered = Math.min(100, remainingSheep);
                systems.push({
                    type: 'Azolla 100 Unit System',
                    capacity: '51-100 sheep',
                    sheepCovered: sheepCovered
                });
                remainingSheep -= sheepCovered;
            } else if (remainingSheep >= 21) {
                // Use Azolla 50 System (supports 21-50 sheep)
                const sheepCovered = Math.min(50, remainingSheep);
                systems.push({
                    type: 'Azolla 50 Unit System',
                    capacity: '21-50 sheep',
                    sheepCovered: sheepCovered
                });
                remainingSheep -= sheepCovered;
            } else {
                // Use Azolla 20 System (supports 1-20 sheep)
                systems.push({
                    type: 'Azolla 20 Unit System',
                    capacity: '1-20 sheep',
                    sheepCovered: remainingSheep
                });
                remainingSheep = 0;
            }
        }

        return systems;
    }

    function displayCalculatedSystems(systems, azollaUnits, herdSize) {
        // Create summary stats with icons
        let html = `
            <div class="row g-2 mb-3">
                <div class="col-4">
                    <div class="text-center p-2 rounded border bg-white">
                        <div class="text-primary fw-bold fs-5">${herdSize}</div>
                        <small class="text-muted">Sheep</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-2 rounded border bg-white">
                        <div class="text-success fw-bold fs-5">${azollaUnits}</div>
                        <small class="text-muted">Azolla Units</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center p-2 rounded border bg-white">
                        <div class="text-info fw-bold fs-5">${systems.length}</div>
                        <small class="text-muted">Systems</small>
                    </div>
                </div>
            </div>
        `;

        if (systems.length > 0) {
            html += `<div class="mb-2"><small class="text-muted fw-semibold">Existing systems to be assigned:</small></div>`;
            
            systems.forEach((system, index) => {
                let badgeColor = system.type.includes('20') ? 'success' : 
                               system.type.includes('50') ? 'warning' : 'info';
                               
                html += `
                    <div class="d-flex align-items-center justify-content-between p-2 mb-1 rounded border bg-white">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-${badgeColor} me-2">${index + 1}</span>
                            <div>
                                <div class="fw-medium">${system.type}</div>
                                <small class="text-muted">${system.capacity}</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border">
                            ${system.sheepCovered} sheep
                        </span>
                    </div>
                `;
            });
        }

        $('#systems-breakdown').html(html);
    }

    // Auto-calculate when herd size changes
    $('#size_of_herds').on('input change', function() {
        const herdSize = parseInt($(this).val()) || 0;
        calculateAzollaSystems(herdSize);
    });

    // Calculate on page load if there's an old value
    @if(old('size_of_herds'))
        calculateAzollaSystems({{ old('size_of_herds') }});
    @endif

    // Toggle shared herd details (delegated + initial state)
    $(document).on('change', '#shared_herd', function() {
        console.log('create: shared_herd changed ->', $(this).is(':checked'));
        if ($(this).is(':checked')) {
            $('#shared_herd_details').slideDown();
            $('#number_of_people').prop('disabled', false);
        } else {
            $('#shared_herd_details').slideUp();
            $('#shared_people_details').empty();
            $('#number_of_people').val('');
            $('#number_of_people').prop('disabled', true);
        }
    });
    // Ensure initial visibility matches checkbox state and set disabled state for number input
    if ($('#shared_herd').length) {
        if ($('#shared_herd').is(':checked')) {
            $('#shared_herd_details').show();
            $('#number_of_people').prop('disabled', false);
        } else {
            $('#shared_herd_details').hide();
            $('#number_of_people').prop('disabled', true);
        }
    }

    // Generate dropdowns and input fields based on number of households
    $(document).on('input change', '#number_of_people', function() {
        console.log('create: number_of_people ->', $(this).val());
        const numberOfPeople = parseInt($(this).val()) || 0;
        const container = $('#shared_people_details');
        container.empty();
        const selectedCommunity = $('#community_id').val();

        // If user entered a number, ensure shared_herd is checked and details visible
        if (numberOfPeople > 0) {
            $('#shared_herd').prop('checked', true);
            $('#shared_herd_details').show();
        }

        for (let i = 1; i <= numberOfPeople; i++) {
            const optionsHtml = buildHouseholdOptions(selectedCommunity);
            const householdDetails = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="household_${i}_name">Household ${i} Name</label>
                        <select class="form-select shared-household-select" id="household_${i}_name" name="household_${i}_name">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="household_${i}_sheep">Number of Sheep</label>
                        <input type="number" class="form-control" id="household_${i}_sheep" name="household_${i}_sheep" min="1" placeholder="Enter number of sheep">
                    </div>
                </div>
            `;
            container.append(householdDetails);
            // Initialize select2 for the new select
            $(`#household_${i}_name`).select2({ theme: 'bootstrap-5', width: '100%', minimumResultsForSearch: 5 });
        }

        if (numberOfPeople === 0) {
            console.log('create: number_of_people is 0');
        } else {
            console.log('create: generated', numberOfPeople, 'rows');
        }
    });

    // Build options HTML for a given community id using householdsByCommunity map
    function buildHouseholdOptions(communityId) {
        let html = '';
        if (!communityId) {
            html += '<option value="" disabled selected>Select Community First</option>';
            return html;
        }

        const list = householdsByCommunity[communityId] || [];
        if (list.length === 0) {
            html += '<option value="" disabled selected>No households found</option>';
            return html;
        }

        html += '<option value="" disabled selected>Select Household</option>';
        list.forEach(function(h) {
            html += `<option value="${h.id}">${h.name}</option>`;
        });
        return html;
    }

    // Update existing shared-household selects when community changes or on request
    function updateSharedHouseholdOptions(communityId, optionsHtmlFromAjax) {
        const selects = $('.shared-household-select');
        if (selects.length === 0) return;

        selects.each(function() {
            const $s = $(this);
            if (optionsHtmlFromAjax) {
                $s.html(optionsHtmlFromAjax);
            } else {
                // Build from local map
                $s.html(buildHouseholdOptions(communityId));
            }

            // Refresh select2
            $s.trigger('change.select2');
            $s.select2({ theme: 'bootstrap-5', width: '100%', minimumResultsForSearch: 5 });
        });
    }

    // If number_of_people has a value on load (old input), trigger change to render preview rows
    @if(old('number_of_people'))
        $('#number_of_people').val('{{ old('number_of_people') }}');
        setTimeout(function(){ $('#number_of_people').trigger('change'); }, 200);
    @endif

    // Enhanced validation for shared sheep count
    $('form').on('submit', function(event) {
        try {
            var chosen = $('input[name="area_type"]:checked').val() || 'area';
            if (chosen === 'alternative') {
                $('#area').val($('#alternative_area').val());
            }
        } catch (e) { console.error('area copy on submit error', e); }

        const herdSize = parseInt($('#size_of_herds').val()) || 0;
        let totalSharedSheep = 0;
        let errorMessage = '';

        // Calculate total shared sheep
        $('#shared_people_details input[id^="household_"][id$="_sheep"]').each(function() {
            totalSharedSheep += parseInt($(this).val()) || 0;
        });

        // Check if total shared sheep exceeds herd size
        if (totalSharedSheep >= herdSize) {
            errorMessage = `Total shared sheep (${totalSharedSheep}) must be less than the herd size (${herdSize}).`;
        }

        // Display error message dynamically
        if (errorMessage) {
            event.preventDefault(); // Prevent form submission
            if (!$('#shared_herd_error').length) {
                $('#shared_herd_details').append('<div id="shared_herd_error" class="text-danger mt-2"></div>');
            }
            $('#shared_herd_error').text(errorMessage);
        } else {
            $('#shared_herd_error').remove(); // Remove error message if validation passes
        }
    });
});
</script>
@endsection

@section('page-fallback-script')
<script>
// Plain JS fallback for environments where jQuery may not be available or handlers fail.
;(function(){
    function buildHouseholdOptionsPlain(communityId){
        if(!communityId) return '<option value="" disabled selected>Select Community First</option>';
        var list = householdsByCommunity[communityId] || [];
        if(list.length === 0) return '<option value="" disabled selected>No households found</option>'; 
        var html = '<option value="" disabled selected>Select Household</option>';
        for(var i=0;i<list.length;i++){ html += '<option value="'+list[i].id+'">'+(list[i].name)+'</option>'; }
        return html;
    }

    function generateSharedRowsPlain(n){
        var container = document.getElementById('shared_people_details');
        if(!container) return;
        container.innerHTML = '';
        var community = (document.getElementById('community_id') || {}).value || null;
        for(var i=1;i<=n;i++){
            var wrapper = document.createElement('div'); wrapper.className = 'row mb-3';
            var col1 = document.createElement('div'); col1.className='col-md-6';
            var lbl = document.createElement('label'); lbl.className='form-label'; lbl.setAttribute('for','household_'+i+'_name'); lbl.textContent = 'Household '+i+' Name';
            var sel = document.createElement('select'); sel.className='form-select shared-household-select'; sel.id='household_'+i+'_name'; sel.name='household_'+i+'_name';
            sel.innerHTML = buildHouseholdOptionsPlain(community);
            // Autofill sibling sheep input when a household is selected (plain JS fallback)
            sel.addEventListener('change', function(){
                try{
                    var v = this.value;
                    var idx = this.id.match(/household_(\d+)_name/);
                    var target = document.getElementById('household_'+(idx?idx[1]:'')+'_sheep');
                    if(v && typeof householdsById !== 'undefined' && householdsById[v] && target && !target.value){
                        target.value = householdsById[v].size_of_herd || '';
                    }
                }catch(e){console.error(e)}
            });
            col1.appendChild(lbl); col1.appendChild(sel);

            var col2 = document.createElement('div'); col2.className='col-md-6';
            var lbl2 = document.createElement('label'); lbl2.className='form-label'; lbl2.setAttribute('for','household_'+i+'_sheep'); lbl2.textContent='Number of Sheep';
            var inp = document.createElement('input'); inp.type='number'; inp.className='form-control'; inp.id='household_'+i+'_sheep'; inp.name='household_'+i+'_sheep'; inp.min='1'; inp.placeholder='Enter number of sheep';
            col2.appendChild(lbl2); col2.appendChild(inp);

            wrapper.appendChild(col1); wrapper.appendChild(col2);
            container.appendChild(wrapper);
        }
    }

    // Attach plain listener
    var numInput = document.getElementById('number_of_people');
    if(numInput){
        numInput.addEventListener('input', function(e){
            var v = parseInt(e.target.value) || 0;
            if(v>0){
                var chk = document.getElementById('shared_herd'); if(chk) chk.checked = true;
                generateSharedRowsPlain(v);
            } else {
                var container = document.getElementById('shared_people_details'); if(container) container.innerHTML='';
            }
        });
        // expose global quick fallback
        window.previewSharedHerdsCreate = function(val){
            try{ var v = parseInt(val) || 0; if(v>0){ document.getElementById('shared_herd').checked = true; generateSharedRowsPlain(v); } else { document.getElementById('shared_people_details').innerHTML=''; } }catch(e){console.error(e)}
        };
    }
})();
</script>
@endsection