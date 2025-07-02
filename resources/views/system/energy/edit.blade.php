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

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>batteries</h6>
                </div>
                @if(count($battarySystems) > 0)

                    <table id="energySystemBatteryTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($battarySystems as $battarySystem)
                            <tr id="battarySystemsRow">
                                <td class="text-center">
                                    {{$battarySystem->battery_model}}
                                </td>
                                <td class="text-center">
                                    {{$battarySystem->battery_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemBattery" 
                                        id="deleteEnergySystemBattery"
                                        data-id="{{$battarySystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More batteries</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBattery">
                                <tr>
                                    <th>Battery Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="battery_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($batteries as $battery)
                                                <option value="{{$battery->id}}">
                                                    {{$battery->battery_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="battery_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBatteryButton" 
                                            class="btn btn-outline-primary">
                                            Add Battery Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New batteries</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBattery">
                                <tr>
                                    <th>Battery Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="battery_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($batteries as $battery)
                                                <option value="{{$battery->id}}">
                                                    {{$battery->battery_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="battery_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBatteryButton" 
                                            class="btn btn-outline-primary">
                                            Add Battery Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>battery Mounts</h6>
                </div>
                @if(count($battaryMountSystems) > 0)

                    <table id="energySystemBatteryMountTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($battaryMountSystems as $battaryMountSystem)
                            <tr id="battaryMountSystemsRow">
                                <td class="text-center">
                                    {{$battaryMountSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$battaryMountSystem->unit}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemBatteryMount" 
                                        id="deleteEnergySystemBatteryMount"
                                        data-id="{{$battaryMountSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More battery Mount</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBatteryMount">
                                <tr>
                                    <th>Battery Mount Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="battery_mount_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($batteryMounts as $batteryMount)
                                                <option value="{{$batteryMount->id}}">
                                                    {{$batteryMount->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBatteryMountButton" 
                                            class="btn btn-outline-primary">
                                            Add Battery Mount Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New battery Mounts</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBatteryMount">
                                <tr>
                                    <th>Battery Mount Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="battery_mount_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($batteryMounts as $batteryMount)
                                                <option value="{{$batteryMount->id}}">
                                                    {{$batteryMount->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBatteryMountButton" 
                                            class="btn btn-outline-primary">
                                            Add Battery Mount Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Solar Panels</h6>
                </div>
                @if(count($pvSystems) > 0)

                    <table id="energySystemPvTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($pvSystems as $pvSystem)
                            <tr id="pvSystemsRow">
                                <td class="text-center">
                                    {{$pvSystem->pv_model}}
                                </td>
                                <td class="text-center">
                                    {{$pvSystem->pv_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemPv" 
                                        id="deleteEnergySystemPv"
                                        data-id="{{$pvSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Solar Panels</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemovePv">
                                <tr>
                                    <th>PV Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="pv_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($pvs as $pv)
                                                <option value="{{$pv->id}}">
                                                    {{$pv->pv_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="pv_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemovePvButton" 
                                            class="btn btn-outline-primary">
                                            Add PV Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Solar Panels</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemovePv">
                                <tr>
                                    <th>PV Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="pv_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($pvs as $pv)
                                                <option value="{{$pv->id}}">
                                                    {{$pv->pv_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="pv_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemovePvButton" 
                                            class="btn btn-outline-primary">
                                            Add PV Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Solar Panel Mounts</h6>
                </div>
                @if(count($pvMountSystems) > 0)

                    <table id="energySystemPvMountTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($pvMountSystems as $pvMountSystem)
                            <tr id="pvMountSystemsRow">
                                <td class="text-center">
                                    {{$pvMountSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$pvMountSystem->unit}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemPvMount" 
                                        id="deleteEnergySystemPvMount"
                                        data-id="{{$pvMountSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Solar Panel Mount</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemovePvMount">
                                <tr>
                                    <th>PV Mount Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="pv_mount_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($pvMounts as $pvMount)
                                                <option value="{{$pvMount->id}}">
                                                    {{$pvMount->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemovePvMountButton" 
                                            class="btn btn-outline-primary">
                                            Add PV Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Solar Panel Mounts</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemovePvMount">
                                <tr>
                                    <th>PV Mount Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="pv_mount_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($pvMounts as $pvMount)
                                                <option value="{{$pvMount->id}}">
                                                    {{$pvMount->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemovePvMountButton" 
                                            class="btn btn-outline-primary">
                                            Add PV Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Controllers</h6>
                </div>
                @if(count($controllerSystems) > 0)

                    <table id="energySystemControllerTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($controllerSystems as $controllerSystem)
                            <tr id="controllerSystemsRow">
                                <td class="text-center">
                                    {{$controllerSystem->charge_controller_model}}
                                </td>
                                <td class="text-center">
                                    {{$controllerSystem->controller_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemController" 
                                        id="deleteEnergySystemController"
                                        data-id="{{$controllerSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Controllers</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveController">
                                <tr>
                                    <th>Controller Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="controller_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($controllers as $controller)
                                                <option value="{{$controller->id}}">
                                                    {{$controller->charge_controller_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="controller_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveControllerButton" 
                                            class="btn btn-outline-primary">
                                            Add Controller Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Controllers</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveController">
                                <tr>
                                    <th>Controller Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="controller_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($controllers as $controller)
                                                <option value="{{$controller->id}}">
                                                    {{$controller->charge_controller_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="controller_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveControllerButton" 
                                            class="btn btn-outline-primary">
                                            Add Controller Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif


                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Inverter</h6>
                </div>
                @if(count($inverterSystems) > 0)

                    <table id="energySystemInverterTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($inverterSystems as $inverterSystem)
                            <tr id="inverterSystemRow">
                                <td class="text-center">
                                    {{$inverterSystem->inverter_model}}
                                </td>
                                <td class="text-center">
                                    {{$inverterSystem->inverter_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemInverter" 
                                        id="deleteEnergySystemInverter"
                                        data-id="{{$inverterSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Inverter</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveInverter">
                                <tr>
                                    <th>Inverter Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="inverter_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($inverters as $inverter)
                                                <option value="{{$inverter->id}}">
                                                    {{$inverter->inverter_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="inverter_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveInverterButton" 
                                            class="btn btn-outline-primary">
                                            Add Inverter Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Inverter</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveInverter">
                                <tr>
                                    <th>Inverter Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="inverter_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($inverters as $inverter)
                                                <option value="{{$inverter->id}}">
                                                    {{$inverter->inverter_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="inverter_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveInverterButton" 
                                            class="btn btn-outline-primary">
                                            Add Inverter Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Relay Drivers</h6>
                </div>
                @if(count($relayDriverSystems) > 0)

                    <table id="energySystemRelayDriverTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($relayDriverSystems as $relayDriverSystem)
                            <tr id="relayDriverSystemsRow">
                                <td class="text-center">
                                    {{$relayDriverSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$relayDriverSystem->relay_driver_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemRelayDriver" 
                                        id="deleteEnergySystemRelayDriver"
                                        data-id="{{$relayDriverSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Relay Drivers</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveRelayDriver">
                                <tr>
                                    <th>Relay Driver Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="relay_driver_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($relayDrivers as $relayDriver)
                                                <option value="{{$relayDriver->id}}">
                                                    {{$relayDriver->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="relay_driver_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveRelayDriverButton" 
                                            class="btn btn-outline-primary">
                                            Add Relay Driver Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Relay Drivers</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveRelayDriver">
                                <tr>
                                    <th>Relay Driver Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="relay_driver_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($relayDrivers as $relayDriver)
                                                <option value="{{$relayDriver->id}}">
                                                    {{$relayDriver->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="relay_driver_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveRelayDriverButton" 
                                            class="btn btn-outline-primary">
                                            Add Relay Driver Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif


                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Load Relay</h6>
                </div>
                @if(count($loadRelaySystems) > 0)

                    <table id="energySystemLoadRelayTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($loadRelaySystems as $loadRelaySystem)
                            <tr id="loadRelaySystemsRow">
                                <td class="text-center">
                                    {{$loadRelaySystem->load_relay_model}}
                                </td>
                                <td class="text-center">
                                    {{$loadRelaySystem->load_relay_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemLoadRelay" 
                                        id="deleteEnergySystemLoadRelay"
                                        data-id="{{$loadRelaySystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Load Relay</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveLoadRelay">
                                <tr>
                                    <th>Load Relay Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="load_relay_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($loadRelaies as $loadRelay)
                                                <option value="{{$loadRelay->id}}">
                                                    {{$loadRelay->load_relay_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="load_relay_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveLoadRelayButton" 
                                            class="btn btn-outline-primary">
                                            Add Load Relay Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Load Relay</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveLoadRelay">
                                <tr>
                                    <th>Load Relay Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="load_relay_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($loadRelaies as $loadRelay)
                                                <option value="{{$loadRelay->id}}">
                                                    {{$loadRelay->load_relay_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="load_relay_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveLoadRelayButton" 
                                            class="btn btn-outline-primary">
                                            Add Load Relay Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif


                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Battery Proccessor</h6>
                </div>
                @if(count($bspSystems) > 0)

                    <table id="energySystemBspTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($bspSystems as $bspSystem)
                            <tr id="bspSystemRow">
                                <td class="text-center">
                                    {{$bspSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$bspSystem->bsp_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemBsp" 
                                        id="deleteEnergySystemBsp"
                                        data-id="{{$bspSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Battery Proccessor</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBsp">
                                <tr>
                                    <th>Battery Proccessor Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="bsp_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($bsps as $bsp)
                                                <option value="{{$bsp->id}}">
                                                    {{$bsp->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="bsp_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBspButton" 
                                            class="btn btn-outline-primary">
                                            Add BSP Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Battery Proccessor</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBsp">
                                <tr>
                                    <th>Battery Proccessor Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="bsp_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($bsps as $bsp)
                                                <option value="{{$bsp->id}}">
                                                    {{$bsp->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="bsp_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBspButton" 
                                            class="btn btn-outline-primary">
                                            Add BSP Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
 

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>BTS</h6>
                </div>
                @if(count($btsSystems) > 0)

                    <table id="energySystemBtsTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($btsSystems as $btsSystem)
                            <tr id="btsSystemRow">
                                <td class="text-center">
                                    {{$btsSystem->BTS_model}}
                                </td>
                                <td class="text-center">
                                    {{$btsSystem->bts_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemBts" 
                                        id="deleteEnergySystemBts"
                                        data-id="{{$btsSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More BTS</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBts">
                                <tr>
                                    <th>BTS Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="bts_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($btss as $bts)
                                                <option value="{{$bts->id}}">
                                                    {{$bts->BTS_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="bts_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBtsButton" 
                                            class="btn btn-outline-primary">
                                            Add BTS Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New BTS</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveBts">
                                <tr>
                                    <th>BTS Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="bts_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($btss as $bts)
                                                <option value="{{$bts->id}}">
                                                    {{$bts->BTS_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="bts_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveBtsButton" 
                                            class="btn btn-outline-primary">
                                            Add BTS Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif


                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Control Center</h6>
                </div>
                @if(count($rccSystems) > 0)

                    <table id="energySystemRccTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($rccSystems as $rccSystem)
                            <tr id="rccSystemRow">
                                <td class="text-center">
                                    {{$rccSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$rccSystem->rcc_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemRcc" 
                                        id="deleteEnergySystemRcc"
                                        data-id="{{$rccSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Control Center</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveRcc">
                                <tr>
                                    <th>Control Center Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="rcc_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($rccs as $rcc)
                                                <option value="{{$rcc->id}}">
                                                    {{$rcc->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="rcc_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveRccButton" 
                                            class="btn btn-outline-primary">
                                            Add RCC Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Control Center</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveRcc">
                                <tr>
                                    <th>Control Center Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="rcc_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($rccs as $rcc)
                                                <option value="{{$rcc->id}}">
                                                    {{$rcc->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="rcc_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveRccButton" 
                                            class="btn btn-outline-primary">
                                            Add RCC Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif


                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Logger</h6>
                </div>
                @if(count($loggerSystems) > 0)

                    <table id="energySystemLoggerTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($loggerSystems as $loggerSystem)
                            <tr id="loggerSystemsRow">
                                <td class="text-center">
                                    {{$loggerSystem->monitoring_model}}
                                </td>
                                <td class="text-center">
                                    {{$loggerSystem->monitoring_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemLogger" 
                                        id="deleteEnergySystemLogger"
                                        data-id="{{$loggerSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Logger</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveLogger">
                                <tr>
                                    <th>Logger Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="logger_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($loggers as $logger)
                                                <option value="{{$logger->id}}">
                                                    {{$logger->monitoring_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="logger_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveLoggerButton" 
                                            class="btn btn-outline-primary">
                                            Add Logger Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Logger</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveLogger">
                                <tr>
                                    <th>Logger Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="logger_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($loggers as $logger)
                                                <option value="{{$logger->id}}">
                                                    {{$logger->monitoring_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="logger_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveLoggerButton" 
                                            class="btn btn-outline-primary">
                                            Add Logger Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif


                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Generator</h6>
                </div>
                @if(count($generatorSystems) > 0)

                    <table id="energySystemGeneratorTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($generatorSystems as $generatorSystem)
                            <tr id="generatorSystemsRow">
                                <td class="text-center">
                                    {{$generatorSystem->generator_model}}
                                </td>
                                <td class="text-center">
                                    {{$generatorSystem->generator_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemGenerator" 
                                        id="deleteEnergySystemGenerator"
                                        data-id="{{$generatorSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Generator</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveGenerator">
                                <tr>
                                    <th>Generator Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="generator_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($generators as $generator)
                                                <option value="{{$generator->id}}">
                                                    {{$generator->generator_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="generator_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveGeneratorButton" 
                                            class="btn btn-outline-primary">
                                            Add Generator Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Generator</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveGenerator">
                                <tr>
                                    <th>Generator Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="generator_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($generators as $generator)
                                                <option value="{{$generator->id}}">
                                                    {{$generator->generator_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="generator_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveGeneratorButton" 
                                            class="btn btn-outline-primary">
                                            Add Generator Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Wind Turbine</h6>
                </div>
                @if(count($turbineSystems) > 0)

                    <table id="energySystemTurbineTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($turbineSystems as $turbineSystem)
                            <tr id="turbineSystemsRow">
                                <td class="text-center">
                                    {{$turbineSystem->wind_turbine_model}}
                                </td>
                                <td class="text-center">
                                    {{$turbineSystem->turbine_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemTurbine" 
                                        id="deleteEnergySystemTurbine"
                                        data-id="{{$turbineSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Wind Turbine</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveTurbine">
                                <tr>
                                    <th>Wind Turbine Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="turbine_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($turbines as $turbine)
                                                <option value="{{$turbine->id}}">
                                                    {{$turbine->wind_turbine_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="turbine_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveTurbineButton" 
                                            class="btn btn-outline-primary">
                                            Add Wind Turbine Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Wind Turbine</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveTurbine">
                                <tr>
                                    <th>Wind Turbine Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="turbine_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($turbines as $turbine)
                                                <option value="{{$turbine->id}}">
                                                    {{$turbine->wind_turbine_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="turbine_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveTurbineButton" 
                                            class="btn btn-outline-primary">
                                            Add Wind Turbine Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Solar Panel MCB</h6>
                </div>
                @if(count($pvMcbSystems) > 0)

                    <table id="energySystemPvMcbTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($pvMcbSystems as $pvMcbSystem)
                            <tr id="pvMcbSystemsRow">
                                <td class="text-center">
                                    {{$pvMcbSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$pvMcbSystem->mcb_pv_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemPvMcb" 
                                        id="deleteEnergySystemPvMcb"
                                        data-id="{{$pvMcbSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Solar Panel MCB</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveMcbPv">
                                <tr>
                                    <th>Solar Panel MCB Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="pv_mcb_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($mcbPvs as $mcbPv)
                                                <option value="{{$mcbPv->id}}">
                                                    {{$mcbPv->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="pv_mcb_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveMcbPvButton" 
                                            class="btn btn-outline-primary">
                                            Add PV MCB Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Solar Panel MCB</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveMcbPv">
                                <tr>
                                    <th>Solar Panel MCB Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="pv_mcb_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($mcbPvs as $mcbPv)
                                                <option value="{{$mcbPv->id}}">
                                                    {{$mcbPv->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="pv_mcb_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveMcbPvButton" 
                                            class="btn btn-outline-primary">
                                            Add PV MCB Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Charge Controllers MCB</h6>
                </div>
                @if(count($controllerMcbSystems) > 0)

                    <table id="energySystemMcbControllerTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($controllerMcbSystems as $controllerMcbSystem)
                            <tr id="controllerMcbSystemsRow">
                                <td class="text-center">
                                    {{$controllerMcbSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$controllerMcbSystem->mcb_controller_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemMcbController" 
                                        id="deleteEnergySystemMcbController"
                                        data-id="{{$controllerMcbSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Charge Controllers MCB</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveControllerMcb">
                                <tr>
                                    <th>Charge Controllers MCB Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="controller_mcb_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($mcbControllers as $mcbController)
                                                <option value="{{$mcbController->id}}">
                                                    {{$mcbController->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="controller_mcb_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveControllerMcbButton" 
                                            class="btn btn-outline-primary">
                                            Add Charge Controllers MCB Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Charge Controllers MCB</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveControllerMcb">
                                <tr>
                                    <th>Charge Controllers MCB Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="controller_mcb_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($mcbControllers as $mcbController)
                                                <option value="{{$mcbController->id}}">
                                                    {{$mcbController->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="controller_mcb_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveControllerMcbButton" 
                                            class="btn btn-outline-primary">
                                            Add Charge Controllers MCB Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Inverter MCB</h6>
                </div>
                @if(count($inventerMcbSystems) > 0)

                    <table id="energySystemMcbInventerTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($inventerMcbSystems as $inventerMcbSystem)
                            <tr id="inventerMcbSystemsRow">
                                <td class="text-center">
                                    {{$inventerMcbSystem->inverter_MCB_model}}
                                </td>
                                <td class="text-center">
                                    {{$inventerMcbSystem->mcb_inverter_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemMcbInventer" 
                                        id="deleteEnergySystemMcbInventer"
                                        data-id="{{$inventerMcbSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Inverter MCB</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveInverterMcb">
                                <tr>
                                    <th>Inverter MCB Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="inventer_mcb_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($mcbInventors as $mcbInventor)
                                                <option value="{{$mcbInventor->id}}">
                                                    {{$mcbInventor->inverter_MCB_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="inventer_mcb_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveInverterMcbButton" 
                                            class="btn btn-outline-primary">
                                            Add Inverter MCB Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Inverter MCB</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveInverterMcb">
                                <tr>
                                    <th>Inverter MCB Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="inventer_mcb_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($mcbInventors as $mcbInventor)
                                                <option value="{{$mcbInventor->id}}">
                                                    {{$mcbInventor->inverter_MCB_model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="inventer_mcb_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveInverterMcbButton" 
                                            class="btn btn-outline-primary">
                                            Add Inverter MCB Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row">
                    <h6>Air Conditioner</h6>
                </div>
                @if(count($airConditionerSystems) > 0)

                    <table id="energySystemAirConditionerTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($airConditionerSystems as $airConditionerSystem)
                            <tr id="airConditionerSystemsRow">
                                <td class="text-center">
                                    {{$airConditionerSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$airConditionerSystem->energy_air_conditioner_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergySystemAirConditioner" 
                                        id="deleteEnergySystemAirConditioner"
                                        data-id="{{$airConditionerSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Air Conditioner</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveAirConditioner">
                                <tr>
                                    <th>Air Conditioner Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="energy_air_conditioner_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($airConditioners as $airConditioner)
                                                <option value="{{$airConditioner->id}}">
                                                    {{$airConditioner->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="energy_air_conditioner_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveAirConditionerButton" 
                                            class="btn btn-outline-primary">
                                            Add Air Conditioner Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                    
                @else
                    <div class="row">
                        <h6>Add New Air Conditioner</h6>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addRemoveAirConditioner">
                                <tr>
                                    <th>Air Conditioner Models</th>
                                    <th>Units</th>
                                    <th>Options</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="energy_air_conditioner_id[]" class="selectpicker form-control"
                                            multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($airConditioners as $airConditioner)
                                                <option value="{{$airConditioner->id}}">
                                                    {{$airConditioner->model}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="energy_air_conditioner_units[0][subject]" class="form-control"
                                            data-id="0">
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveAirConditionerButton" 
                                            class="btn btn-outline-primary">
                                            Add Air Conditioner Units
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
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

    // delete energy system BTS
    $('#energySystemBtsTable').on('click', '.deleteEnergySystemBts',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system battery
    $('#energySystemBatteryTable').on('click', '.deleteEnergySystemBattery',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system battery mount
    $('#energySystemBatteryMountTable').on('click', '.deleteEnergySystemBatteryMount',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system pv
    $('#energySystemPvTable').on('click', '.deleteEnergySystemPv',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system pv
    $('#energySystemPvMountTable').on('click', '.deleteEnergySystemPvMount',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system controller
    $('#energySystemControllerTable').on('click', '.deleteEnergySystemController',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system MCB PV
    $('#energySystemPvMcbTable').on('click', '.deleteEnergySystemPvMcb',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system BSP
    $('#energySystemBspTable').on('click', '.deleteEnergySystemBsp',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Logger
    $('#energySystemLoggerTable').on('click', '.deleteEnergySystemLogger',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Load Relay
    $('#energySystemLoadRelayTable').on('click', '.deleteEnergySystemLoadRelay',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Inverter
    $('#energySystemInverterTable').on('click', '.deleteEnergySystemInverter',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Generator
    $('#energySystemGeneratorTable').on('click', '.deleteEnergySystemGenerator',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system RCC
    $('#energySystemRccTable').on('click', '.deleteEnergySystemRcc',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Relay Driver
    $('#energySystemRelayDriverTable').on('click', '.deleteEnergySystemRelayDriver',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Turbine
    $('#energySystemTurbineTable').on('click', '.deleteEnergySystemTurbine',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Mcb Controller
    $('#energySystemMcbControllerTable').on('click', '.deleteEnergySystemMcbController',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Mcb Inverter
    $('#energySystemMcbInventerTable').on('click', '.deleteEnergySystemMcbInventer',function() {
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
                    type: 'get',
                    data: {id: id},
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

    // delete energy system Air Conditioner    
    $('#energySystemAirConditionerTable').on('click', '.deleteEnergySystemAirConditioner',function() {    
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
                    type: 'get',
                    data: {id: id},
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

    var battery_counter = 0;
    var battery_mount_counter = 0;
    var pv_counter = 0;
    var pv_mount_counter = 0;
    var controller_counter = 0;
    var inverter_counter = 0;
    var relay_driver_counter = 0;
    var load_relay_counter = 0;
    var bsp_counter = 0;
    var logger_counter = 0;
    var rcc_counter = 0;
    var generator_counter = 0;
    var turbine_counter = 0;
    var inventer_mcb_counter = 0;
    var controller_mcb_counter = 0;
    var pv_mcb_counter = 0;
    var air_counter = 0;
    var bts_counter = 0;

    // Battery
    $(document).on('click', '#addRemoveBatteryButton', function () {

        ++battery_counter;
        $("#addRemoveBattery").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ battery_counter +'" name="battery_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeBattery">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeBattery', function () {
        $(this).parents('tr').remove();
    });


    // Battery Mount
    $(document).on('click', '#addRemoveBatteryMountButton', function () {

        ++battery_mount_counter;
        $("#addRemoveBatteryMount").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ battery_mount_counter +'" name="units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeBatteryMount">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeBatteryMount', function () {
        $(this).parents('tr').remove();
    });

    // PV
    $(document).on('click', '#addRemovePvButton', function () {

        ++pv_counter;
        $("#addRemovePv").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ pv_counter +'"' +
            'name="pv_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePv">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePv', function () {
        $(this).parents('tr').remove();
    });

    // PV Mount
    $(document).on('click', '#addRemovePvMountButton', function () {

        ++pv_mount_counter;
        $("#addRemovePvMount").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ pv_mount_counter +'"' +
            'name="units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePvMount">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePvMount', function () {
        $(this).parents('tr').remove();
    });
   
    // Controllers
    $(document).on('click', '#addRemoveControllerButton', function () {

        ++controller_counter;
        $("#addRemoveController").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ controller_counter +'"' +
            'name="controller_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeController">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeController', function () {
        $(this).parents('tr').remove();
    });
    
    // Inverter
    $(document).on('click', '#addRemoveInverterButton', function () {

        ++inverter_counter;
        $("#addRemoveInverter").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ inverter_counter +'"' +
            'name="inverter_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeInverter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeInverter', function () {
        $(this).parents('tr').remove();
    });
    
    // Relay Driver
    $(document).on('click', '#addRemoveRelayDriverButton', function () {

        ++relay_driver_counter;
        $("#addRemoveRelayDriver").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ relay_driver_counter +'"' +
            'name="relay_driver_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeRelayDriver">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRelayDriver', function () {
        $(this).parents('tr').remove();
    });
    
    // Load Relay
    $(document).on('click', '#addRemoveLoadRelayButton', function () {

        ++load_relay_counter;
        $("#addRemoveLoadRelay").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ load_relay_counter +'"' +
            'name="load_relay_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeLoadRelay">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeLoadRelay', function () {
        $(this).parents('tr').remove();
    });


    // RCC
    $(document).on('click', '#addRemoveRccButton', function () {

        ++rcc_counter;
        $("#addRemoveRcc").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ rcc_counter +'"' +
            'name="rcc_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeRcc">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRcc', function () {
        $(this).parents('tr').remove();
    });

    // BSP
    $(document).on('click', '#addRemoveBspButton', function () {

        ++bsp_counter;
        $("#addRemoveBsp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ bsp_counter +'"' +
            'name="bsp_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeBsp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeBsp', function () {
        $(this).parents('tr').remove();
    });
    
    // Logger
    $(document).on('click', '#addRemoveLoggerButton', function () {

        ++logger_counter;
        $("#addRemoveLogger").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ logger_counter +'"' +
            'name="logger_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeLogger">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeLogger', function () {
        $(this).parents('tr').remove();
    });

    // Generator
    $(document).on('click', '#addRemoveGeneratorButton', function () {

        ++generator_counter;
        $("#addRemoveGenerator").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ generator_counter +'"' +
            'name="generator_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeGenerator">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeGenerator', function () {
        $(this).parents('tr').remove();
    });

    // Turbine
    $(document).on('click', '#addRemoveTurbineButton', function () {

        ++turbine_counter;
        $("#addRemoveTurbine").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ turbine_counter +'"' +
            'name="turbine_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeTurbine">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeTurbine', function () {
        $(this).parents('tr').remove();
    });

    // Controllers MCB
    $(document).on('click', '#addRemoveControllerMcbButton', function () {

        ++controller_mcb_counter;
        $("#addRemoveControllerMcb").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ controller_mcb_counter +'"' +
            'name="controller_mcb_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeMcbController">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeMcbController', function () {
        $(this).parents('tr').remove();
    });

    // PV MCB
    $(document).on('click', '#addRemoveMcbPvButton', function () {

        ++pv_mcb_counter;
        $("#addRemoveMcbPv").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ pv_mcb_counter +'"' +
            'name="pv_mcb_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeMcbPv">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeMcbPv', function () {
        $(this).parents('tr').remove();
    });

    // Inverter MCB
    $(document).on('click', '#addRemoveInverterMcbButton', function () {

        ++inventer_mcb_counter;
        $("#addRemoveInverterMcb").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ inventer_mcb_counter +'"' +
            'name="inventer_mcb_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeMcbInverter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeMcbInverter', function () {
        $(this).parents('tr').remove();
    });

    // Air Conditioner
    $(document).on('click', '#addRemoveAirConditionerButton', function () {

        ++air_counter;
        $("#addRemoveAirConditioner").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ air_counter +'" name="energy_air_conditioner_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeAirConditioner">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAirConditioner', function () {
        $(this).parents('tr').remove();
    });

    // BTS
    $(document).on('click', '#addRemoveBtsButton', function () {

        ++bts_counter;
        $("#addRemoveBts").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ bts_counter +'" name="bts_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeBts">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeBts', function () {
        $(this).parents('tr').remove();
    });

</script>

@endsection