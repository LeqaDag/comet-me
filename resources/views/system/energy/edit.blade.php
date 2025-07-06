@extends('layouts/layoutMaster')

@section('title', 'edit energy system')

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
    <span class="text-muted fw-light">Edit </span> {{$energySystem->name}}
    <span class="text-muted fw-light">Information </span> 
</h4> 

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-system.update', $energySystem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    @if($energySystem->Community)
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <input type="text" class="form-control" disabled
                            value="{{$energySystem->Community->english_name}}">
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" class="selectpicker form-control"
                                data-live-search="true">
                                <option value="{{$energySystem->EnergySystemType->id}}" disabled selected>
                                    {{$energySystem->EnergySystemType->name}}
                                </option>
                                @foreach($energyTypes as $energyType)
                                    <option value="{{$energyType->id}}">
                                        {{$energyType->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" 
                            class="form-control" value="{{$energySystem->name}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" 
                            class="form-control" value="{{$energySystem->installation_year}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($energySystem->energy_system_cycle_id)
                                <option value="{{$energySystem->EnergySystemCycle->id}}" disabled selected>
                                    {{$energySystem->EnergySystemCycle->name}}
                                </option>
                                @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                @endforeach
                                @else 

                                <option disabled selected>Choose one...</option>
                                @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upgrade Year 1</label>
                            <input type="number" name="upgrade_year1" 
                            class="form-control" value="{{$energySystem->upgrade_year1}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upgrade Year 2</label>
                            <input type="number" name="upgrade_year2" 
                            class="form-control" value="{{$energySystem->upgrade_year2}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Rated Power</label>
                            <input type="text" name="total_rated_power" 
                            class="form-control"value="{{$energySystem->total_rated_power}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Generated Power</label>
                            <input type="text" name="generated_power" 
                            class="form-control"value="{{$energySystem->generated_power}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Turbine Power</label>
                            <input type="text" name="turbine_power" 
                            class="form-control"value="{{$energySystem->turbine_power}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                                {{$energySystem->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <hr class="mt-4">
                <h5>Batteries</h5>

                @if(count($battarySystems) > 0)
                    <table class="table table-striped my-2" id="batteryTable">
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
                            @foreach($battarySystems as $index => $battery)
                                <tr data-battery-id="{{ $battery->id }}">
                                    <td class="text-center">{{ $battery->battery_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="battery_units[{{ $battery->id }}]" class="form-control battery-units" 
                                        data-battery-index="{{ $index }}" value="{{ $battery->battery_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="battery_costs[{{ $battery->id }}]" class="form-control battery-costs" 
                                        data-battery-index="{{ $index }}" value="{{ $battery->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-battery-{{ $index }}">{{ $battery->battery_units * $battery->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteBattery" data-id="{{ $battery->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Battery --}}
                <h6>Add New Battery</h6>
                <table class="table table-bordered" id="addRemoveBattery">
                    <thead>
                        <tr>
                            <th>Battery Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="battery_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($batteries as $battery)
                                        <option value="{{ $battery->id }}">{{ $battery->battery_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="battery_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="battery_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveBatteryButton">Add Battery</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Battery Mounts</h5>

                @if(count($battaryMountSystems) > 0)
                    <table class="table table-striped my-2" id="batteryMountTable">
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
                            @foreach($battaryMountSystems as $index => $battery)
                                <tr data-battery-mount-id="{{ $battery->id }}">
                                    <td class="text-center">{{ $battery->model }}</td>
                                    <td>
                                        <input type="number" step="any" name="battery_mount_units[{{ $battery->id }}]" class="form-control battery-mount-units" 
                                        data-battery-mount-index="{{ $index }}" value="{{ $battery->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="battery_mount_costs[{{ $battery->id }}]" class="form-control battery-mount-costs" 
                                        data-battery-mount-index="{{ $index }}" value="{{ $battery->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-battery-mount-{{ $index }}">{{ $battery->unit * $battery->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteBatteryMount" data-id="{{ $battery->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Battery Mounts--}}
                <h6>Add New Battery Mounts</h6>
                <table class="table table-bordered" id="addRemoveBatteryMount">
                    <thead>
                        <tr>
                            <th>Battery MountModel</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="battery_mount_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($batteryMounts as $batteryMount)
                                        <option value="{{ $batteryMount->id }}">{{ $batteryMount->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number"step="any" name="battery_mount_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number"step="any" name="battery_mount_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveBatteryMountButton">Add Battery Mount</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Solar Panels</h5>

                @if(count($pvSystems) > 0)
                    <table class="table table-striped my-2" id="pvTable">
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
                            @foreach($pvSystems as $index => $pv)
                                <tr data-pv-id="{{ $pv->id }}">
                                    <td class="text-center">{{ $pv->pv_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="pv_units[{{ $pv->id }}]" class="form-control pv-units" 
                                        data-pv-index="{{ $index }}" value="{{ $pv->pv_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="pv_costs[{{ $pv->id }}]" class="form-control pv-costs" 
                                        data-pv-index="{{ $index }}" value="{{ $pv->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-pv-{{ $index }}">{{ $pv->pv_units * $pv->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deletePv" data-id="{{ $pv->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Solar Panels --}}
                <h6>Add New Solar Panels</h6>
                <table class="table table-bordered" id="addRemovePv">
                    <thead>
                        <tr>
                            <th>PV Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="pv_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($pvs as $pv)
                                        <option value="{{ $pv->id }}">{{ $pv->pv_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any"name="pv_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="pv_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemovePvButton">Add PV</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Solar Panel Mounts</h5>

                @if(count($pvMountSystems) > 0)
                    <table class="table table-striped my-2" id="pvMountTable">
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
                            @foreach($pvMountSystems as $index => $pv)
                                <tr data-pv-mount-id="{{ $pv->id }}">
                                    <td class="text-center">{{ $pv->model }}</td>
                                    <td>
                                        <input type="number"step="any" name="pv_mount_units[{{ $pv->id }}]" class="form-control pv-mount-units" 
                                        data-pv-mount-index="{{ $index }}" value="{{ $pv->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any"name="pv_mount_costs[{{ $pv->id }}]" class="form-control pv-mount-costs" 
                                        data-pv-mount-index="{{ $index }}" value="{{ $pv->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-pv-mount-{{ $index }}">{{ $pv->unit * $pv->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deletePvMount" data-id="{{ $pv->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More PV Mounts--}}
                <h6>Add New PV Mounts</h6>
                <table class="table table-bordered" id="addRemovePvMount">
                    <thead>
                        <tr>
                            <th>PV Mount Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="pv_mount_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($pvMounts as $pvMount)
                                        <option value="{{ $pvMount->id }}">{{ $pvMount->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any"name="pv_mount_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"name="pv_mount_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemovePvMountButton">Add PV Mount</button></td>
                        </tr>
                    </tbody>
                </table>

                <hr class="mt-4">
                <h5>Controlles</h5>

                @if(count($controllerSystems) > 0)
                    <table class="table table-striped my-2" id="controllerTable">
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
                            @foreach($controllerSystems as $index => $controller)
                                <tr data-controller-id="{{ $controller->id }}">
                                    <td class="text-center">{{ $controller->charge_controller_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="controller_units[{{ $controller->id }}]" class="form-control controller-units" 
                                        data-controller-index="{{ $index }}" value="{{ $controller->controller_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="controller_costs[{{ $controller->id }}]" class="form-control controller-costs" 
                                        data-controller-index="{{ $index }}" value="{{ $controller->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-controller-{{ $index }}">{{ $controller->controller_units * $controller->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteController" data-id="{{ $controller->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Controllers --}}
                <h6>Add New Controllers</h6>
                <table class="table table-bordered" id="addRemoveController">
                    <thead>
                        <tr>
                            <th>Controller Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="controller_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($controllers as $controller)
                                        <option value="{{ $controller->id }}">{{ $controller->charge_controller_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="controller_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="controller_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveControllerButton">Add Controller</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Inverter</h5>

                @if(count($inverterSystems) > 0)
                    <table class="table table-striped my-2" id="inverterTable">
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
                            @foreach($inverterSystems as $index => $inverter)
                                <tr data-inverter-id="{{ $inverter->id }}">
                                    <td class="text-center">{{ $inverter->inverter_model }}</td>
                                    <td>
                                        <input type="number" step="any"  name="inverter_units[{{ $inverter->id }}]" class="form-control inverter-units" 
                                        data-inverter-index="{{ $index }}" value="{{ $inverter->inverter_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any"  name="inverter_costs[{{ $inverter->id }}]" class="form-control inverter-costs" 
                                        data-inverter-index="{{ $index }}" value="{{ $inverter->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-inverter-{{ $index }}">{{ $inverter->inverter_units * $inverter->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteInverter" data-id="{{ $inverter->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Inverters --}}
                <h6>Add New Inverters</h6>
                <table class="table table-bordered" id="addRemoveInverter">
                    <thead>
                        <tr>
                            <th>Inverter Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="inverter_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($inverters as $inverter)
                                        <option value="{{ $inverter->id }}">{{ $inverter->inverter_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="inverter_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any"  name="inverter_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveInverterButton">Add Inverter</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Relay Drivers</h5>

                @if(count($relayDriverSystems) > 0)
                    <table class="table table-striped my-2" id="relayDriverTable">
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
                            @foreach($relayDriverSystems as $index => $relayDriver)
                                <tr data-relay-driver-id="{{ $relayDriver->id }}">
                                    <td class="text-center">{{ $relayDriver->model }}</td>
                                    <td>
                                        <input type="number" step="any"  name="relay-driver_units[{{ $relayDriver->id }}]" class="form-control relay-driver-units" 
                                        data-relay-driver-index="{{ $index }}" value="{{ $relayDriver->relay_driver_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any"  name="relay-driver_costs[{{ $relayDriver->id }}]" class="form-control relay-driver-costs" 
                                        data-relay-driver-index="{{ $index }}" value="{{ $relayDriver->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-relay-driver-{{ $index }}">{{ $relayDriver->relay_driver_units * $relayDriver->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteRelayDriver" data-id="{{ $relayDriver->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Relay Drivers --}}
                <h6>Add New Relay Drivers</h6>
                <table class="table table-bordered" id="addRemoveRelayDriver">
                    <thead>
                        <tr>
                            <th>Relay Driver Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="relay_driver_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($relayDrivers as $relayDriver)
                                        <option value="{{ $relayDriver->id }}">{{ $relayDriver->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="relay-driver_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="relay-driver_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveRelayDriverButton">Add Relay Driver</button></td>
                        </tr>
                    </tbody>
                </table>



                <hr class="mt-4">
                <h5>Load Relay</h5>

                @if(count($loadRelaySystems) > 0)
                    <table class="table table-striped my-2" id="loadRelayTable">
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
                        @foreach($loadRelaySystems as $index => $loadRelay)
                            <tr data-load-relay-id="{{ $loadRelay->id }}">
                                <td class="text-center">{{ $loadRelay->load_relay_model }}</td>
                                <td>
                                    <input type="number" step="any" name="load-relay-units[{{ $index }}][subject]" 
                                        class="form-control load-relay-units" 
                                        data-load-relay-index="{{ $index }}" 
                                        value="{{ $loadRelay->load_relay_units }}">
                                </td>
                                <td>
                                    <input type="number" step="any" name="load-relay-costs[{{ $index }}][subject]" 
                                        class="form-control load-relay-costs" 
                                        data-load-relay-index="{{ $index }}" 
                                        value="{{ $loadRelay->cost }}">
                                </td>
                                <td>
                                    <span id="total-load-relay-{{ $index }}">{{ number_format($loadRelay->load_relay_units * $loadRelay->cost, 2) }}</span>
                                </td>
                                <td>
                                    <a class="btn deleteLoadRelay" data-id="{{ $loadRelay->id }}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                @endif

                {{-- Add More Load Relays --}}
                <h6>Add New Load Relays</h6>
                <table class="table table-bordered" id="addRemoveLoadRelay">
                    <thead>
                        <tr>
                            <th>Load Relay Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="load_relay_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($loadRelaies as $loadRelay)
                                        <option value="{{ $loadRelay->id }}">{{ $loadRelay->load_relay_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="relay-driver_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="relay-driver_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveLoadRelayButton">Add Load Relay</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Battery Proccessor</h5>

                @if(count($bspSystems) > 0)
                    <table class="table table-striped my-2" id="bspTable">
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
                            @foreach($bspSystems as $index => $bsp)
                                <tr data-bsp-id="{{ $bsp->id }}">
                                    <td class="text-center">{{ $bsp->model }}</td>
                                    <td>
                                        <input type="number" step="any" name="bsp_units[{{ $bsp->id }}]" class="form-control bsp-units" 
                                        data-bsp-index="{{ $index }}" value="{{ $bsp->bsp_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="bsp_costs[{{ $bsp->id }}]" class="form-control bsp-costs" 
                                        data-bsp-index="{{ $index }}" value="{{ $bsp->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-bsp-{{ $index }}">{{ $bsp->bsp_units * $bsp->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteBsp" data-id="{{ $bsp->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Battery Proccessor --}}
                <h6>Add New Battery Proccessor</h6>
                <table class="table table-bordered" id="addRemoveBsp">
                    <thead>
                        <tr>
                            <th>BSP Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="bsp_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($bsps as $bsp)
                                        <option value="{{ $bsp->id }}">{{ $bsp->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="bsp_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="bsp_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveBspButton">Add Battery Proccessor</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>BTS</h5>

                @if(count($btsSystems) > 0)
                    <table class="table table-striped my-2" id="btsTable">
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
                            @foreach($btsSystems as $index => $bts)
                                <tr data-bts-id="{{ $bts->id }}">
                                    <td class="text-center">{{ $bts->BTS_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="bts_units[{{ $bts->id }}]" class="form-control bts-units" 
                                        data-bts-index="{{ $index }}" value="{{ $bts->bts_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="bts_costs[{{ $bts->id }}]" class="form-control bts-costs" 
                                        data-bts-index="{{ $index }}" value="{{ $bts->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-bts-{{ $index }}">{{ $bts->bts_units * $bts->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteBts" data-id="{{ $bts->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More BTS --}}
                <h6>Add New BTS</h6>
                <table class="table table-bordered" id="addRemoveBts">
                    <thead>
                        <tr>
                            <th>BTS Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="bts_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($btss as $bts)
                                        <option value="{{ $bts->id }}">{{ $bts->BTS_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="bts_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="bts_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveBtsButton">Add BTS</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Control Center</h5>

                @if(count($rccSystems) > 0)
                    <table class="table table-striped my-2" id="rccTable">
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
                            @foreach($rccSystems as $index => $rcc)
                                <tr data-rcc-id="{{ $rcc->id }}">
                                    <td class="text-center">{{ $rcc->model }}</td>
                                    <td>
                                        <input type="number" step="any" name="rcc_units[{{ $rcc->id }}]" class="form-control rcc-units" 
                                        data-rcc-index="{{ $index }}" value="{{ $rcc->rcc_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="rcc_costs[{{ $rcc->id }}]" class="form-control rcc-costs" 
                                        data-rcc-index="{{ $index }}" value="{{ $rcc->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-rcc-{{ $index }}">{{ $rcc->rcc_units * $rcc->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteRcc" data-id="{{ $rcc->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Control Center --}}
                <h6>Add New Control Center</h6>
                <table class="table table-bordered" id="addRemoveRcc">
                    <thead>
                        <tr>
                            <th>RCC Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="rcc_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($rccs as $rcc)
                                        <option value="{{ $rcc->id }}">{{ $rcc->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="rcc_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="rcc_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveRccButton">Add Control Center</button></td>
                        </tr>
                    </tbody>
                </table>



                <hr class="mt-4">
                <h5>Logger</h5>

                @if(count($loggerSystems) > 0)
                    <table class="table table-striped my-2" id="loggerTable">
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
                            @foreach($loggerSystems as $index => $logger)
                                <tr data-logger-id="{{ $logger->id }}">
                                    <td class="text-center">{{ $logger->monitoring_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="logger_units[{{ $logger->id }}]" class="form-control logger-units" 
                                        data-logger-index="{{ $index }}" value="{{ $logger->monitoring_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="logger_costs[{{ $logger->id }}]" class="form-control logger-costs" 
                                        data-logger-index="{{ $index }}" value="{{ $logger->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-logger-{{ $index }}">{{ $logger->monitoring_units * $logger->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteLogger" data-id="{{ $logger->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Logger --}}
                <h6>Add New Logger</h6>
                <table class="table table-bordered" id="addRemoveLogger">
                    <thead>
                        <tr>
                            <th>Logger Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="logger_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($loggers as $logger)
                                        <option value="{{ $logger->id }}">{{ $logger->monitoring_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="logger_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="logger_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveLoggerButton">Add Logger</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Generator</h5>

                @if(count($generatorSystems) > 0)
                    <table class="table table-striped my-2" id="generatorTable">
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
                            @foreach($generatorSystems as $index => $generator)
                                <tr data-generator-id="{{ $generator->id }}">
                                    <td class="text-center">{{ $generator->generator_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="generator_units[{{ $generator->id }}]" class="form-control generator-units" 
                                        data-generator-index="{{ $index }}" value="{{ $generator->generator_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="generator_costs[{{ $generator->id }}]" class="form-control generator-costs" 
                                        data-generator-index="{{ $index }}" value="{{ $generator->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-generator-{{ $index }}">{{ $generator->generator_units * $generator->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteGenerator" data-id="{{ $generator->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Generator --}}
                <h6>Add New Generator</h6>
                <table class="table table-bordered" id="addRemoveGenerator">
                    <thead>
                        <tr>
                            <th>Generator Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="generator_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($generators as $generator)
                                        <option value="{{ $generator->id }}">{{ $generator->generator_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="generator_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="generator_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveGeneratorButton">Add Generator</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Wind Turbine</h5>

                @if(count($turbineSystems) > 0)
                    <table class="table table-striped my-2" id="turbineTable">
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
                            @foreach($turbineSystems as $index => $turbine)
                                <tr data-turbine-id="{{ $turbine->id }}">
                                    <td class="text-center">{{ $turbine->wind_turbine_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="turbine_units[{{ $turbine->id }}]" class="form-control turbine-units" 
                                        data-turbine-index="{{ $index }}" value="{{ $turbine->turbine_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="turbine_costs[{{ $turbine->id }}]" class="form-control turbine-costs" 
                                        data-turbine-index="{{ $index }}" value="{{ $turbine->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-turbine-{{ $index }}">{{ $turbine->turbine_units * $turbine->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteTurbine" data-id="{{ $turbine->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Wind Turbine --}}
                <h6>Add New Wind Turbine</h6>
                <table class="table table-bordered" id="addRemoveTurbine">
                    <thead>
                        <tr>
                            <th>Wind Turbine Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="turbine_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($turbines as $turbine)
                                        <option value="{{ $turbine->id }}">{{ $turbine->wind_turbine_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="turbine_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="turbine_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveTurbineButton">Add Wind Turbine</button></td>
                        </tr>
                    </tbody>
                </table>

                <hr class="mt-4">
                <h5>Solar Panel MCB</h5>

                @if(count($pvMcbSystems) > 0)
                    <table class="table table-striped my-2" id="mcbPvTable">
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
                            @foreach($pvMcbSystems as $index => $mcbPv)
                                <tr data-mcb-pv-id="{{ $mcbPv->id }}">
                                    <td class="text-center">{{ $mcbPv->model }}</td>
                                    <td>
                                        <input type="number" step="any" name="mcb_pv_units[{{ $mcbPv->id }}]" class="form-control mcb-pv-units" 
                                        data-mcb-pv-index="{{ $index }}" value="{{ $mcbPv->mcb_pv_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="mcb_pv_costs[{{ $mcbPv->id }}]" class="form-control mcb-pv-costs" 
                                        data-mcb-pv-index="{{ $index }}" value="{{ $mcbPv->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-mcb-pv-{{ $index }}">{{ $mcbPv->mcb_pv_units * $mcbPv->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteMcbPv" data-id="{{ $mcbPv->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Solar Panel MCB --}}
                <h6>Add New Solar Panel MCB</h6>
                <table class="table table-bordered" id="addRemoveMcbPv">
                    <thead>
                        <tr>
                            <th>Solar Panel MCB Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="mcb_pv_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($mcbPvs as $mcbPv)
                                        <option value="{{ $mcbPv->id }}">{{ $mcbPv->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="mcb_pv_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="mcb_pv_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveMcbPvButton">Add Solar Panel MCB</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Charge Controller MCB</h5>

                @if(count($controllerMcbSystems) > 0)
                    <table class="table table-striped my-2" id="mcbControllerTable">
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
                            @foreach($controllerMcbSystems as $index => $mcbController)
                                <tr data-mcb-controller-id="{{ $mcbController->id }}">
                                    <td class="text-center">{{ $mcbController->model }}</td>
                                    <td>
                                        <input type="number" step="any" name="mcb_controller_units[{{ $mcbController->id }}]" class="form-control mcb-controller-units" 
                                        data-mcb-controller-index="{{ $index }}" value="{{ $mcbController->mcb_controller_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="mcb_controller_costs[{{ $mcbController->id }}]" class="form-control mcb-controller-costs" 
                                        data-mcb-controller-index="{{ $index }}" value="{{ $mcbController->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-mcb-controller-{{ $index }}">{{ $mcbController->mcb_controller_units * $mcbController->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteMcbController" data-id="{{ $mcbController->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Charge Controller MCB --}}
                <h6>Add New Charge Controller MCB</h6>
                <table class="table table-bordered" id="addRemoveMcbController">
                    <thead>
                        <tr>
                            <th>Charge Controller MCB Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="mcb_controller_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($mcbControllers as $mcbController)
                                        <option value="{{ $mcbController->id }}">{{ $mcbController->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="mcb_controller_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="mcb_controller_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveMcbControllerButton">Add Charge Controller MCB</button></td>
                        </tr>
                    </tbody>
                </table>



                <hr class="mt-4">
                <h5>Inverter MCB</h5>

                @if(count($inventerMcbSystems) > 0)
                    <table class="table table-striped my-2" id="mcbInverterTable">
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
                            @foreach($inventerMcbSystems as $index => $mcbInventor)
                                <tr data-mcb-inverter-id="{{ $mcbInventor->id }}">
                                    <td class="text-center">{{ $mcbInventor->inverter_MCB_model }}</td>
                                    <td>
                                        <input type="number" step="any" name="mcb_inverter_units[{{ $mcbInventor->id }}]" class="form-control mcb-inverter-units" 
                                        data-mcb-inverter-index="{{ $index }}" value="{{ $mcbInventor->mcb_inverter_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="mcb_inverter_costs[{{ $mcbInventor->id }}]" class="form-control mcb-inverter-costs" 
                                        data-mcb-inverter-index="{{ $index }}" value="{{ $mcbInventor->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-mcb-inverter-{{ $index }}">{{ $mcbInventor->mcb_inverter_units * $mcbInventor->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteMcbInverter" data-id="{{ $mcbInventor->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Inverter MCB --}}
                <h6>Add New Inverter MCB</h6>
                <table class="table table-bordered" id="addRemoveMcbInverter">
                    <thead>
                        <tr>
                            <th>Inverter MCB Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="mcb_inverter_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($mcbInventors as $mcbInverter)
                                        <option value="{{ $mcbInverter->id }}">{{ $mcbInverter->inverter_MCB_model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="mcb_inverter_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="mcb_inverter_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveMcbInverterButton">Add Inverter MCB</button></td>
                        </tr>
                    </tbody>
                </table>




                <hr class="mt-4">
                <h5>Air Conditioner</h5>

                @if(count($airConditionerSystems) > 0)
                    <table class="table table-striped my-2" id="airConditionerTable">
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
                            @foreach($airConditionerSystems as $index => $conditioner)
                                <tr data-conditioner-id="{{ $conditioner->id }}">
                                    <td class="text-center">{{ $conditioner->model }}</td>
                                    <td>
                                        <input type="number" step="any" name="conditioner_units[{{ $conditioner->id }}]" class="form-control conditioner-units" 
                                        data-conditioner-index="{{ $index }}" value="{{ $conditioner->energy_air_conditioner_units }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="conditioner_costs[{{ $conditioner->id }}]" class="form-control conditioner-costs" 
                                        data-conditioner-index="{{ $index }}" value="{{ $conditioner->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-conditioner-{{ $index }}">{{ $conditioner->energy_air_conditioner_units * $conditioner->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteConditioner" data-id="{{ $conditioner->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Air Conditioners --}}
                <h6>Add New Air Conditioners</h6>
                <table class="table table-bordered" id="addRemoveAirConditioner">
                    <thead>
                        <tr>
                            <th>Air Conditioner Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="conditioner_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($airConditioners as $conditioner)
                                        <option value="{{ $conditioner->id }}">{{ $conditioner->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="conditioner_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" step="any" name="conditioner_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveAirConditionerButton">Add Conditioner</button></td>
                        </tr>
                    </tbody>
                </table>

                @if($energySystem->energy_system_type_id == 2) 

                    <hr class="mt-4">
                    <h5>FBS Cabinets</h5>

                    @if(count($fbsCabinets) > 0)
                        <table class="table table-striped my-2" id="fbsCabinetTable">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fbsCabinets as $index => $cabinet)
                                    <tr data-cabinet-id="{{ $cabinet->id }}">
                                        <td>
                                            <input type="number" step="any" name="cabinet_units[{{ $cabinet->id }}]" class="form-control cabinet-units" 
                                            data-cabinet-index="{{ $index }}" value="{{ $cabinet->unit }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="cabinet_costs[{{ $cabinet->id }}]" class="form-control cabinet-costs" 
                                            data-cabinet-index="{{ $index }}" value="{{ $cabinet->cost }}">
                                        </td>
                                        <td>
                                            <span id="total-cabinet-{{ $index }}">{{ $cabinet->unit * $cabinet->cost }}</span>
                                        </td>
                                        <td>
                                            <a class="btn deleteCabinet" data-id="{{ $cabinet->id }}"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @else 
                        {{-- Add More FBS Cabinets --}}
                        <h6>Add New FBS Cabinets</h6>
                        <table class="table table-bordered" id="addRemoveCabinet">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number"  step="any"name="cabinet_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number"  step="any"name="cabinet_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    

                    <hr class="mt-4">
                    <h5>FBS Fans</h5>

                    @if(count($fbsFans) > 0)
                        <table class="table table-striped my-2" id="fbsFanTable">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fbsFans as $index => $fan)
                                    <tr data-fan-id="{{ $fan->id }}">
                                        <td>
                                            <input type="number" step="any" name="fan_units[{{ $fan->id }}]" class="form-control fan-units" 
                                            data-fan-index="{{ $index }}" value="{{ $fan->unit }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="fan_costs[{{ $fan->id }}]" class="form-control fan-costs" 
                                            data-fan-index="{{ $index }}" value="{{ $fan->cost }}">
                                        </td>
                                        <td>
                                            <span id="total-fan-{{ $index }}">{{ $fan->unit * $fan->cost }}</span>
                                        </td>
                                        <td>
                                            <a class="btn deleteFan" data-id="{{ $fan->id }}"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @else
                        {{-- Add More FBS Fans --}}
                        <h6>Add New FBS Fans</h6>
                        <table class="table table-bordered" id="addRemoveFan">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" step="any" name="fan_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number" step="any" name="fan_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    

                    <hr class="mt-4">
                    <h5>FBS Locks</h5>

                    @if(count($fbsLocks) > 0)
                        <table class="table table-striped my-2" id="fbsLockTable">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fbsLocks as $index => $lock)
                                    <tr data-lock-id="{{ $lock->id }}">
                                        <td>
                                            <input type="number" step="any" name="lock_units[{{ $lock->id }}]" class="form-control lock-units" 
                                            data-lock-index="{{ $index }}" value="{{ $lock->unit }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="lock_costs[{{ $lock->id }}]" class="form-control lock-costs" 
                                            data-lock-index="{{ $index }}" value="{{ $lock->cost }}">
                                        </td>
                                        <td>
                                            <span id="total-lock-{{ $index }}">{{ $lock->unit * $lock->cost }}</span>
                                        </td>
                                        <td>
                                            <a class="btn deleteLock" data-id="{{ $lock->id }}"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @else
                            {{-- Add More FBS Locks --}}
                            <h6>Add New FBS Locks</h6>
                            <table class="table table-bordered" id="addRemoveLock">
                                <thead>
                                    <tr>
                                        <th>Units</th>
                                        <th>Cost per Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="number" step="any" name="lock_units[0][subject]" class="form-control" data-id="0"></td>
                                        <td><input type="number" step="any" name="lock_costs[0][subject]" class="form-control" data-id="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                    @endif

                    <hr class="mt-4">
                    <h5>FBS Wirings</h5>

                    @if(count($fbsWirings) > 0)
                        <table class="table table-striped my-2" id="fbsWiringTable">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fbsWirings as $index => $wiring)
                                    <tr data-wiring-id="{{ $wiring->id }}">
                                        <td>
                                            <input type="number" step="any" name="wiring_units[{{ $wiring->id }}]" class="form-control wiring-units" 
                                            data-wiring-index="{{ $index }}" value="{{ $wiring->unit }}">
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="wiring_costs[{{ $wiring->id }}]" class="form-control wiring-costs" 
                                            data-wiring-index="{{ $index }}" value="{{ $wiring->cost }}">
                                        </td>
                                        <td>
                                            <span id="total-wiring-{{ $index }}">{{ $wiring->unit * $wiring->cost }}</span>
                                        </td>
                                        <td>
                                            <a class="btn deleteFbsWiring" data-id="{{ $wiring->id }}"><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @else
                            {{-- Add More FBS Wirings --}}
                            <h6>Add New FBS Wirings</h6>
                            <table class="table table-bordered" id="addRemoveWiring">
                                <thead>
                                    <tr>
                                        <th>Units</th>
                                        <th>Cost per Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="number" step="any" name="wiring_units[0][subject]" class="form-control" data-id="0"></td>
                                        <td><input type="number" step="any" name="wiring_costs[0][subject]" class="form-control" data-id="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                    @endif

                @endif

                <hr class="mt-4">
                <h5>House Wirings</h5>

                @if(count($houseWirings) > 0)
                    <table class="table table-striped my-2" id="wiringHouseTable">
                        <thead>
                            <tr>
                                <th>Units</th>
                                <th>Cost per unit</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($houseWirings as $index => $wiringHouse)
                                <tr data-wiring-house-id="{{ $wiringHouse->id }}">
                                    <td>
                                        <input type="number" step="any" name="wiring_house_units[{{ $wiringHouse->id }}]" class="form-control wiring-house-units" 
                                        data-wiring-house-index="{{ $index }}" value="{{ $wiringHouse->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="wiring_house_costs[{{ $wiringHouse->id }}]" class="form-control wiring-house-costs" 
                                        data-wiring-house-index="{{ $index }}" value="{{ $wiringHouse->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-wiring-house-{{ $index }}">{{ $wiringHouse->unit * $wiringHouse->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteWiringHouse" data-id="{{ $wiringHouse->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                        {{-- Add More House Wirings --}}
                        <h6>Add New House Wirings</h6>
                        <table class="table table-bordered" id="addRemoveWiringHouse">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number"  step="any"name="wiring_house_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number" step="any" name="wiring_house_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                @endif
                    
                <hr class="mt-4">
                <h5>Electricity rooms</h5>

                @if(count($electricityRooms) > 0)
                    <table class="table table-striped my-2" id="electricityRoomTable">
                        <thead>
                            <tr>
                                <th>Units</th>
                                <th>Cost per unit</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($electricityRooms as $index => $electricityRoom)
                                <tr data-electricity-room-id="{{ $electricityRoom->id }}">
                                    <td>
                                        <input type="number" step="any" name="electricity_room_units[{{ $electricityRoom->id }}]" class="form-control electricity-room-units" 
                                        data-electricity-room-index="{{ $index }}" value="{{ $electricityRoom->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="electricity_room_costs[{{ $electricityRoom->id }}]" class="form-control electricity-room-costs" 
                                        data-electricity-room-index="{{ $index }}" value="{{ $electricityRoom->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-electricity-room-{{ $index }}">{{ $electricityRoom->unit * $electricityRoom->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteElectricityRoom" data-id="{{ $electricityRoom->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                        {{-- Add More Electricity rooms --}}
                        <h6>Add New Electricity rooms</h6>
                        <table class="table table-bordered" id="addRemoveElectricityRoom">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" step="any" name="electricity_room_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number" step="any" name="electricity_room_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                @endif

                <hr class="mt-4">
                <h5>Electricity Bos rooms</h5>

                @if(count($electricityBosRooms) > 0)
                    <table class="table table-striped my-2" id="electricityBosRoomTable">
                        <thead>
                            <tr>
                                <th>Units</th>
                                <th>Cost per unit</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($electricityBosRooms as $index => $electricityBosRoom)
                                <tr data-electricity-bos-room-id="{{ $electricityBosRoom->id }}">
                                    <td>
                                        <input type="number" step="any" name="electricity_bos_room_units[{{ $electricityBosRoom->id }}]" class="form-control electricity-bos-room-units" 
                                        data-electricity-bos-room-index="{{ $index }}" value="{{ $electricityBosRoom->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="electricity_bos_room_costs[{{ $electricityBosRoom->id }}]" class="form-control electricity-bos-room-costs" 
                                        data-electricity-bos-room-index="{{ $index }}" value="{{ $electricityBosRoom->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-electricity-bos-room-{{ $index }}">{{ $electricityBosRoom->unit * $electricityBosRoom->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteElectricityBosRoom" data-id="{{ $electricityBosRoom->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                        {{-- Add More Electricity bos rooms --}}
                        <h6>Add New Electricity bos rooms</h6>
                        <table class="table table-bordered" id="addRemoveElectricityBosRoom">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" step="any" name="electricity_bos_room_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number" step="any" name="electricity_bos_room_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                @endif


                <hr class="mt-4">
                <h5>Community/Compound Grid</h5>

                @if(count($communityGrids) > 0)
                    <table class="table table-striped my-2" id="gridTable">
                        <thead>
                            <tr>
                                <th>Units</th>
                                <th>Cost per unit</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($communityGrids as $index => $communityGrid)
                                <tr data-grid-id="{{ $communityGrid->id }}">
                                    <td>
                                        <input type="number" step="any" name="grid_units[{{ $communityGrid->id }}]" class="form-control grid-units" 
                                        data-grid-index="{{ $index }}" value="{{ $communityGrid->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="grid_costs[{{ $communityGrid->id }}]" class="form-control grid-costs" 
                                        data-grid-index="{{ $index }}" value="{{ $communityGrid->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-grid-{{ $index }}">{{ $communityGrid->unit * $communityGrid->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteGrid" data-id="{{ $communityGrid->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                        {{-- Add More Community/Compound Grid --}}
                        <h6>Add New Community/Compound Grid</h6>
                        <table class="table table-bordered" id="addRemoveGrid">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" step="any" name="grid_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number" step="any" name="grid_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                @endif

                <hr class="mt-4">
                <h5>Refrigerator</h5>

                @if(count($refrigerators) > 0)
                    <table class="table table-striped my-2" id="refrigeratorTable">
                        <thead>
                            <tr>
                                <th>Units</th>
                                <th>Cost per unit</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($refrigerators as $index => $refrigerator)
                                <tr data-refrigerator-id="{{ $refrigerator->id }}">
                                    <td>
                                        <input type="number" step="any" name="refrigerator_units[{{ $refrigerator->id }}]" class="form-control refrigerator-units" 
                                        data-refrigerator-index="{{ $index }}" value="{{ $refrigerator->unit }}">
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="refrigerator_costs[{{ $refrigerator->id }}]" class="form-control refrigerator-costs" 
                                        data-refrigerator-index="{{ $index }}" value="{{ $refrigerator->cost }}">
                                    </td>
                                    <td>
                                        <span id="total-refrigerator-{{ $index }}">{{ $refrigerator->unit * $refrigerator->cost }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteRefrigerator" data-id="{{ $refrigerator->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                        {{-- Add More Refrigerator --}}
                        <h6>Add New Refrigerator</h6>
                        <table class="table table-bordered" id="addRemoveRefrigerator">
                            <thead>
                                <tr>
                                    <th>Units</th>
                                    <th>Cost per Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="number" step="any" name="refrigerator_units[0][subject]" class="form-control" data-id="0"></td>
                                    <td><input type="number" step="any" name="refrigerator_costs[0][subject]" class="form-control" data-id="0"></td>
                                </tr>
                            </tbody>
                        </table>
                @endif

                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    let batteryIndex = 1;
    const batterysData = @json($batteries);

    $('#addRemoveBatteryButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        batterysData.forEach(t => {
            options += `<option value="${t.id}">${t.battery_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="battery_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="battery_units[${batteryIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="battery_costs[${batteryIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveBattery tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        batteryIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Battery
    const debounceTimersBattery = {};
    $(document).on('input', '.battery-units, .battery-costs', function () {
        const indexBattery = $(this).data('battery-index'); 

        // Use correct attribute selector data-battery-index
        const unit = parseFloat($(`.battery-units[data-battery-index="${indexBattery}"]`).val()) || 0;
        const cost = parseFloat($(`.battery-costs[data-battery-index="${indexBattery}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-battery-${indexBattery}`).text(total);

        clearTimeout(debounceTimersBattery[indexBattery]);
        debounceTimersBattery[indexBattery] = setTimeout(() => {
            const row = $(this).closest('tr');
            const batteryId = row.data('battery-id');

            $.ajax({
                url: `/update-energy-battery/${batteryId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system battery
    $('#batteryTable').on('click', '.deleteBattery',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this battery?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemBattery') }}",
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


    let batteryMountIndex = 1;
    const batterysMountData = @json($batteryMounts);

    $('#addRemoveBatteryMountButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        batterysMountData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="battery_mount_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="battery_mount_units[${batteryMountIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="battery_mount_costs[${batteryMountIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveBatteryMount tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        batteryMountIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total BatteryMount
    const debounceTimersBatteryMount = {};
    $(document).on('input', '.battery-mount-units, .battery-mount-costs', function () {
        const indexBatteryMount = $(this).data('battery-mount-index'); 

        // Use correct attribute selector data-battery-index
        const unit = parseFloat($(`.battery-mount-units[data-battery-mount-index="${indexBatteryMount}"]`).val()) || 0;
        const cost = parseFloat($(`.battery-mount-costs[data-battery-mount-index="${indexBatteryMount}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-battery-mount-${indexBatteryMount}`).text(total);

        clearTimeout(debounceTimersBatteryMount[indexBatteryMount]);
        debounceTimersBatteryMount[indexBatteryMount] = setTimeout(() => {
            const row = $(this).closest('tr');
            const batteryMountId = row.data('battery-mount-id');

            $.ajax({
                url: `/update-energy-battery-mount/${batteryMountId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system battery mount
    $('#batteryMountTable').on('click', '.deleteBatteryMount',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this battery mount?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemBatteryMount') }}",
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


    let pvIndex = 1;
    const pvsData = @json($pvs);

    $('#addRemovePvButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        pvsData.forEach(t => {
            options += `<option value="${t.id}">${t.pv_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="pv_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="pv_units[${pvIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="pv_costs[${pvIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemovePv tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        pvIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total PV
    const debounceTimersPv = {};
    $(document).on('input', '.pv-units, .pv-costs', function () {
        const indexPv = $(this).data('pv-index'); 

        // Use correct attribute selector data-pv-index
        const unit = parseFloat($(`.pv-units[data-pv-index="${indexPv}"]`).val()) || 0;
        const cost = parseFloat($(`.pv-costs[data-pv-index="${indexPv}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-pv-${indexPv}`).text(total);

        clearTimeout(debounceTimersPv[indexPv]);
        debounceTimersPv[indexPv] = setTimeout(() => {
            const row = $(this).closest('tr');
            const pvId = row.data('pv-id');

            $.ajax({
                url: `/update-energy-pv/${pvId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system pv
    $('#pvTable').on('click', '.deletePv',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Solar Panel?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemPv') }}",
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

    let pvMountIndex = 1;
    const pvsMountData = @json($pvMounts);

    $('#addRemovePvMountButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        pvsMountData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="pv_mount_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="pv_mount_units[${pvMountIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="pv_mount_costs[${pvMountIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemovePvMount tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        pvMountIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total pv Mount
    const debounceTimersPvMount = {};
    $(document).on('input', '.pv-mount-units, .pv-mount-costs', function () {
        const indexPvMount = $(this).data('pv-mount-index'); 

        // Use correct attribute selector data-pv-index
        const unit = parseFloat($(`.pv-mount-units[data-pv-mount-index="${indexPvMount}"]`).val()) || 0;
        const cost = parseFloat($(`.pv-mount-costs[data-pv-mount-index="${indexPvMount}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-pv-mount-${indexPvMount}`).text(total);

        clearTimeout(debounceTimersPvMount[indexPvMount]);
        debounceTimersPvMount[indexPvMount] = setTimeout(() => {
            const row = $(this).closest('tr');
            const pvMountId = row.data('pv-mount-id');

            $.ajax({
                url: `/update-energy-pv-mount/${pvMountId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system pv Mount
    $('#pvMountTable').on('click', '.deletePvMount',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Solar Panel Mount?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemPvMount') }}",
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

    let controllerIndex = 1;
    const controllersData = @json($controllers);

    $('#addRemoveControllerButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        controllersData.forEach(t => {
            options += `<option value="${t.id}">${t.charge_controller_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="controller_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="controller_units[${controllerIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="controller_costs[${controllerIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveController tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        controllerIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Controller
    const debounceTimersController = {};
    $(document).on('input', '.controller-units, .controller-costs', function () {
        const indexController = $(this).data('controller-index'); 

        // Use correct attribute selector data-controller-index
        const unit = parseFloat($(`.controller-units[data-controller-index="${indexController}"]`).val()) || 0;
        const cost = parseFloat($(`.controller-costs[data-controller-index="${indexController}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-controller-${indexController}`).text(total);

        clearTimeout(debounceTimersController[indexController]);
        debounceTimersController[indexController] = setTimeout(() => {
            const row = $(this).closest('tr');
            const controllerId = row.data('controller-id');

            $.ajax({
                url: `/update-energy-controller/${controllerId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system controller
    $('#controllerTable').on('click', '.deleteController',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this controller?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemController') }}",
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

    let mcbPvIndex = 1;
    const mcbPvsData = @json($mcbPvs);

    $('#addRemoveMcbPvButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        mcbPvsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="mcb_pv_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="mcb_pv_units[${mcbPvIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="mcb_pv_costs[${mcbPvIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveMcbPv tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        mcbPvIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total McbPv
    const debounceTimersMcbPv = {};
    $(document).on('input', '.mcb-pv-units, .mcb-pv-costs', function () {
        const indexMcbPv = $(this).data('mcb-pv-index'); 

        // Use correct attribute selector data-mcb-pv-index
        const unit = parseFloat($(`.mcb-pv-units[data-mcb-pv-index="${indexMcbPv}"]`).val()) || 0;
        const cost = parseFloat($(`.mcb-pv-costs[data-mcb-pv-index="${indexMcbPv}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-mcb-pv-${indexMcbPv}`).text(total);

        clearTimeout(debounceTimersMcbPv[indexMcbPv]);
        debounceTimersMcbPv[indexMcbPv] = setTimeout(() => {
            const row = $(this).closest('tr');
            const mcbPvId = row.data('mcb-pv-id');

            $.ajax({
                url: `/update-energy-mcb-pv/${mcbPvId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system MCB PV
    $('#mcbPvTable').on('click', '.deleteMcbPv',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this MCB PV?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemMcbPv') }}",
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

    let bspIndex = 1;
    const bspsData = @json($bsps);

    $('#addRemoveBspButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        bspsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="bsp_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="bsp_units[${bspIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="bsp_costs[${bspIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveBsp tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        bspIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Bsp
    const debounceTimersBsp = {};
    $(document).on('input', '.bsp-units, .bsp-costs', function () {
        const indexBsp = $(this).data('bsp-index'); 

        // Use correct attribute selector data-bsp-index
        const unit = parseFloat($(`.bsp-units[data-bsp-index="${indexBsp}"]`).val()) || 0;
        const cost = parseFloat($(`.bsp-costs[data-bsp-index="${indexBsp}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-bsp-${indexBsp}`).text(total);

        clearTimeout(debounceTimersBsp[indexBsp]);
        debounceTimersBsp[indexBsp] = setTimeout(() => {
            const row = $(this).closest('tr');
            const bspId = row.data('bsp-id');

            $.ajax({
                url: `/update-energy-bsp/${bspId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system BSP
    $('#bspTable').on('click', '.deleteBsp',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this BSP?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemBsp') }}",
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


    let loggerIndex = 1;
    const loggersData = @json($loggers);

    $('#addRemoveLoggerButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        loggersData.forEach(t => {
            options += `<option value="${t.id}">${t.monitoring_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="logger_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="logger_units[${loggerIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="logger_costs[${loggerIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveLogger tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        loggerIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Logger
    const debounceTimersLogger = {};
    $(document).on('input', '.logger-units, .logger-costs', function () {
        const indexLogger = $(this).data('logger-index'); 

        // Use correct attribute selector data-logger-index
        const unit = parseFloat($(`.logger-units[data-logger-index="${indexLogger}"]`).val()) || 0;
        const cost = parseFloat($(`.logger-costs[data-logger-index="${indexLogger}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-logger-${indexLogger}`).text(total);

        clearTimeout(debounceTimersLogger[indexLogger]);
        debounceTimersLogger[indexLogger] = setTimeout(() => {
            const row = $(this).closest('tr');
            const loggerId = row.data('logger-id');

            $.ajax({
                url: `/update-energy-logger/${loggerId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Logger
    $('#loggerTable').on('click', '.deleteLogger',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Logger?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemMonitoring') }}",
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

    let loadRelayIndex = {{ count($loadRelaySystems) }}; // Continue from existing
    const loadRelaysData = @json($loadRelaies);

    $('#addRemoveLoadRelayButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        loadRelaysData.forEach(t => {
            options += `<option value="${t.id}">${t.load_relay_model}</option>`;
        });

        const newRow = `
            <tr>
                <td>
                    <select name="load_relay_ids[]" class="selectpicker form-control" data-live-search="true">
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" step="any" name="load-relay-units[${loadRelayIndex}][subject]" 
                        class="form-control load-relay-units" 
                        data-load-relay-index="${loadRelayIndex}">
                </td>
                <td>
                    <input type="number" step="any" name="load-relay-costs[${loadRelayIndex}][subject]" 
                        class="form-control load-relay-costs" 
                        data-load-relay-index="${loadRelayIndex}">
                </td>
                <td>
                    <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                </td>
            </tr>
        `;

        $('#addRemoveLoadRelay tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        loadRelayIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total (existing + dynamic rows)
    const debounceTimersLoadRelay = {};
    $(document).on('input', '.load-relay-units, .load-relay-costs', function () {
        const index = $(this).data('load-relay-index');

        const unit = parseFloat($(`.load-relay-units[data-load-relay-index="${index}"]`).val()) || 0;
        const cost = parseFloat($(`.load-relay-costs[data-load-relay-index="${index}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);

        $(`#total-load-relay-${index}`).text(total);

        // Only send AJAX if this row has a load-relay ID (i.e., it's from the DB)
        const row = $(this).closest('tr');
        const loadRelayId = row.data('load-relay-id');

        if (loadRelayId) {
            clearTimeout(debounceTimersLoadRelay[index]);
            debounceTimersLoadRelay[index] = setTimeout(() => {
                $.ajax({
                    url: `/update-energy-load-relay/${loadRelayId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                        }
                    },
                    error: function () {
                        Swal.fire({ icon: 'error', title: 'Update failed', confirmButtonText: 'Retry' });
                    }
                });
            }, 500);
        }
    });

    // delete energy system Load Relay
    $('#loadRelayTable').on('click', '.deleteLoadRelay',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Load Relay?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemLoadRelay') }}",
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

    let inverterIndex = 1;
    const invertersData = @json($inverters);

    $('#addRemoveInverterButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        invertersData.forEach(t => {
            options += `<option value="${t.id}">${t.inverter_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="inverter_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="inverter_units[${inverterIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="inverter_costs[${inverterIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveInverter tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        inverterIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Inverter
    const debounceTimersInverter = {};
    $(document).on('input', '.inverter-units, .inverter-costs', function () {
        const indexInverter = $(this).data('inverter-index'); 

        // Use correct attribute selector data-inverter-index
        const unit = parseFloat($(`.inverter-units[data-inverter-index="${indexInverter}"]`).val()) || 0;
        const cost = parseFloat($(`.inverter-costs[data-inverter-index="${indexInverter}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-inverter-${indexInverter}`).text(total);

        clearTimeout(debounceTimersInverter[indexInverter]);
        debounceTimersInverter[indexInverter] = setTimeout(() => {
            const row = $(this).closest('tr');
            const inverterId = row.data('inverter-id');

            $.ajax({
                url: `/update-energy-inverter/${inverterId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Inverter
    $('#inverterTable').on('click', '.deleteInverter',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Inverter?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemInverter') }}",
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


    let btsIndex = 1;
    const btssData = @json($btss);

    $('#addRemoveBtsButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        btssData.forEach(t => {
            options += `<option value="${t.id}">${t.BTS_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="bts_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="bts_units[${btsIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="bts_costs[${btsIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveBts tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        btsIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Bts
    const debounceTimersBts = {};
    $(document).on('input', '.bts-units, .bts-costs', function () {
        const indexBts = $(this).data('bts-index'); 

        // Use correct attribute selector data-bts-index
        const unit = parseFloat($(`.bts-units[data-bts-index="${indexBts}"]`).val()) || 0;
        const cost = parseFloat($(`.bts-costs[data-bts-index="${indexBts}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-bts-${indexBts}`).text(total);

        clearTimeout(debounceTimersBts[indexBts]);
        debounceTimersBts[indexBts] = setTimeout(() => {
            const row = $(this).closest('tr');
            const btsId = row.data('bts-id');

            $.ajax({
                url: `/update-energy-bts/${btsId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system BTS
    $('#btsTable').on('click', '.deleteBts',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this bts?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemBts') }}",
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


    let generatorIndex = 1;
    const generatorsData = @json($generators);

    $('#addRemoveGeneratorButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        generatorsData.forEach(t => {
            options += `<option value="${t.id}">${t.generator_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="generator_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="generator_units[${generatorIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="generator_costs[${generatorIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveGenerator tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        generatorIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Generator
    const debounceTimersGenerator = {};
    $(document).on('input', '.generator-units, .generator-costs', function () {
        const indexGenerator = $(this).data('generator-index'); 

        // Use correct attribute selector data-generator-index
        const unit = parseFloat($(`.generator-units[data-generator-index="${indexGenerator}"]`).val()) || 0;
        const cost = parseFloat($(`.generator-costs[data-generator-index="${indexGenerator}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-generator-${indexGenerator}`).text(total);

        clearTimeout(debounceTimersGenerator[indexGenerator]);
        debounceTimersGenerator[indexGenerator] = setTimeout(() => {
            const row = $(this).closest('tr');
            const generatorId = row.data('generator-id');

            $.ajax({
                url: `/update-energy-generator/${generatorId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Generator
    $('#generatorTable').on('click', '.deleteGenerator',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Generator?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemGenerator') }}",
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


    let rccIndex = 1;
    const rccsData = @json($rccs);

    $('#addRemoveRccButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        rccsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="rcc_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="rcc_units[${rccIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="rcc_costs[${rccIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveRcc tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        rccIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Rcc
    const debounceTimersRcc = {};
    $(document).on('input', '.rcc-units, .rcc-costs', function () {
        const indexRcc = $(this).data('rcc-index'); 

        // Use correct attribute selector data-rcc-index
        const unit = parseFloat($(`.rcc-units[data-rcc-index="${indexRcc}"]`).val()) || 0;
        const cost = parseFloat($(`.rcc-costs[data-rcc-index="${indexRcc}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-rcc-${indexRcc}`).text(total);

        clearTimeout(debounceTimersRcc[indexRcc]);
        debounceTimersRcc[indexRcc] = setTimeout(() => {
            const row = $(this).closest('tr');
            const rccId = row.data('rcc-id');

            $.ajax({
                url: `/update-energy-rcc/${rccId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system RCC
    $('#rccTable').on('click', '.deleteRcc',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Rcc?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemRcc') }}",
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

    let relayDriverIndex = 1;
    const relayDriversData = @json($relayDrivers);

    $('#addRemoveRelayDriverButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        relayDriversData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="relay_driver_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="relay-driver_units[${relayDriverIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="relay-driver_costs[${relayDriverIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveRelayDriver tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        relayDriverIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total RelayDriver
    const debounceTimersRelayDriver = {};
    $(document).on('input', '.relay-driver-units, .relay-driver-costs', function () {
        const indexRelayDriver = $(this).data('relay-driver-index'); 

        // Use correct attribute selector data-relay-driver-index
        const unit = parseFloat($(`.relay-driver-units[data-relay-driver-index="${indexRelayDriver}"]`).val()) || 0;
        const cost = parseFloat($(`.relay-driver-costs[data-relay-driver-index="${indexRelayDriver}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-relay-driver-${indexRelayDriver}`).text(total);

        clearTimeout(debounceTimersRelayDriver[indexRelayDriver]);
        debounceTimersRelayDriver[indexRelayDriver] = setTimeout(() => {
            const row = $(this).closest('tr');
            const relayDriverId = row.data('relay-driver-id');

            $.ajax({
                url: `/update-energy-relay-driver/${relayDriverId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Relay Driver
    $('#relayDriverTable').on('click', '.deleteRelayDriver',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Relay Driver?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemRelayDriver') }}",
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

    let turbineIndex = 1;
    const turbinesData = @json($turbines);

    $('#addRemoveTurbineButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        turbinesData.forEach(t => {
            options += `<option value="${t.id}">${t.wind_turbine_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="turbine_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="turbine_units[${turbineIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="turbine_costs[${turbineIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveTurbine tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        turbineIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Turbine
    const debounceTimersTurbine = {};
    $(document).on('input', '.turbine-units, .turbine-costs', function () {
        const indexTurbine = $(this).data('turbine-index'); 

        // Use correct attribute selector data-turbine-index
        const unit = parseFloat($(`.turbine-units[data-turbine-index="${indexTurbine}"]`).val()) || 0;
        const cost = parseFloat($(`.turbine-costs[data-turbine-index="${indexTurbine}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-turbine-${indexTurbine}`).text(total);

        clearTimeout(debounceTimersTurbine[indexTurbine]);
        debounceTimersTurbine[indexTurbine] = setTimeout(() => {
            const row = $(this).closest('tr');
            const turbineId = row.data('turbine-id');

            $.ajax({
                url: `/update-energy-turbine/${turbineId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Turbine
    $('#turbineTable').on('click', '.deleteTurbine',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Turbine?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemTurbine') }}",
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


    let mcbControllerIndex = 1;
    const mcbControllersData = @json($mcbControllers);

    $('#addRemoveMcbControllerButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        mcbControllersData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="mcb_controller_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="mcb_controller_units[${mcbControllerIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="mcb_controller_costs[${mcbControllerIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveMcbController tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        mcbControllerIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total McbController
    const debounceTimersMcbController = {};
    $(document).on('input', '.mcb-controller-units, .mcb-controller-costs', function () {
        const indexMcbController = $(this).data('mcb-controller-index'); 

        // Use correct attribute selector data-mcb-controller-index
        const unit = parseFloat($(`.mcb-controller-units[data-mcb-controller-index="${indexMcbController}"]`).val()) || 0;
        const cost = parseFloat($(`.mcb-controller-costs[data-mcb-controller-index="${indexMcbController}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-mcb-controller-${indexMcbController}`).text(total);

        clearTimeout(debounceTimersMcbController[indexMcbController]);
        debounceTimersMcbController[indexMcbController] = setTimeout(() => {
            const row = $(this).closest('tr');
            const mcbControllerId = row.data('mcb-controller-id');

            $.ajax({
                url: `/update-energy-mcb-controller/${mcbControllerId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });
    // delete energy system Mcb Controller
    $('#mcbControllerTable').on('click', '.deleteMcbController',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Mcb Controller?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemMcbController') }}",
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


    let mcbInverterIndex = 1;
    const mcbInvertersData = @json($mcbInventors); 

    $('#addRemoveMcbInverterButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        mcbInvertersData.forEach(t => {
            options += `<option value="${t.id}">${t.inverter_MCB_model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="mcb_inverter_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="mcb_inverter_units[${mcbInverterIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="mcb_inverter_costs[${mcbInverterIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveMcbInverter tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        mcbInverterIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total McbInverter
    const debounceTimersMcbInverter = {};
    $(document).on('input', '.mcb-inverter-units, .mcb-inverter-costs', function () {
        const indexMcbInverter = $(this).data('mcb-inverter-index'); 

        // Use correct attribute selector data-mcb-inverter-index
        const unit = parseFloat($(`.mcb-inverter-units[data-mcb-inverter-index="${indexMcbInverter}"]`).val()) || 0;
        const cost = parseFloat($(`.mcb-inverter-costs[data-mcb-inverter-index="${indexMcbInverter}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-mcb-inverter-${indexMcbInverter}`).text(total);

        clearTimeout(debounceTimersMcbInverter[indexMcbInverter]);
        debounceTimersMcbInverter[indexMcbInverter] = setTimeout(() => {
            const row = $(this).closest('tr');
            const mcbinverterId = row.data('mcb-inverter-id');

            $.ajax({
                url: `/update-energy-mcb-inverter/${mcbinverterId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });
    // delete energy system Mcb Inverter
    $('#mcbInverterTable').on('click', '.deleteMcbInverter',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Mcb Inverter?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemMcbInverter') }}",
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



    // Air Conditioner 
    let airConditionerIndex = 1;
    const airConditionersData = @json($airConditioners);

    $('#addRemoveAirConditionerButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        airConditionersData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="conditioner_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" step="any" name="conditioner_units[${airConditionerIndex}][subject]" class="form-control"></td>
                <td><input type="number" step="any" name="conditioner_costs[${airConditionerIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveAirConditioner tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        airConditionerIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total Air Conditioner
    const debounceTimersAirConditioner = {};
    $(document).on('input', '.conditioner-units, .conditioner-costs', function () {
        const indexAirConditioner = $(this).data('conditioner-index'); 

        // Use correct attribute selector data-conditioner-index
        const unit = parseFloat($(`.conditioner-units[data-conditioner-index="${indexAirConditioner}"]`).val()) || 0;
        const cost = parseFloat($(`.conditioner-costs[data-conditioner-index="${indexAirConditioner}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-conditioner-${indexAirConditioner}`).text(total);

        clearTimeout(debounceTimersAirConditioner[indexAirConditioner]);
        debounceTimersAirConditioner[indexAirConditioner] = setTimeout(() => {
            const row = $(this).closest('tr');
            const conditionerId = row.data('conditioner-id');

            $.ajax({
                url: `/update-energy-conditioner/${conditionerId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Air Conditioner    
    $('#airConditionerTable').on('click', '.deleteConditioner',function() {    
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Air Conditioner?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemAirConditioner') }}",
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

    // FBS Cabinet
    // Auto-calculate total Cabinet
    const debounceTimersCabinet = {};
    $(document).on('input', '.cabinet-units, .cabinet-costs', function () {
        const indexcabinet = $(this).data('cabinet-index'); 

        // Use correct attribute selector data-cabinet-index
        const unit = parseFloat($(`.cabinet-units[data-cabinet-index="${indexcabinet}"]`).val()) || 0;
        const cost = parseFloat($(`.cabinet-costs[data-cabinet-index="${indexcabinet}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-cabinet-${indexcabinet}`).text(total);

        clearTimeout(debounceTimersCabinet[indexcabinet]);
        debounceTimersCabinet[indexcabinet] = setTimeout(() => {
            const row = $(this).closest('tr');
            const cabinetId = row.data('cabinet-id');

            $.ajax({
                url: `/update-energy-cabinet/${cabinetId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system cabinet
    $('#fbsCabinetTable').on('click', '.deleteCabinet',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Cabinet?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemCabinet') }}",
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

    // FBS Fan
    // Auto-calculate total Fan
    const debounceTimersFan = {};
    $(document).on('input', '.fan-units, .fan-costs', function () {
        const indexfan = $(this).data('fan-index'); 

        // Use correct attribute selector data-fan-index
        const unit = parseFloat($(`.fan-units[data-fan-index="${indexfan}"]`).val()) || 0;
        const cost = parseFloat($(`.fan-costs[data-fan-index="${indexfan}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-fan-${indexfan}`).text(total);

        clearTimeout(debounceTimersFan[indexfan]);
        debounceTimersFan[indexfan] = setTimeout(() => {
            const row = $(this).closest('tr');
            const fanId = row.data('fan-id');

            $.ajax({
                url: `/update-energy-fan/${fanId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system fan
    $('#fbsFanTable').on('click', '.deleteFan',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Fan?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemFan') }}",
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

    // FBS Lock
    // Auto-calculate total Lock
    const debounceTimersLock = {};
    $(document).on('input', '.lock-units, .lock-costs', function () {
        const indexlock = $(this).data('lock-index'); 

        // Use correct attribute selector data-lock-index
        const unit = parseFloat($(`.lock-units[data-lock-index="${indexlock}"]`).val()) || 0;
        const cost = parseFloat($(`.lock-costs[data-lock-index="${indexlock}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-lock-${indexlock}`).text(total);

        clearTimeout(debounceTimersLock[indexlock]);
        debounceTimersLock[indexlock] = setTimeout(() => {
            const row = $(this).closest('tr');
            const lockId = row.data('lock-id');

            $.ajax({
                url: `/update-energy-lock/${lockId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system lock
    $('#fbsLockTable').on('click', '.deleteLock',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Lock?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemLock') }}",
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

    // FBS Wiring
    // Auto-calculate total wiring
    const debounceTimersWiring = {};
    $(document).on('input', '.wiring-units, .wiring-costs', function () {
        const indexwiring = $(this).data('wiring-index'); 

        // Use correct attribute selector data-wiring-index
        const unit = parseFloat($(`.wiring-units[data-wiring-index="${indexwiring}"]`).val()) || 0;
        const cost = parseFloat($(`.wiring-costs[data-wiring-index="${indexwiring}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-wiring-${indexwiring}`).text(total);

        clearTimeout(debounceTimersWiring[indexwiring]);
        debounceTimersWiring[indexwiring] = setTimeout(() => {
            const row = $(this).closest('tr');
            const wiringId = row.data('wiring-id');

            $.ajax({
                url: `/update-energy-wiring/${wiringId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system wiring
    $('#fbsWiringTable').on('click', '.deleteFbsWiring', function() {
        var id = $(this).data('id');
        var $ele = $(this).closest('tr'); 

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this FBS Wiring?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemWiring') }}",
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
                                confirmButtonText: 'Okay!'
                            }).then(() => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info');
            }
        });
    });





    // electricity room
    // Auto-calculate total ElectricityRoom
    const debounceTimersElectricityRoom = {};
    $(document).on('input', '.electricity-room-units, .electricity-room-costs', function () {
        const indexElectricityRoom = $(this).data('electricity-room-index'); 

        // Use correct attribute selector data-electricity-room-index
        const unit = parseFloat($(`.electricity-room-units[data-electricity-room-index="${indexElectricityRoom}"]`).val()) || 0;
        const cost = parseFloat($(`.electricity-room-costs[data-electricity-room-index="${indexElectricityRoom}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-electricity-room-${indexElectricityRoom}`).text(total);

        clearTimeout(debounceTimersElectricityRoom[indexElectricityRoom]);
        debounceTimersElectricityRoom[indexElectricityRoom] = setTimeout(() => {
            const row = $(this).closest('tr');
            const electricityRoomId = row.data('electricity-room-id');

            $.ajax({
                url: `/update-energy-electricity-room/${electricityRoomId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system ElectricityRoom
    $('#electricityRoomTable').on('click', '.deleteElectricityRoom',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Electricity Room?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemElectricityRoom') }}",
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
                                confirmButtonText: 'Okay!'
                            }).then(() => {
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

    // electricity bos room
    // Auto-calculate total ElectricityRoom
    const debounceTimersElectricityBosRoom = {};
    $(document).on('input', '.electricity-bos-room-units, .electricity-bos-room-costs', function () {
        const indexElectricityBosRoom = $(this).data('electricity-bos-room-index'); 

        // Use correct attribute selector data-electricity-room-index
        const unit = parseFloat($(`.electricity-bos-room-units[data-electricity-bos-room-index="${indexElectricityBosRoom}"]`).val()) || 0;
        const cost = parseFloat($(`.electricity-bos-room-costs[data-electricity-bos-room-index="${indexElectricityBosRoom}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-electricity-bos-room-${indexElectricityBosRoom}`).text(total);

        clearTimeout(debounceTimersElectricityBosRoom[indexElectricityBosRoom]);
        debounceTimersElectricityBosRoom[indexElectricityBosRoom] = setTimeout(() => {
            const row = $(this).closest('tr');
            const electricityBosRoomId = row.data('electricity-bos-room-id');

            $.ajax({
                url: `/update-energy-electricity-room-bos/${electricityBosRoomId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Electricity Bos Room
    $('#electricityBosRoomTable').on('click', '.deleteElectricityBosRoom',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Electricity Bos Room?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemElectricityBosRoom') }}",
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


    // House Wiring
    // Auto-calculate total Wiring
    const debounceTimersWiringHouse = {};
    $(document).on('input', '.wiring-house-units, .wiring-house-costs', function () {
        const indexwiringHouse = $(this).data('wiring-house-index'); 

        // Use correct attribute selector data-wiring-index
        const unit = parseFloat($(`.wiring-house-units[data-wiring-house-index="${indexwiringHouse}"]`).val()) || 0;
        const cost = parseFloat($(`.wiring-house-costs[data-wiring-house-index="${indexwiringHouse}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-wiring-house-${indexwiringHouse}`).text(total);

        clearTimeout(debounceTimersWiringHouse[indexwiringHouse]);
        debounceTimersWiringHouse[indexwiringHouse] = setTimeout(() => {
            const row = $(this).closest('tr');
            const wiringHouseId = row.data('wiring-house-id');

            $.ajax({
                url: `/update-energy-wiring-house/${wiringHouseId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Wiring House
    $('#wiringHouseTable').on('click', '.deleteWiringHouse',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this House Wiring?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemWiringHouse') }}",
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


    // Grids
    // Auto-calculate total Grid
    const debounceTimersGrid = {};
    $(document).on('input', '.grid-units, .grid-costs', function () {

        const indexGrid = $(this).data('grid-index'); 

        // Use correct attribute selector data-grid-index
        const unit = parseFloat($(`.grid-units[data-grid-index="${indexGrid}"]`).val()) || 0;
        const cost = parseFloat($(`.grid-costs[data-grid-index="${indexGrid}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-grid-${indexGrid}`).text(total);

        clearTimeout(debounceTimersGrid[indexGrid]);
        debounceTimersGrid[indexGrid] = setTimeout(() => {
            const row = $(this).closest('tr');
            const gridId = row.data('grid-id');

            $.ajax({
                url: `/update-energy-grid/${gridId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system Grid
    $('#gridTable').on('click', '.deleteGrid',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Grid?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemGrid') }}",
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

    // refrigerator
    // Auto-calculate total Refrigerator
    const debounceTimersRefrigerator = {};
    $(document).on('input', '.refrigerator-units, .refrigerator-costs', function () {

        const indexRefrigerator = $(this).data('refrigerator-index'); 

        // Use correct attribute selector data-refrigerator-index
        const unit = parseFloat($(`.refrigerator-units[data-refrigerator-index="${indexRefrigerator}"]`).val()) || 0;
        const cost = parseFloat($(`.refrigerator-costs[data-refrigerator-index="${indexRefrigerator}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-refrigerator-${indexRefrigerator}`).text(total);

        clearTimeout(debounceTimersRefrigerator[indexRefrigerator]);
        debounceTimersRefrigerator[indexRefrigerator] = setTimeout(() => {
            const row = $(this).closest('tr');
            const refrigeratorId = row.data('refrigerator-id');

            $.ajax({
                url: `/update-energy-refrigerator/${refrigeratorId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete energy system refrigerator
    $('#refrigeratorTable').on('click', '.deleteRefrigerator',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Rrefrigerator?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergySystemRefrigerator') }}",
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

</script>

@endsection