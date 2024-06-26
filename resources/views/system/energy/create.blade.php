@extends('layouts/layoutMaster')

@section('title', 'create energy system')

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
  <span class="text-muted fw-light">Add </span> New Energy System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('energy-system')}}" id="energySystemForm"
                enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communitySelected"
                                >
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" class="selectpicker form-control"
                                data-live-search="true" id="energySystemTypeSelected">
                                <option disabled selected>Choose one...</option>
                                @foreach($energyTypes as $energyType)
                                    <option value="{{$energyType->id}}">
                                        {{$energyType->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="energy_system_type_id_error" style="color: red;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" required
                            class="form-control">
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" required
                            class="form-control">
                        </fieldset> 
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" class="selectpicker form-control"
                                data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                            </textarea>
                        </fieldset>
                    </div>
                </div>
               
                <hr>
                
                <div class="row">
                    <h6>batteries</h6>
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

                <hr>
                
                <div class="row">
                    <h6>battery Mounts</h6>
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

                <hr>

                <div class="row">
                    <h6>Solar Panels</h6> 
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
                <hr>

                <div class="row">
                    <h6>Solar Panel Mounts</h6> 
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
                <hr>

                <div class="row">
                    <h6>Controllers</h6> 
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
                <hr>

                <div class="row">
                    <h6>Inverter</h6> 
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
                <hr>

                <div class="row">
                    <h6>Relay Drivers</h6> 
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
                <hr>

                <div class="row">
                    <h6>Load Relay</h6> 
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
                <hr>

                <div class="row">
                    <h6>Battery Proccessor</h6> 
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
                <hr>

                <div class="row">
                    <h6>Control Center</h6> 
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
                <hr>

                <div class="row">
                    <h6>Logger</h6> 
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
                <hr>

                <div class="row">
                    <h6>Generator</h6> 
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
                <hr>

                <div class="row">
                    <h6>Wind Turbine</h6> 
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
                <hr>

                <div class="row">
                    <h6>Solar Panel MCB</h6> 
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
                <hr>

                <div class="row">
                    <h6>Charge Controllers MCB</h6> 
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
                <hr>

                <div class="row">
                    <h6>Inverter MCB</h6> 
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
                <hr>

                <div class="row">
                    <h6>Air Conditioner</h6> 
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

    $(document).ready(function() {

        $('#energySystemForm').on('submit', function (event) {

            var energyTypeValue = $('#energySystemTypeSelected').val();

            if (energyTypeValue == null) {

                $('#energy_system_type_id_error').html('Please select a type!'); 
                return false;
            } else  if (energyTypeValue != null) {

                $('#energy_system_type_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#energy_system_type_id_error').empty();

            this.submit();
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


</script>
@endsection

