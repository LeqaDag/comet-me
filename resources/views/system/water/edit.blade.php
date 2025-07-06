@extends('layouts/layoutMaster')

@section('title', 'Edit Water System')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label {
        margin-top: 20px;
    }
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{ $waterSystem->name }}
    <span class="text-muted fw-light">Information </span>
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{ route('water-system.update', $waterSystem->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-4 mb-1">
                        <label>Water System Type</label>
                        <select name="water_system_type_id" class="selectpicker form-control"
                                data-live-search="true">
                            @if($waterSystem->water_system_type_id)
                                <option disabled selected>{{ $waterSystem->WaterSystemType->type }}</option>
                            @endif
                            @foreach($waterSystemTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-1">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required
                               value="{{ old('name', $waterSystem->name) }}">
                    </div>

                    <div class="col-md-4 mb-1">
                        <label>Community</label>
                        <select name="community_id" class="selectpicker form-control" data-live-search="true" required>
                            @if($waterSystem->community_id)
                                <option disabled selected>{{ $waterSystem->Community->english_name }}</option>
                            @endif
                            @foreach($communities as $community)
                                <option value="{{ $community->id }}">{{ $community->english_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-1">
                        <label>Start Year</label>
                        <input type="number" name="year" class="form-control"
                               value="{{ old('year', $waterSystem->year) }}">
                    </div>

                    <div class="col-md-4 mb-1">
                        <label>Cycle Year</label>
                        <select name="water_system_cycle_id" class="selectpicker form-control"
                                data-live-search="true" required>
                            @if($waterSystem->water_system_cycle_id)
                                <option disabled selected>{{ $waterSystem->WaterSystemCycle->name }}</option>
                            @endif
                            @foreach($waterSystemCycles as $cycle)
                                <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-1">
                        <label>Upgrade Year 1</label>
                        <input type="number" name="upgrade_year1" class="form-control"
                               value="{{ old('upgrade_year1', $waterSystem->upgrade_year1) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-1">
                        <label>Upgrade Year 2</label>
                        <input type="number" name="upgrade_year2" class="form-control"
                               value="{{ old('upgrade_year2', $waterSystem->upgrade_year2) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-1">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="4" style="resize: none;">{{ old('description', $waterSystem->description) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-1">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="4" style="resize: none;">{{ old('notes', $waterSystem->notes) }}</textarea>
                    </div>
                </div>

                <hr class="mt-4">
                <h5>Tanks</h5>

                @if(count($waterTanks) > 0)
                    <table class="table table-striped my-2" id="tankTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterTanks as $index => $tank)
                                <tr data-tank-id="{{ $tank->id }}">
                                    <td class="text-center">{{ $tank->model }}</td>
                                    <td>
                                        <input type="number"step="any" name="tank_units[{{ $tank->id }}]" class="form-control tank-units" 
                                        data-index="{{ $index }}" value="{{ $tank->tank_units }}">
                                    </td>
                                    <td>
                                        <input type="number"step="any" name="tank_costs[{{ $tank->id }}]" class="form-control tank-costs" 
                                        data-index="{{ $index }}" value="{{ $tank->tank_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-{{ $index }}">{{ $tank->tank_units * $tank->tank_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteTank" data-id="{{ $tank->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Tanks --}}
                <h6>Add New Tanks</h6>
                <table class="table table-bordered" id="addRemoveTank">
                    <thead>
                        <tr>
                            <th>Tank Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="tank_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($tanks as $tank)
                                        <option value="{{ $tank->id }}">{{ $tank->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number"step="any" name="tank_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="tank_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveTankButton">Add Tank</button></td>
                        </tr>
                    </tbody>
                </table>

                <hr class="mt-4">
                <h5>Pipes</h5>

                @if(count($waterPipes) > 0)
                    <table class="table table-striped my-2" id="pipeTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterPipes as $index => $pipe)
                                <tr data-pipe-id="{{ $pipe->id }}">
                                    <td class="text-center">{{ $pipe->model }}</td>
                                    <td>
                                        <input type="number" step="any"name="pipe_units[{{ $pipe->id }}]" class="form-control pipe-units" 
                                        data-pipe-index="{{ $index }}" value="{{ $pipe->pipe_units }}">
                                    </td>
                                    <td>
                                        <input type="number"step="any" name="pipe_costs[{{ $pipe->id }}]" class="form-control pipe-costs" 
                                        data-pipe-index="{{ $index }}" value="{{ $pipe->pipe_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-pipe-{{ $index }}">{{ $pipe->pipe_units * $pipe->pipe_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deletePipe" data-id="{{ $pipe->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Pipes --}}
                <h6>Add New Pipes</h6>
                <table class="table table-bordered" id="addRemovePipe">
                    <thead>
                        <tr>
                            <th>Pipe Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="pipe_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($pipes as $pipe)
                                        <option value="{{ $pipe->id }}">{{ $pipe->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any"name="pipe_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="pipe_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemovePipeButton">Add Pipe</button></td>
                        </tr>
                    </tbody>
                </table>

                <hr class="mt-4">
                <h5>Pumps</h5>

                @if(count($waterPumps) > 0)
                    <table class="table table-striped my-2" id="pumpTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterPumps as $index => $pump)
                                <tr data-pump-id="{{ $pump->id }}">
                                    <td class="text-center">{{ $pump->model }}</td>
                                    <td>
                                        <input type="number"step="any" name="pump_units[{{ $pump->id }}]" class="form-control pump-units" 
                                        data-pump-index="{{ $index }}" value="{{ $pump->pump_units }}">
                                    </td>
                                    <td>
                                        <input type="number"step="any" name="pump_costs[{{ $pump->id }}]" class="form-control pump-costs" 
                                        data-pump-index="{{ $index }}" value="{{ $pump->pump_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-pump-{{ $index }}">{{ $pump->pump_units * $pump->pump_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deletePump" data-id="{{ $pump->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Pumps --}}
                <h6>Add New Pumps</h6>
                <table class="table table-bordered" id="addRemovePump">
                    <thead>
                        <tr>
                            <th>Pump Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="pump_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($pumps as $pump)
                                        <option value="{{ $pump->id }}">{{ $pump->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any"name="pump_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="pump_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemovePumpButton">Add Pump</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Connectors</h5>

                @if(count($waterConnectors) > 0)
                    <table class="table table-striped my-2" id="connectorTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterConnectors as $index => $connector)
                                <tr data-connector-id="{{ $connector->id }}">
                                    <td class="text-center">{{ $connector->model }}</td>
                                    <td>
                                        <input type="number" step="any"name="connector_units[{{ $connector->id }}]" class="form-control connector-units" 
                                        data-connector-index="{{ $index }}" value="{{ $connector->connector_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any"name="connector_costs[{{ $connector->id }}]" class="form-control connector-costs" 
                                        data-connector-index="{{ $index }}" value="{{ $connector->connector_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-connector-{{ $index }}">{{ $connector->connector_units * $connector->connector_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteConnector" data-id="{{ $connector->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Connectors --}}
                <h6>Add New Connectors</h6>
                <table class="table table-bordered" id="addRemoveConnector">
                    <thead>
                        <tr>
                            <th>Connector Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="connector_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($connectors as $connector)
                                        <option value="{{ $connector->id }}">{{ $connector->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number"step="any" name="connector_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="connector_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveConnectorButton">Add Connector</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Filters</h5>

                @if(count($waterFilters) > 0)
                    <table class="table table-striped my-2" id="filterTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterFilters as $index => $filter)
                                <tr data-filter-id="{{ $filter->id }}">
                                    <td class="text-center">{{ $filter->model }}</td>
                                    <td>
                                        <input type="number" step="any"name="filter_units[{{ $filter->id }}]" class="form-control filter-units" 
                                        data-filter-index="{{ $index }}" value="{{ $filter->filter_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any"name="filter_costs[{{ $filter->id }}]" class="form-control filter-costs" 
                                        data-filter-index="{{ $index }}" value="{{ $filter->filter_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-filter-{{ $index }}">{{ $filter->filter_units * $filter->filter_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteFilter" data-id="{{ $filter->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Filters --}}
                <h6>Add New Filters</h6>
                <table class="table table-bordered" id="addRemoveFilter">
                    <thead>
                        <tr>
                            <th>Filter Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="filter_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($filters as $filter)
                                        <option value="{{ $filter->id }}">{{ $filter->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number"step="any" name="filter_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number"step="any" name="filter_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveFilterButton">Add Filter</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Taps</h5>

                @if(count($waterTaps) > 0)
                    <table class="table table-striped my-2" id="tapTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterTaps as $index => $tap)
                                <tr data-tap-id="{{ $tap->id }}">
                                    <td class="text-center">{{ $tap->model }}</td>
                                    <td>
                                        <input type="number" step="any"name="tap_units[{{ $tap->id }}]" class="form-control tap-units" 
                                        data-tap-index="{{ $index }}" value="{{ $tap->tap_units }}">
                                    </td>
                                    <td>
                                        <input type="number"step="any" name="tap_costs[{{ $tap->id }}]" class="form-control tap-costs" 
                                        data-tap-index="{{ $index }}" value="{{ $tap->tap_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-tap-{{ $index }}">{{ $tap->tap_units * $tap->tap_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteTap" data-id="{{ $tap->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Taps --}}
                <h6>Add New Taps</h6>
                <table class="table table-bordered" id="addRemoveTap">
                    <thead>
                        <tr>
                            <th>Tap Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="tap_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($taps as $tap)
                                        <option value="{{ $tap->id }}">{{ $tap->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any"name="tap_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="tap_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveTapButton">Add Tap</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Valves</h5>

                @if(count($waterValves) > 0)
                    <table class="table table-striped my-2" id="valveTable">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Units</th>
                                <th>Cost</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waterValves as $index => $valve)
                                <tr data-valve-id="{{ $valve->id }}">
                                    <td class="text-center">{{ $valve->model }}</td>
                                    <td>
                                        <input type="number" step="any"name="valve_units[{{ $valve->id }}]" class="form-control valve-units" 
                                        data-valve-index="{{ $index }}" value="{{ $valve->valve_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any"name="valve_costs[{{ $valve->id }}]" class="form-control valve-costs" 
                                        data-valve-index="{{ $index }}" value="{{ $valve->valve_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-valve-{{ $index }}">{{ $valve->valve_units * $valve->valve_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteValve" data-id="{{ $valve->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Valves --}}
                <h6>Add New Valves</h6>
                <table class="table table-bordered" id="addRemoveValve">
                    <thead>
                        <tr>
                            <th>Valve Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="valve_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($valves as $valve)
                                        <option value="{{ $valve->id }}">{{ $valve->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any"name="valve_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="valve_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveValveButton">Add Valve</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
$(function () {

    let tankIndex = 1;
    const tanksData = @json($tanks);

    $('#addRemoveTankButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        tanksData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="tank_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number"step="any" name="tank_units[${tankIndex}][subject]" class="form-control"></td>
                <td><input type="number"step="any" name="tank_costs[${tankIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveTank tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        tankIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimers = {};
    $(document).on('input', '.tank-units, .tank-costs', function () {
        const index = $(this).data('index');
        const unit = parseFloat($(`.tank-units[data-index="${index}"]`).val()) || 0;
        const cost = parseFloat($(`.tank-costs[data-index="${index}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-${index}`).text(total);

        clearTimeout(debounceTimers[index]);
        debounceTimers[index] = setTimeout(() => {
            const row = $(this).closest('tr');
            const tankId = row.data('tank-id');

            $.ajax({
                url: `/update-water-tank/${tankId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water tank
    $('#tankTable').on('click', '.deleteTank',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Tank?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteTank') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    let pipeIndex = 1;
    const pipesData = @json($pipes);

    $('#addRemovePipeButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        pipesData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="pipe_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number"step="any" name="pipe_units[${pipeIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any"name="pipe_costs[${pipeIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemovePipe tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        pipeIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total for pipe
    const debouncePipes = {};
    $(document).on('input', '.pipe-units, .pipe-costs', function () {

        const indexPipe = $(this).data('pipe-index');
        const unit = parseFloat($(`.pipe-units[data-pipe-index="${indexPipe}"]`).val()) || 0;
        const cost = parseFloat($(`.pipe-costs[data-pipe-index="${indexPipe}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-pipe-${indexPipe}`).text(total);

        clearTimeout(debouncePipes[indexPipe]);
        debouncePipes[indexPipe] = setTimeout(() => {

            const row = $(this).closest('tr');
            const pipeId = row.data('pipe-id');

            $.ajax({
                url: `/update-water-pipe/${pipeId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water pipe
    $('#pipeTable').on('click', '.deletePipe',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Pipe?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletePipe') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });


    let pumpIndex = 1;
    const pumpsData = @json($pumps);

    $('#addRemovePumpButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        pumpsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="pump_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any"name="pump_units[${pumpIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any"name="pump_costs[${pumpIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemovePump tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        pumpIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total for pump
    const debouncePumps = {};
    $(document).on('input', '.pump-units, .pump-costs', function () {

        const indexPump = $(this).data('pump-index');
        const unit = parseFloat($(`.pump-units[data-pump-index="${indexPump}"]`).val()) || 0;
        const cost = parseFloat($(`.pump-costs[data-pump-index="${indexPump}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-pump-${indexPump}`).text(total);

        clearTimeout(debouncePumps[indexPump]);
        debouncePumps[indexPump] = setTimeout(() => {

            const row = $(this).closest('tr');
            const pumpId = row.data('pump-id');

            $.ajax({
                url: `/update-water-pump/${pumpId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water pump
    $('#pumpTable').on('click', '.deletePump',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Pump?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletePump') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });



    let connectorIndex = 1;
    const connectorsData = @json($connectors);

    $('#addRemoveConnectorButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        connectorsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="connector_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number"step="any" name="connector_units[${connectorIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any"name="connector_costs[${connectorIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveConnector tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        connectorIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total for connector
    const debounceConnectors = {};
    $(document).on('input', '.connector-units, .connector-costs', function () {

        const indexConnector = $(this).data('connector-index');
        const unit = parseFloat($(`.connector-units[data-connector-index="${indexConnector}"]`).val()) || 0;
        const cost = parseFloat($(`.connector-costs[data-connector-index="${indexConnector}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-connector-${indexConnector}`).text(total);

        clearTimeout(debounceConnectors[indexConnector]);
        debounceConnectors[indexConnector] = setTimeout(() => {

            const row = $(this).closest('tr');
            const connectorId = row.data('connector-id');

            $.ajax({
                url: `/update-water-connector/${connectorId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water connector
    $('#connectorTable').on('click', '.deleteConnector',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Connector?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteConnector') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });



    let filterIndex = 1;
    const filtersData = @json($filters);

    $('#addRemoveFilterButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        filtersData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="filter_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any"name="filter_units[${filterIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any"name="filter_costs[${filterIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveFilter tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        filterIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total for filter
    const debounceFilters = {};
    $(document).on('input', '.filter-units, .filter-costs', function () {

        const indexFilter = $(this).data('filter-index');
        const unit = parseFloat($(`.filter-units[data-filter-index="${indexFilter}"]`).val()) || 0;
        const cost = parseFloat($(`.filter-costs[data-filter-index="${indexFilter}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-filter-${indexFilter}`).text(total);

        clearTimeout(debounceFilters[indexFilter]);
        debounceFilters[indexFilter] = setTimeout(() => {

            const row = $(this).closest('tr');
            const filterId = row.data('filter-id');

            $.ajax({
                url: `/update-water-filter/${filterId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water filter
    $('#filterTable').on('click', '.deleteFilter', function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Filter?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteFilter') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });



    let tapIndex = 1;
    const tapsData = @json($taps);

    $('#addRemoveTapButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        tapsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="tap_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any"name="tap_units[${tapIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any"name="tap_costs[${tapIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveTap tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        tapIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total for tap
    const debounceTaps = {};
    $(document).on('input', '.tap-units, .tap-costs', function () {

        const indexTap = $(this).data('tap-index');
        const unit = parseFloat($(`.tap-units[data-tap-index="${indexTap}"]`).val()) || 0;
        const cost = parseFloat($(`.tap-costs[data-tap-index="${indexTap}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-tap-${indexTap}`).text(total);

        clearTimeout(debounceTaps[indexTap]);
        debounceTaps[indexTap] = setTimeout(() => {

            const row = $(this).closest('tr');
            const tapId = row.data('tap-id');

            $.ajax({
                url: `/update-water-tap/${tapId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water tap
    $('#tapTable').on('click', '.deleteTap', function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Tap?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteTap') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });


    let valveIndex = 1;
    const valvesData = @json($valves);

    $('#addRemoveValveButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        valvesData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="valve_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any"name="valve_units[${valveIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any"name="valve_costs[${valveIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveValve tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        valveIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total for valve
    const debounceValves = {};
    $(document).on('input', '.valve-units, .valve-costs', function () {

        const indexValve = $(this).data('valve-index');
        const unit = parseFloat($(`.valve-units[data-valve-index="${indexValve}"]`).val()) || 0;
        const cost = parseFloat($(`.valve-costs[data-valve-index="${indexValve}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-valve-${indexValve}`).text(total);

        clearTimeout(debounceValves[indexValve]);
        debounceValves[indexValve] = setTimeout(() => {

            const row = $(this).closest('tr');
            const valveId = row.data('valve-id');

            $.ajax({
                url: `/update-water-valve/${valveId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete water valve
    $('#valveTable').on('click', '.deleteValve', function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Valve?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteValve') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

});
</script>
@endsection
