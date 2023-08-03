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

    .dropdown-toggle{
        height: 40px;
        
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Energy System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('energy-system')}}" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" 
                                required>
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('community_id'))
                                <span class="error">{{ $errors->first('community_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" class="selectpicker form-control"
                                data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($energyTypes as $energyType)
                                    <option value="{{$energyType->id}}">
                                        {{$energyType->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" 
                            class="form-control">
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
                    <h6>Battery Status Processor</h6> 
                </div>
              
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveBsp">
                            <tr>
                                <th>Battery Status Processor Models</th>
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
                    <h6>Remote Control Center</h6> 
                </div>
              
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveRcc">
                            <tr>
                                <th>Remote Control Center Models</th>
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

@endsection
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>

    var battery_counter = 0;
    var pv_counter = 0;
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

</script>