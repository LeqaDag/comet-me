@extends('layouts/layoutMaster')

@section('title', 'Edit Agriculture Holder')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<style>
/* reuse styles from create view (kept minimal here) */
.section-header{font-size:1rem;font-weight:600;color:#495057;border-bottom:2px solid #e9ecef;padding-bottom:.5rem;margin-bottom:1rem}

/* Elegant dropdown borders and focus states for native selects and Select2 */
.form-select,
.shared-household-select,
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px;
    padding: 8px 12px;
    border: 1px solid #d9dee3;
    border-radius: 0.375rem;
    background: linear-gradient(180deg, #ffffff 0%, #fbfbfb 100%);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, transform 0.08s;
    font-size: 0.9rem;
}

.form-select:hover,
.select2-container--bootstrap-5 .select2-selection--single:hover {
    border-color: #cfd8df;
}

.form-select:focus,
.form-select:focus-visible,
.select2-container--bootstrap-5 .select2-selection--single:focus,
.select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
    border-color: #86b7fe !important;
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.12) !important;
    outline: none;
}

.select2-dropdown {
    border: 1px solid #d9dee3 !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important;
}

/* Donor panel styles */
.list-group-item .badge { width: 28px; height: 28px; display:inline-flex; align-items:center; justify-content:center; border-radius:6px; }
.list-group-item .fw-medium { font-size: 0.95rem; }
.card-border-donor { border-left: 4px solid #0d6efd; }

/* subtle accent when a Select2 has an auto-selected value */
.auto-selected .select2-selection--single {
    border-color: #28a745 !important;
    background-color: #f8fff9 !important;
}

/* Improve appearance of disabled selects */
.form-select[disabled],
.select2-container--bootstrap-5 .select2-selection--single[aria-disabled="true"] {
    background-color: #f8f9fa;
    color: #6c757d;
}

/* Helper for smaller selects inside rows */
.row .form-select, .row .select2-container--bootstrap-5 { font-size: 0.9rem; }
</style>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
@endsection

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Agriculture Holders / </span> Edit
</h4>

@if(session()->has('error'))
    <div class="alert alert-danger">{{ session()->get('error') }}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Agriculture Holder</h5>
                <p class="card-text text-muted"><i class="fa-solid fa-info-circle"></i> Update the agriculture holder information</p>
            </div>
            <div class="card-body">
                <form action="{{ route('argiculture-user.update', $agricultureUser->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information (community/household/requested_date) -->
                    <div class="mb-4">
                        <h6 class="section-header">Basic Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="community_id">Community</label>
                                <select class="form-select @error('community_id') is-invalid @enderror" id="community_id" name="community_id">
                                    <option value="">Select Community</option>
                                    @foreach($communities as $community)
                                        <option value="{{ $community->id }}" {{ (old('community_id', optional($agricultureUser->community)->id) == $community->id) ? 'selected' : '' }}>
                                            {{ $community->english_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('community_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="household_id">Household</label>
                                <select class="form-select @error('household_id') is-invalid @enderror" id="household_id" name="household_id">
                                    <option value="">Select Household</option>
                                    @foreach($households as $household)
                                        <option value="{{ $household->id }}" {{ (old('household_id', optional($agricultureUser->household)->id) == $household->id) ? 'selected' : '' }}>
                                            {{ $household->english_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('household_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="requested_date">Requested Date</label>
                                <input type="date" disabled class="form-control @error('requested_date') is-invalid @enderror" id="requested_date" name="requested_date" value="{{ old('requested_date', optional($agricultureUser)->requested_date ? $agricultureUser->requested_date->format('Y-m-d') : date('Y-m-d')) }}">
                                @error('requested_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text"><small class="text-muted">Date when the agriculture system was requested</small></div>
                            </div>
                        </div>
                    </div>


                    <!-- Livestock Information -->
                    <div class="mb-4">
                        <h6 class="section-header">Livestock Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="azolla_unit">Azolla Units (Auto-calculated)</label>
                                <input type="number" class="form-control @error('azolla_unit') is-invalid @enderror" id="azolla_unit" name="azolla_unit" value="{{ old('azolla_unit', $agricultureUser->azolla_unit ?? '') }}" readonly placeholder="Will be calculated based on herd size">
                                @error('azolla_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text"><small class="text-muted">1 unit per 25 sheeps</small></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="size_of_herds">Size of Herds (Total Sheep)</label>
                                <input type="number" class="form-control @error('size_of_herds') is-invalid @enderror" id="size_of_herds" name="size_of_herds" value="{{ old('size_of_herds', $agricultureUser->size_of_herds ?? '') }}" min="0" placeholder="Enter total number of sheep">
                                @error('size_of_herds')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text"><small class="text-muted">This will automatically calculate the systems and azolla units needed.</small></div>
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
                            <input class="form-check-input" type="checkbox" id="shared_herd" name="shared_herd" onclick="toggleSharedHerd(this)" {{ (isset($agricultureUser->agricultureSharedHolders) && $agricultureUser->agricultureSharedHolders->count()>0) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shared_herd">
                                Have you shared your herd with others?
                            </label>
                        </div>

                        <div id="shared_herd_details" style="display: {{ (isset($agricultureUser->agricultureSharedHolders) && $agricultureUser->agricultureSharedHolders->count()>0) ? 'block' : 'none' }};">
                            <div class="mt-3">
                                <label class="form-label" for="number_of_people">Number of Households</label>
                                <input type="number" class="form-control" id="number_of_people" name="number_of_people" min="0" placeholder="Enter number of households" value="{{ old('number_of_people', (isset($agricultureUser->agricultureSharedHolders) ? $agricultureUser->agricultureSharedHolders->count() : '')) }}" onchange="previewSharedHerdsEdit(this.value)">
                            </div>

                            <div id="shared_people_details" class="mt-3">
                                {{-- Will be populated by JS. Existing entries will be handled by existingShared JS array below. --}}
                            </div>
                        </div>
                    </div>
                    <!-- Installation Type and System Cycle -->
                    <div class="mb-4">
                        <h6 class="section-header">Installation Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="agriculture_installation_type_id">Type of Installation</label>
                                <select class="form-select @error('agriculture_installation_type_id') is-invalid @enderror" id="agriculture_installation_type_id" name="agriculture_installation_type_id">
                                    <option value="">Select Installation Type</option>
                                    @if(!empty($agricultureInstallationTypes) && count($agricultureInstallationTypes) > 0)
                                        @foreach($agricultureInstallationTypes as $inst)
                                            <option value="{{ $inst->id }}" {{ old('agriculture_installation_type_id', optional($agricultureUser)->agriculture_installation_type_id) == $inst->id ? 'selected' : '' }}>{{ $inst->english_name ?? $inst->arabic_name ?? ('#'.$inst->id) }}</option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>No installation types available</option>
                                    @endif
                                </select>
                                @error('agriculture_installation_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="agriculture_system_cycle_id">System Cycle</label>
                                <select class="form-select @error('agriculture_system_cycle_id') is-invalid @enderror" id="agriculture_system_cycle_id" name="agriculture_system_cycle_id">
                                    <option value="">Select System Cycle</option>
                                    @foreach($agricultureSystemCycles as $cycle)
                                        <option value="{{ $cycle->id }}" {{ (old('agriculture_system_cycle_id', $agricultureUser->agriculture_system_cycle_id ?? '') == $cycle->id) ? 'selected' : '' }}>{{ $cycle->name }}</option>
                                    @endforeach
                                </select>
                                @error('agriculture_system_cycle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Animals Information -->
                    <div class="mb-4">
                        <h6 class="section-header">Additional Animals (Optional)</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="size_of_cow">Number of Cows</label>
                                <input type="number" 
                                       class="form-control @error('size_of_cow') is-invalid @enderror" 
                                       id="size_of_cow" 
                                       name="size_of_cow" 
                                       value="{{ old('size_of_cow', $agricultureUser->size_of_cow ?? '') }}"
                                       min="0"
                                       placeholder="Enter number of cows">
                                @error('size_of_cow')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label" for="size_of_camel">Number of Camels</label>
                                <input type="number" 
                                       class="form-control @error('size_of_camel') is-invalid @enderror" 
                                       id="size_of_camel" 
                                       name="size_of_camel" 
                                       value="{{ old('size_of_camel', $agricultureUser->size_of_camel ?? '') }}"
                                       min="0"
                                       placeholder="Enter number of camels">
                                @error('size_of_camel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="size_of_chicken">Number of Chickens</label>
                                <input type="number" 
                                       class="form-control @error('size_of_chicken') is-invalid @enderror" 
                                       id="size_of_chicken" 
                                       name="size_of_chicken" 
                                       value="{{ old('size_of_chicken', $agricultureUser->size_of_chicken ?? '') }}"
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
            <option value="A" {{ old('area_of_installation', optional($agricultureUser)->area_of_installation) == 'A' ? 'selected' : '' }}>A</option>
            <option value="B" {{ old('area_of_installation', optional($agricultureUser)->area_of_installation) == 'B' ? 'selected' : '' }}>B</option>
            <option value="C" {{ old('area_of_installation', optional($agricultureUser)->area_of_installation) == 'C' ? 'selected' : '' }}>C</option>
        </select>
        @error('area_of_installation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
                        <div class="row">
                            <!-- Area selection: choose which area to use -->
                            <div class="col-12 mb-2">
                                <label class="form-label">Choose Area Type</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="area_type" id="area_type_area" value="area" {{ (old('area_type', (isset($agricultureUser->alternative_area) && $agricultureUser->alternative_area) ? 'alternative' : 'area') == 'area') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="area_type_area">Area</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="area_type" id="area_type_alternative" value="alternative" {{ (old('area_type', (isset($agricultureUser->alternative_area) && $agricultureUser->alternative_area) ? 'alternative' : 'area') == 'alternative') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="area_type_alternative">Alternative area </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 area-number-col" style="display: {{ (old('area_type', (isset($agricultureUser->alternative_area) && $agricultureUser->alternative_area) ? 'alternative' : 'area') == 'area') ? 'block' : 'none' }};">
                                <label class="form-label" for="area">Area</label>
                                <input type="number" step="0.01"
                                       class="form-control @error('area') is-invalid @enderror"
                                       id="area"
                                       name="area"
                                       value="{{ old('area', $agricultureUser->area ?? '') }}"
                                       placeholder="Enter area ">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 alternative-area-col" style="display: {{ (old('area_type', (isset($agricultureUser->alternative_area) && $agricultureUser->alternative_area) ? 'alternative' : 'area') == 'alternative') ? 'block' : 'none' }};">
                                <label class="form-label" for="alternative_area">Alternative Area</label>
                                <textarea class="form-control @error('alternative_area') is-invalid @enderror"
                                          id="alternative_area"
                                          name="alternative_area"
                                          rows="2"
                                          placeholder="Enter alternative area">{{ old('alternative_area', $agricultureUser->alternative_area ?? '') }}</textarea>
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
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" placeholder="Enter any additional notes...">{{ old('notes', $agricultureUser->notes ?? '') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <div class="w-100 mb-3">
                            <div class="card border shadow-sm">
                                <div class="card-body py-3">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <div>
                                            <h6 class="mb-1">Donors</h6>
                                            <small class="text-muted">Add one or more donors who support this holder</small>
                                        </div>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="donor_herd" name="donor_herd" onclick="toggleDonorHerd(this)" {{ (isset($agricultureUser->agricultureHolderDonors) && $agricultureUser->agricultureHolderDonors->count()>0) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="donor_herd">Enable</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="donor_herd_details" style="display: {{ (isset($agricultureUser->agricultureHolderDonors) && $agricultureUser->agricultureHolderDonors->count()>0) ? 'block' : 'none' }};">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-8">
                                                <label class="form-label visually-hidden" for="new_donor_select">Choose donor</label>
                                                <select id="new_donor_select" class="form-select">
                                                    <option value="">Search or choose donor...</option>
                                                    @if(isset($donors) && $donors->count())
                                                        @foreach($donors as $donor)
                                                            <option value="{{ $donor->id }}">{{ $donor->donor_name ?? $donor->donorName ?? ($donor->name ?? 'Donor #' . $donor->id) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4 d-flex gap-2">
                                                <button type="button" id="add_donor_btn" class="btn btn-primary w-100">Add Donor</button>
                                            </div>
                                        </div>

                                        <div id="existing_donors_list" class="list-group list-group-flush mt-3">
                                            @if(isset($agricultureUser->agricultureHolderDonors) && $agricultureUser->agricultureHolderDonors->count()>0)
                                                @foreach($agricultureUser->agricultureHolderDonors as $hd)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center" data-donor-id="{{ $hd->donor_id }}">
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <div class="fw-medium">{{ optional($hd->donor)->donor_name ?? 'Unknown' }}</div>
                                                                <small class="text-muted">{{ optional($hd->donor)->email ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-outline-danger remove-donor-item"><i class="bx bx-trash"></i></button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div id="donor_hidden_inputs"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                        <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('argiculture-user.index') }}" class="btn btn-outline-secondary"><i class="bx bx-x me-1"></i>Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i>Update Agriculture Holder</button>
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
@foreach($households as $household)
    householdsByCommunity[{{ $household->community_id }}] = householdsByCommunity[{{ $household->community_id }}] || [];
    householdsByCommunity[{{ $household->community_id }}].push({ id: {{ $household->id }}, name: {!! json_encode($household->english_name) !!} });
@endforeach

// Global toggle functions used by inline onclick attributes
    function toggleSharedHerd(checkbox) {
    try {
        var details = document.getElementById('shared_herd_details');
        var ppl = document.getElementById('shared_people_details');
        var num = document.getElementById('number_of_people');

        if (checkbox && checkbox.checked) {
            if (details) details.style.display = 'block';
            if (num) num.disabled = false;
        } else {
            if (details) details.style.display = 'none';
            if (ppl) ppl.innerHTML = '';
            if (num) { num.value = ''; num.disabled = true; }
        }
    } catch (e) { console.error('toggleSharedHerd error', e); }
}
window.toggleSharedHerd = toggleSharedHerd;

function toggleDonorHerd(checkbox) {
    try {
        if (checkbox && checkbox.checked) {
            document.getElementById('donor_herd_details').style.display = 'block';
        } else {
            var details = document.getElementById('donor_herd_details'); if (details) details.style.display = 'none';
            var list = document.getElementById('existing_donors_list'); if (list) list.innerHTML = '';
            var hidden = document.getElementById('donor_hidden_inputs'); if (hidden) hidden.innerHTML = '';
        }
    } catch (e) { console.error('toggleDonorHerd error', e); }
}
window.toggleDonorHerd = toggleDonorHerd;

var householdsAll = [];
try {
    Object.keys(householdsByCommunity).forEach(function(k){
        var list = householdsByCommunity[k] || [];
        for (var j = 0; j < list.length; j++) { householdsAll.push(list[j]); }
    });
} catch (e) { console.error('Error building householdsAll', e); }

function findHouseholdNameById(id){
    try {
        id = parseInt(id);
        for(var i=0;i<householdsAll.length;i++){ if(parseInt(householdsAll[i].id) === id) return householdsAll[i].name; }
    } catch (e) { console.error('findHouseholdNameById error', e); }
    return null;
}

$(document).ready(function() {
    $('.form-select').select2({ theme: 'bootstrap-5', width: '100%', minimumResultsForSearch: 5 });

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

    // Ensure alternative area value is copied to area input on submit when alternative selected
    $('form').on('submit', function() {
        try {
            var chosen = $('input[name="area_type"]:checked').val() || 'area';
            if (chosen === 'alternative') {
                $('#area').val($('#alternative_area').val());
            }
        } catch (e) { console.error('area copy on submit error', e); }
    });

    // If there's an old or existing community_id value, trigger change to populate households
    @if(old('community_id') || (isset($agricultureUser) && $agricultureUser->community_id))
        $('#community_id').val('{{ old('community_id', $agricultureUser->community_id ?? '') }}').trigger('change');
        setTimeout(function(){ $('#household_id').val('{{ old('household_id', $agricultureUser->household_id ?? '') }}').trigger('change'); }, 500);
    @endif

    // Full Azolla & Systems calculator (copied/adapted from create view)
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
                    sheepCovered: sheepCovered,
                    system_type: 'azolla_100'
                });
                remainingSheep -= sheepCovered;
            } else if (remainingSheep >= 21) {
                // Use Azolla 50 System (supports 21-50 sheep)
                const sheepCovered = Math.min(50, remainingSheep);
                systems.push({
                    type: 'Azolla 50 Unit System',
                    capacity: '21-50 sheep',
                    sheepCovered: sheepCovered,
                    system_type: 'azolla_50'
                });
                remainingSheep -= sheepCovered;
            } else {
                // Use Azolla 20 System (supports 1-20 sheep)
                systems.push({
                    type: 'Azolla 20 Unit System',
                    capacity: '1-20 sheep',
                    sheepCovered: remainingSheep,
                    system_type: 'azolla_20'
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

    // Calculate on page load if there's an old value or existing holder value
    @if(old('size_of_herds') || (isset($agricultureUser) && $agricultureUser->size_of_herds))
        calculateAzollaSystems({{ old('size_of_herds', $agricultureUser->size_of_herds ?? 0) }});
    @endif
    
    // Build options HTML for a given community id using householdsByCommunity map
    function buildHouseholdOptions(communityId) {
        let html = '';
        let list = [];
        if (communityId) {
            list = householdsByCommunity[communityId] || [];
        } else {
            // fallback to all households
            list = householdsAll || [];
        }

        if (!list || list.length === 0) {
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
    function updateSharedHouseholdOptions(communityId) {
        const selects = $('.shared-household-select');
        if (selects.length === 0) return;

        // Build options html once
        var optionsHtml = buildHouseholdOptions(communityId);
        selects.each(function() {
            const $s = $(this);
            // update options preserving current selection where possible
            var current = $s.val();
            $s.html(optionsHtml);
            // If current not in new options, append it if known
            if (current && $s.find('option[value="' + current + '"]').length === 0) {
                var name = findHouseholdNameById(current) || ('Household #' + current);
                $s.append($('<option>', { value: current, text: name }));
            }
            // refresh select2 if initialized, otherwise initialize once
            if ($s.data('select2')) {
                $s.trigger('change.select2');
            } else {
                $s.select2({ theme: 'bootstrap-5', width: '100%', minimumResultsForSearch: 5 });
            }
        });
    }

    // When community changes, refresh household lists used by shared rows
    $('#community_id').on('change', function() {
        var communityId = $(this).val();
        updateSharedHouseholdOptions(communityId);
    });

    // Prepare existingShared entries for prefilling shared rows
    var existingShared = [];
    @if(isset($agricultureUser->agricultureSharedHolders) && $agricultureUser->agricultureSharedHolders->count()>0)
        existingShared = {!! json_encode($agricultureUser->agricultureSharedHolders->map(function($s){ return ['household_id'=>$s->household_id,'size_of_herds'=>$s->size_of_herds]; })->values()) !!};
    @endif

    // Debounce helper to avoid rapid-heavy DOM ops
    function debounce(fn, wait) {
        var t;
        return function() { var ctx = this, args = arguments; clearTimeout(t); t = setTimeout(function(){ fn.apply(ctx, args); }, wait); };
    }

    // Generate shared household rows when number changes (debounced)
    var generateSharedRows = debounce(function() {
        try {
            const numberOfPeople = Math.min(200, parseInt($('#number_of_people').val()) || 0); // safety cap
            const container = $('#shared_people_details');
            container.empty();
            const selectedCommunity = $('#community_id').val();

            if (numberOfPeople > 0) {
                $('#shared_herd').prop('checked', true);
                $('#shared_herd_details').show();
            }

            if (numberOfPeople === 0) return;

            // Build all rows HTML first to minimize reflows
            var rowsHtml = '';
            var optionsForCommunity = buildHouseholdOptions(selectedCommunity);

            for (var i = 1; i <= numberOfPeople; i++) {
                var pre = existingShared[i-1] || {};
                var sheepCount = pre.size_of_herds || '';
                rowsHtml += `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="household_${i}_name">Household ${i} Name</label>
                            <select class="form-select shared-household-select" id="household_${i}_name" name="household_${i}_name">
                                ${optionsForCommunity}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="household_${i}_sheep">Number of Sheep</label>
                            <input type="number" class="form-control" id="household_${i}_sheep" name="household_${i}_sheep" min="0" value="${sheepCount}" placeholder="Enter number of sheep">
                        </div>
                    </div>
                `;
            }

            container.append(rowsHtml);

            // Initialize select2 for new selects and set selected values (append missing option if necessary)
            for (var i = 1; i <= numberOfPeople; i++) {
                var sel = $(`#household_${i}_name`);
                if (!sel.length) continue;
                if (!sel.data('select2')) {
                    sel.select2({ theme: 'bootstrap-5', width: '100%', minimumResultsForSearch: 5 });
                }
                var pre = existingShared[i-1] || {};
                var selectedHousehold = pre.household_id || '';
                if (selectedHousehold) {
                    if (sel.find('option[value="' + selectedHousehold + '"]').length === 0) {
                        var name = findHouseholdNameById(selectedHousehold) || ('Household #' + selectedHousehold);
                        sel.append($('<option>', { value: selectedHousehold, text: name }));
                    }
                    sel.val(selectedHousehold).trigger('change');
                }
            }
        } catch (e) { console.error('generateSharedRows error', e); }
    }, 120);

    $(document).on('input change', '#number_of_people', generateSharedRows);

    // Plain-JS helper exposed for onchange attribute
    window.previewSharedHerdsEdit = function(value) {
        try {
            const v = parseInt(value) || 0;
            $('#number_of_people').val(v);
            $('#number_of_people').trigger('change');
        } catch (e) { console.error(e); }
    };

    // If number_of_people has a value on load, trigger change to render preview rows
    var numPeopleVal = parseInt($('#number_of_people').val()) || 0;
    if (numPeopleVal > 0) {
        $('#number_of_people').trigger('change');
    }

    // --- Donor add/remove flow ---
    function rebuildHiddenDonorInputs() {
        var container = $('#donor_hidden_inputs');
        container.empty();
        $('#existing_donors_list .list-group-item').each(function(index) {
            var id = $(this).data('donor-id');
            var input = $('<input>').attr('type','hidden').attr('name','donor_' + (index+1) + '_id').val(id);
            container.append(input);
        });
        // ensure number_of_donors hidden input exists
        var count = $('#existing_donors_list .list-group-item').length;
        var countInput = $('#number_of_donors_hidden');
        if (countInput.length === 0) {
            container.append('<input type="hidden" id="number_of_donors_hidden" name="number_of_donors" value="' + count + '">');
        } else {
            countInput.val(count);
        }
    }

    $('#add_donor_btn').on('click', function() {
        var sel = $('#new_donor_select');
        var id = sel.val();
        var text = sel.find('option:selected').text();
        if (!id) { return; }
        // avoid duplicates
        if ($('#existing_donors_list .list-group-item[data-donor-id="' + id + '"]').length > 0) {
            toastr.warning('This donor is already added');
            return;
        }
        var item = $('<div>').addClass('list-group-item d-flex justify-content-between align-items-center').attr('data-donor-id', id);
        item.append($('<div>').text(text));
        var btn = $('<button>').attr('type','button').addClass('btn btn-link text-danger remove-donor-item').html('<i class="bx bx-trash"></i>');
        item.append(btn);
        $('#existing_donors_list').append(item);
        rebuildHiddenDonorInputs();
    });

    $(document).on('click', '.remove-donor-item', function() {
        $(this).closest('.list-group-item').remove();
        rebuildHiddenDonorInputs();
    });

    // initialize hidden inputs from any server-rendered donors
    rebuildHiddenDonorInputs();
});
</script>
@endsection