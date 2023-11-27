@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'energy system')

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
  <span class="text-muted fw-light">Add </span> New Energy Components
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('energy-component')}}" enctype="multipart/form-data" >
                @csrf

                <div class="row">
                    <h6>Batteries</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewBattery">
                            <tr>
                                <th>Battery Model</th>
                                <th>Battery Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="battery_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="battery_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewBatteryButton" 
                                        class="btn btn-outline-primary">
                                        Add More Battery
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
                        <table class="table table-bordered" id="addRemoveNewSolarPanel">
                            <tr>
                                <th>Solar Panel Model</th>
                                <th>Solar Panel Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="pv_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="pv_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewSolarPanelButton" 
                                        class="btn btn-outline-primary">
                                        Add More Solar Panel
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
              
                <hr>

                <div class="row">
                    <h6>Charge Controllers</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewChargeController">
                            <tr>
                                <th>Charge Controller Model</th>
                                <th>Charge Controller Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="charge_controller_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="charge_controller_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewChargeControllerButton" 
                                        class="btn btn-outline-primary">
                                        Add More Charge Controller
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
                        <table class="table table-bordered" id="addRemoveNewInverter">
                            <tr>
                                <th>Inverter Model</th>
                                <th>Inverter Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="inverter_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="inverter_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewInverterButton" 
                                        class="btn btn-outline-primary">
                                        Add More Inverter 
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
                        <table class="table table-bordered" id="addRemoveNewRelayDriver">
                            <tr>
                                <th>Relay Driver Model</th>
                                <th>Relay Driver Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="relay_driver_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="relay_driver_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewRelayDriverButton" 
                                        class="btn btn-outline-primary">
                                        Add More Relay Drivers
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
                        <table class="table table-bordered" id="addRemoveNewLoadRelay">
                            <tr>
                                <th>Load Relay Model</th>
                                <th>Load Relay Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="load_relay_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="load_relay_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewLoadRelayButton" 
                                        class="btn btn-outline-primary">
                                        Add More Load Relay
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
               
                <hr>

                <div class="row">
                    <h6>Battery Status Processor </h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewBsp">
                            <tr>
                                <th>BSP Model</th>
                                <th>BSP Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="bsp_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="bsp_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewBspButton" 
                                        class="btn btn-outline-primary">
                                        Add More BSP
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
                        <table class="table table-bordered" id="addRemoveNewRemoteControlCenter">
                            <tr>
                                <th>Remote Control Center Model</th>
                                <th>Remote Control Center Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="rcc_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="rcc_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewRemoteControlCenterButton" 
                                        class="btn btn-outline-primary">
                                        Add More Remote Control Center
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
                        <table class="table table-bordered" id="addRemoveNewLogger">
                            <tr>
                                <th>Logger Model</th>
                                <th>Logger Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="logger_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="logger_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewLoggerButton" 
                                        class="btn btn-outline-primary">
                                        Add More Logger
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
                        <table class="table table-bordered" id="addRemoveNewGenerator">
                            <tr>
                                <th>Generator Model</th>
                                <th>Generator Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="generator_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="generator_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewGeneratorButton" 
                                        class="btn btn-outline-primary">
                                        Add More Generator
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
                        <table class="table table-bordered" id="addRemoveNewTurbine">
                            <tr>
                                <th>Wind Turbine Model</th>
                                <th>Wind Turbine Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="turbine_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="turbine_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewTurbineButton" 
                                        class="btn btn-outline-primary">
                                        Add More Wind Turbine
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
                        <table class="table table-bordered" id="addRemoveNewSolarPanelMcb">
                            <tr>
                                <th>Solar Panel MCB Model</th>
                                <th>Solar Panel MCB Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="pv_mcb_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="pv_mcb_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewSolarPanelMcbButton" 
                                        class="btn btn-outline-primary">
                                        Add More Solar Panel MCB
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
                        <table class="table table-bordered" id="addRemoveNewChargeControllerMcb">
                            <tr>
                                <th>Charge Controller MCB Model</th>
                                <th>Charge Controller MCB Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="charge_controller_mcb_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="charge_controller_mcb_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewChargeControllerMcbButton" 
                                        class="btn btn-outline-primary">
                                        Add More Charge Controller MCB
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
                        <table class="table table-bordered" id="addRemoveNewInverterMcb">
                            <tr>
                                <th>Inverter MCB Model</th>
                                <th>Inverter MCB Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="inverter_mcb_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="inverter_mcb_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewInverterMcbButton" 
                                        class="btn btn-outline-primary">
                                        Add More Inverter MCB
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
    $(document).on('click', '#addRemoveNewBatteryButton', function () {

        ++battery_counter;
        $("#addRemoveNewBattery").append('<tr><td><input class="form-control data-id="'+ battery_counter +'" name="battery_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ battery_counter +'" name="battery_brands[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeBattery">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeBattery', function () {
        $(this).parents('tr').remove();
    });

    // Solar Panel
    $(document).on('click', '#addRemoveNewSolarPanelButton', function () {

        ++pv_counter;
        $("#addRemoveNewSolarPanel").append('<tr><td><input class="form-control data-id="'+ pv_counter +'" name="pv_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ pv_counter +'"' +
            'name="pv_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeSolarPanel">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeSolarPanel', function () {
        $(this).parents('tr').remove();
    });
   
    // Controllers
    $(document).on('click', '#addRemoveNewChargeControllerButton', function () {

        ++controller_counter;
        $("#addRemoveNewChargeController").append('<tr><td><input class="form-control data-id="'+ controller_counter +'" name="charge_controller_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ controller_counter +'"' +
            'name="charge_controller_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeController">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeController', function () {
        $(this).parents('tr').remove();
    });
    
    // Inverter
    $(document).on('click', '#addRemoveNewInverterButton', function () {

        ++inverter_counter;
        $("#addRemoveNewInverter").append('<tr><td><input class="form-control data-id="'+ inverter_counter +'" name="inverter_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ inverter_counter +'"' +
            'name="inverter_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeInverter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeInverter', function () {
        $(this).parents('tr').remove();
    });
    
    // Realy Driver
    $(document).on('click', '#addRemoveNewRelayDriverButton', function () {

        ++relay_driver_counter;
        $("#addRemoveNewRelayDriver").append('<tr><td><input class="form-control data-id="'+ relay_driver_counter +'"name="relay_driver_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ relay_driver_counter +'"' +
            'name="relay_driver_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeRelayDriver">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRelayDriver', function () {
        $(this).parents('tr').remove();
    });
    
    // Load Relay
    $(document).on('click', '#addRemoveNewLoadRelayButton', function () {

        ++load_relay_counter;
        $("#addRemoveNewLoadRelay").append('<tr><td><input class="form-control data-id="'+ load_relay_counter +'" name="load_relay_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ load_relay_counter +'"' +
            'name="load_relay_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeLoadRelay">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeLoadRelay', function () {
        $(this).parents('tr').remove();
    });
    
    // BSP
    $(document).on('click', '#addRemoveNewBspButton', function () {

        ++bsp_counter;
        $("#addRemoveNewBsp").append('<tr><td><input class="form-control data-id="'+ bsp_counter +'" name="bsp_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ bsp_counter +'"' +
            'name="bsp_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeBsp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeBsp', function () {
        $(this).parents('tr').remove();
    });

    // RCC
    $(document).on('click', '#addRemoveNewRemoteControlCenterButton', function () {

        ++rcc_counter;
        $("#addRemoveNewRemoteControlCenter").append('<tr><td><input class="form-control data-id="'+ rcc_counter +'" name="rcc_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ rcc_counter +'"' +
            'name="rcc_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeRcc">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRcc', function () {
        $(this).parents('tr').remove();
    });

    // Logger
    $(document).on('click', '#addRemoveNewLoggerButton', function () {

        ++logger_counter;
        $("#addRemoveNewLogger").append('<tr><td><input class="form-control data-id="'+ logger_counter +'" name="logger_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ logger_counter +'"' +
            'name="logger_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeLogger">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeLogger', function () {
        $(this).parents('tr').remove();
    });

    // Generator
    $(document).on('click', '#addRemoveNewGeneratorButton', function () {

        ++generator_counter;
        $("#addRemoveNewGenerator").append('<tr><td><input class="form-control data-id="'+ generator_counter +'" name="generator_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ generator_counter +'"' +
            'name="generator_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeGenerator">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeGenerator', function () {
        $(this).parents('tr').remove();
    });

    // Wind Turbine
    $(document).on('click', '#addRemoveNewTurbineButton', function () {

        ++turbine_counter;
        $("#addRemoveNewTurbine").append('<tr><td><input class="form-control data-id="'+ turbine_counter +'" name="turbine_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ turbine_counter +'"' +
            'name="turbine_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeTurbine">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeTurbine', function () {
        $(this).parents('tr').remove();
    });

    // Controllers MCB
    $(document).on('click', '#addRemoveNewChargeControllerMcbButton', function () {

        ++controller_mcb_counter;
        $("#addRemoveNewChargeControllerMcb").append('<tr><td><input class="form-control data-id="'+ controller_mcb_counter +'" name="charge_controller_mcb_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ controller_mcb_counter +'"' +
            'name="charge_controller_mcb_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeControllerMcb">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeControllerMcb', function () {
        $(this).parents('tr').remove();
    });

    // Inverter MCB
    $(document).on('click', '#addRemoveNewInverterMcbButton', function () {

        ++inventer_mcb_counter;
        $("#addRemoveNewInverterMcb").append('<tr><td><input class="form-control data-id="'+ inventer_mcb_counter +'" name="inverter_mcb_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ inventer_mcb_counter +'"' +
            'name="inverter_mcb_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeInverterMcb">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeInverterMcb', function () {
        $(this).parents('tr').remove();
    });

    // PV MCB
    $(document).on('click', '#addRemoveNewSolarPanelMcbButton', function () {

        ++pv_mcb_counter;
        $("#addRemoveNewSolarPanelMcb").append('<tr><td><input class="form-control data-id="'+ pv_mcb_counter +'" name="pv_mcb_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ pv_mcb_counter +'"' +
            'name="pv_mcb_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePvMcb">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePvMcb', function () {
        $(this).parents('tr').remove();
    });

</script>