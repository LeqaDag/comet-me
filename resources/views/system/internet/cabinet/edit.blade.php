@extends('layouts/layoutMaster')

@section('title', 'edit network cabinet')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    } 
</style>

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$internetSystem->system_name}}
    <span class="text-muted fw-light">Network Cabinets </span> 
</h4>

@php
    $componentTypes = [
        'App\Models\Router' => ['label' => 'Router', 'data' => $routers],
        'App\Models\Switche' => ['label' => 'Switch', 'data' => $switchs],
        'App\Models\CameraShelve' => ['label' => 'CameraShelve', 'data' => $cameraShelves],
        'App\Models\PatchPanel' => ['label' => 'PatchPanel', 'data' => $patchPaneles],
        'App\Models\AirPatchPanel' => ['label' => 'AirPatchPanel', 'data' => $airPatchPaneles],
        'App\Models\PatchCord' => ['label' => 'PatchCord', 'data' => $patchCords],
        'App\Models\PowerDistributor' => ['label' => 'PowerDistributor', 'data' => $powerDistributors],
        'App\Models\Keystone' => ['label' => 'Keystone', 'data' => $Keystones],
        'App\Models\NvrCamera' => ['label' => 'NvrCamera', 'data' => $nvrs],
    ];
@endphp

    <form method="POST" action="{{ route('components.storeComponents', $internetSystem->id) }}">
    @csrf
    @method('PUT')
    @if(count($internetSystem->networkCabinets) > 0)
        @foreach($internetSystem->networkCabinets as $cabinet)
            <div class="card mb-5">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $cabinet->model }}</h5>
                    <div class="d-flex align-items-center gap-2">
                    <input 
                        type="number" 
                        step="0.01"
                        class="form-control form-control-sm cabinet-cost-input"
                        style="width: 100px"
                        value="{{ $cabinet->pivot->cost ?? 0 }}"
                        data-cabinet-id="{{ $cabinet->id }}"
                        data-internet-id="{{ $internetSystem->id }}"
                    >
                    <input hidden 
                        name="internet_system_id" 
                        value="{{ $cabinet->pivot->internet_system_id ?? 0 }}"
                    >
                    <button type="button"
                        class="btn btn-sm btn-danger deleteCabinetBtn"
                        data-id="{{ $cabinet->id }}"
                        data-internet-id="{{ $internetSystem->id }}">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                </div>

                <div class="card-body">
                    @php
                        $cabinetPivotSystem = $internetSystem->networkCabinetInternetSystems->firstWhere('id', $cabinet->pivot->id);
                        $totalCost = $cabinetPivotSystem?->components->sum(fn($c) => $c->unit * $c->cost) ?? 0;
                    @endphp
                    <p><strong>Total Components Cost:</strong> 
                        {{ $totalCost }} ₪
                    </p>
 
                    @php
                        $pivot = $cabinet->pivot ?? null;
                        $cabinetPivot = $internetSystem->networkCabinetInternetSystems->firstWhere('id', $pivot?->id);
                        $grouped = $cabinetPivot?->components->groupBy('component_type') ?? collect();
                    @endphp

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs mb-3" id="componentTabs-{{ $cabinet->id }}" role="tablist">
                        @foreach($componentTypes as $class => $info)
                            @php $slug = Str::slug($info['label']); @endphp
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                id="{{ $slug }}-tab-{{ $cabinet->id }}"
                                data-bs-toggle="tab"
                                href="#{{ $slug }}-{{ $cabinet->id }}"
                                role="tab">
                                    {{ $info['label'] }}s
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="componentTabContent-{{ $cabinet->id }}">
                        @foreach($componentTypes as $class => $info)
                            @php
                                $label = $info['label'];
                                $items = $info['data'];
                                $components = $grouped[$class] ?? collect();
                                $slug = Str::slug($label);
                            @endphp

                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="{{ $slug }}-{{ $cabinet->id }}"
                                role="tabpanel"
                                aria-labelledby="{{ $slug }}-tab-{{ $cabinet->id }}">

                                {{-- Existing Components Table --}}
                                @if($components->count() > 0)
                                    <table class="table table-sm table-bordered" id="componentTable-{{ $slug }}-{{ $cabinet->id }}">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Model</th>
                                                <th>Units</th>
                                                <th>Cost</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($components as $component)
                                                @php $uniqueIndex = $component->id; @endphp
                                                <tr data-component-id="{{ $component->id }}">
                                                    <td>{{ $component->component->model ?? '—' }}</td>
                                                    <td>
                                                        <input type="number"
                                                            name="existing_components[{{ $component->id }}][unit]"
                                                            value="{{ $component->unit }}"
                                                            class="component-units form-control form-control-sm"
                                                            step="1" min="0"
                                                            data-component-index="{{ $uniqueIndex }}">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="existing_components[{{ $component->id }}][cost]"
                                                            value="{{ $component->cost }}"
                                                            class="component-costs form-control form-control-sm"
                                                            step="0.01" min="0"
                                                            data-component-index="{{ $uniqueIndex }}">
                                                    </td>
                                                    <td id="total-components-{{ $uniqueIndex }}">
                                                        {{ $component->unit * $component->cost }}
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-outline-danger deleteComponent" 
                                                        data-id="{{ $component->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">No {{ $label }}s added yet.</p>
                                @endif

                        
                                <div id="componentsContainer-{{ $slug }}-{{ $cabinet->id }}">
                                    {{-- Dynamic inputs added by JS will go here --}}
                                </div>

                                <button type="button" 
                                    class="btn btn-sm btn-primary mb-3 addComponentBtn" 
                                    data-slug="{{ $slug }}" 
                                    data-cabinet-id="{{ $cabinet->id }}"
                                    data-label="{{ $label }}"
                                    data-items='@json($items)'
                                    data-class="{{ $class }}"> {{-- add this line --}}
                                    + Add {{ $label }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        <button type="submit" class="btn btn-success mb-5">Save All Changes</button>
    </form>
    @else
        <p class="text-danger">No cabinets are linked to this internet system. Please add some.</p>

        <h6>Add New Cabinets</h6>
        <table class="table table-bordered" id="addRemoveCabinet">
            <thead>
                <tr>
                    <th>Cabinet Model</th>
                    <th>Cost per Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="cabinet_ids[]" class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Choose one...</option>
                            @foreach($cabinets as $cabinet)
                                <option value="{{ $cabinet->id }}">{{ $cabinet->model }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="any"name="cabinet_costs[0][subject]" class="form-control" data-id="0"></td>
                    <td><button type="button" class="btn btn-outline-primary" id="addRemoveCabinetButton">Add Cabinet</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" data-id="{{ $internetSystem->id }}" class="btn btn-sm btn-primary mb-3 addCabinetBtn">
            Save Changes
        </button>
    @endif
<script>

$(function() {

    $(document).on('change', '.cabinet-cost-input', function () {
        
        const cost = $(this).val();
        const cabinetId = $(this).data('cabinet-id');
        const internetSystemId = $(this).data('internet-id');

        $.ajax({
            url: `/internet-system-cabinet/update-cost`,
            type: 'GET',
            data: {
                cost: cost,
                cabinet_id: cabinetId,
                internet_system_id: internetSystemId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Cabinet cost updated successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Something went wrong.', 'error');
            }
        });
    });


    let cabinetIndex = 1;
    const cabinetsData = @json($cabinets);

    $('#addRemoveCabinetButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        cabinetsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="cabinet_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any"name="cabinet_costs[${cabinetIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveCabinet tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        cabinetIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // This part is for adding new network cabinet
    $('.addCabinetBtn').on('click', function () {

        const internetSystemId = $(this).data('id');

        let newCabinets = [];

        $('#addRemoveCabinet tbody tr').each(function () {

            const cabinetId = $(this).find('select[name="cabinet_ids[]"]').val();
            const cost = $(this).find('input[name^="cabinet_costs"]').val();

            if (cabinetId && cost) {

                newCabinets.push({
                    cabinet_id: cabinetId,
                    cost: cost
                });
            }
        });

        if (newCabinets.length === 0) {
            Swal.fire('Warning', 'Please fill in at least one cabinet.', 'warning');
            return;
        }

        $.ajax({
            url: `/internet-system-cabinet/${internetSystemId}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                new_cabinets: newCabinets
            },
            success: function (response) {
                if (response.success === 1) {
                    Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' })
                        .then(() => location.reload()); // Reload to show new cabinets
                }
            },
            error: function (xhr) {
                console.error('Failed to save cabinet', xhr.responseText);
                Swal.fire('Error', 'Something went wrong while saving the cabinet.', 'error');
            }
        });
    });

    // delete internet system cabinet
    $(document).on('click', '.deleteCabinetBtn', function () {

        const cabinetId = $(this).data('id');
        const internetSystemId = $(this).data('internet-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This cabinet and all of its components will be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/internet-system/${internetSystemId}/cabinet/${cabinetId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.msg, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.msg || 'Something went wrong.', 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'Failed to delete cabinet.', 'error');
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    // Add new component inputs dynamically
    $('.addComponentBtn').on('click', function() {

        const slug = $(this).data('slug');
        const cabinetId = $(this).data('cabinet-id');
        const label = $(this).data('label');
        const items = $(this).data('items');
        const componentClass = $(this).data('class'); // added here

        const container = $(`#componentsContainer-${slug}-${cabinetId}`);

        // Count existing input groups for this component type & cabinet:
        // We want to count children with matching componentClass attribute or fallback to length
        const index = container.children().length;

        let options = '';
        items.forEach(item => {
            options += `<option value="${item.id}">${item.model}</option>`;
        });

        const html = `
            <div class="row g-2 align-items-end mb-2 component-input-group" data-index="${index}">
                <div class="col-md-4">
                    <label class="form-label">Select ${label}</label>
                    <select name="components[${cabinetId}][${componentClass}][${index}][component_id]" class="form-select form-select-sm">
                        ${options}
                    </select>
                    <input type="hidden" name="components[${cabinetId}][${componentClass}][${index}][component_type]" value="${componentClass}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Units</label>
                    <input type="number" name="components[${cabinetId}][${componentClass}][${index}][unit]" class="form-control form-control-sm" min="1">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cost</label>
                    <input type="number" name="components[${cabinetId}][${componentClass}][${index}][cost]" class="form-control form-control-sm" step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger removeComponentBtn">Remove</button>
                </div>
            </div>
        `;

        container.append(html);
    });

    // Remove dynamic input group
    $(document).on('click', '.removeComponentBtn', function() {
        $(this).closest('.component-input-group').remove();
    });


    // Auto-calculate total for the network cabinet
    const debounceTimersCabinetComponent = {};
    $(document).on('input', '.component-units, .component-costs', function () {

        const index = $(this).data('component-index'); 

        const unit = parseFloat($(`.component-units[data-component-index="${index}"]`).val()) || 0;
        const cost = parseFloat($(`.component-costs[data-component-index="${index}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);

        $(`#total-components-${index}`).text(total);

        clearTimeout(debounceTimersCabinetComponent[index]);

        debounceTimersCabinetComponent[index] = setTimeout(() => {
            const row = $(this).closest('tr');
            const componentId = row.data('component-id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: `/update-internet-cabinet-component/${componentId}`,
                method: 'POST',
                data: {
                    units: unit,
                    cost: cost
                },
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                },
                error: function (xhr) {
                    console.error('Failed to update component', xhr.responseText);
                }
            });

        }, 500);
    });

    // Delete the cabinet component
    $(document).on('click', '.deleteComponent', function (e) {

        const componentId = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This component will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/components/${componentId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.msg, 'success');
                            row.remove();
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });

});
</script>

@endsection
