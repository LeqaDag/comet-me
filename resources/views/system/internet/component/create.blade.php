@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'internet system')

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
  <span class="text-muted fw-light">Add </span> New Internet Components
</h4>
 
<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('internet-component')}}" enctype="multipart/form-data" >
                @csrf

                <div class="row">
                    <h6>Add New Routers</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewRouter">
                            <tr>
                                <th>Router Models</th>
                                <th>Router Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="router_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="router_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewRouterButton" 
                                        class="btn btn-outline-primary">
                                        Add Router Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h6>Switches</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewSwitch">
                            <tr>
                                <th>Switch Models</th>
                                <th>Switch Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="switch_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="switch_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewSwitchButton" 
                                        class="btn btn-outline-primary">
                                        Add More Switch Units
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
                        <table class="table table-bordered" id="addRemoveNewController">
                            <tr>
                                <th>Controller Models</th>
                                <th>Controller Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="controller_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="controller_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewControllerButton" 
                                        class="btn btn-outline-primary">
                                        Add More Controller Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
               
                <hr>

                <div class="row">
                    <h6>AP</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewAP">
                            <tr>
                                <th>AP Models</th>
                                <th>AP Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="ap_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="ap_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewAPButton" 
                                        class="btn btn-outline-primary">
                                        Add More AP Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>

                <div class="row">
                    <h6>AP Lite</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewAPLite">
                            <tr>
                                <th>AP Lite Models</th>
                                <th>AP Lite Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="apLite_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="apLite_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewAPLiteButton" 
                                        class="btn btn-outline-primary">
                                        Add More AP Lite Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
               
                <hr>

                <div class="row">
                    <h6>Air Max / PTP</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewPtp">
                            <tr>
                                <th>PTP Models</th>
                                <th>PTP Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="ptp_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="ptp_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewPtpButton" 
                                        class="btn btn-outline-primary">
                                        Add More PTP Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
               
                <hr>

                <div class="row">
                    <h6>UISP Air Max</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewUISP">
                            <tr>
                                <th>UISP Models</th>
                                <th>UISP Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="uisp_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="uisp_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewUISPButton" 
                                        class="btn btn-outline-primary">
                                        Add More UISP Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            
                <hr>

                <div class="row">
                    <h6>Electricians</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewElectrician">
                            <tr>
                                <th>Electrician Models</th>
                                <th>Electrician Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="electrician_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="electrician_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewElectricianButton" 
                                        class="btn btn-outline-primary">
                                        Add More Electrician Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            
                <hr>

                <div class="row">
                    <h6>Connectors</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewConnector">
                            <tr>
                                <th>Connector Models</th>
                                <th>Connector Brand</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="connector_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="connector_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewConnectorButton" 
                                        class="btn btn-outline-primary">
                                        Add More Connector Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <h6>Network Cabinets</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewCabinet">
                            <tr>
                                <th>Cabinet Models</th>
                                <th>Cabinet Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="cabinet_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="cabinet_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewCabinetButton" 
                                        class="btn btn-outline-primary">
                                        Add More Cabinet Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <h6>Patch Panel</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewPatchPanel">
                            <tr>
                                <th>Patch Panel Model</th>
                                <th>Patch Panel Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="patchpanel_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="patchpanel_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewPatchPanelButton" 
                                        class="btn btn-outline-primary">
                                        Add More Patch Panel Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <h6>Air Patch Panel</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewAirPatchPanel">
                            <tr>
                                <th>Air Patch Panel Model</th>
                                <th>Air Patch Panel Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="airpatchpanel_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="airpatchpanel_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewAirPatchPanelButton" 
                                        class="btn btn-outline-primary">
                                        Add More Air Patch Panel Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <h6>Camera Shelve</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewCameraShelve">
                            <tr>
                                <th>Camera Shelve Model</th>
                                <th>Camera Shelve Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="camerashelve_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="camerashelve_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewCameraShelveButton" 
                                        class="btn btn-outline-primary">
                                        Add More Camera Shelve Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <h6>Patch Cord</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewPatchCord">
                            <tr>
                                <th>Patch Cord Model</th>
                                <th>Patch Cord Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="patchcord_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="patchcord_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewPatchCordButton" 
                                        class="btn btn-outline-primary">
                                        Add More Patch Cord Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <h6>3M Keystone</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewKeystone">
                            <tr>
                                <th>3M Keystone Model</th>
                                <th>3M Keystone Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="keystone_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="keystone_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewKeystoneButton" 
                                        class="btn btn-outline-primary">
                                        Add More 3M Keystone Units
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <h6>Power Distributor</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveNewPowerDistributor">
                            <tr>
                                <th>Power Distributor Model</th>
                                <th>Power Distributor Brand</th>
                                <th>Options</th>
                            </tr> 
                            <tr>
                                <td>
                                    <input type="text" name="powerdistributor_models[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <input type="text" name="powerdistributor_brands[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveNewPowerDistributorButton" 
                                        class="btn btn-outline-primary">
                                        Add More Power Distributor Units
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

    var router_counter = 0;
    var switch_counter = 0;
    var controller_counter = 0;
    var ap_counter = 0;
    var ap_lite_counter = 0;
    var ptp_counter = 0;
    var uisp_counter = 0;
    var electrician_counter = 0;
    var connector_counter = 0;
    var cabinet_counter = 0;
    var patchpanel_counter = 0;
    var airpatchpanel_counter = 0;
    var camerashelve_counter = 0;
    var patchcord_counter = 0;
    var keystone_counter = 0;
    var powerdistributor_counter = 0;

    // Routers
    $(document).on('click', '#addRemoveNewRouterButton', function () {

        ++router_counter;
        $("#addRemoveNewRouter").append('<tr><td><input class="form-control data-id="'+ router_counter +'" name="router_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ router_counter +'" name="router_brands[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeRouter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRouter', function () {
        $(this).parents('tr').remove();
    });

    // Switchs
    $(document).on('click', '#addRemoveNewSwitchButton', function () {

        ++switch_counter;
        $("#addRemoveNewSwitch").append('<tr><td><input class="form-control data-id="'+ switch_counter +'" name="switch_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ switch_counter +'"' +
            'name="switch_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeSwitch">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeSwitch', function () {
        $(this).parents('tr').remove();
    });
   
    // Controllers
    $(document).on('click', '#addRemoveNewControllerButton', function () {

        ++controller_counter;
        $("#addRemoveNewController").append('<tr><td><input class="form-control data-id="'+ controller_counter +'" name="controller_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ controller_counter +'"' +
            'name="controller_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeController">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeController', function () {
        $(this).parents('tr').remove();
    });
    
    // AP
    $(document).on('click', '#addRemoveNewAPButton', function () {

        ++ap_counter;
        $("#addRemoveNewAP").append('<tr><td><input class="form-control data-id="'+ ap_counter +'" name="ap_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ ap_counter +'"' +
            'name="ap_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAp', function () {
        $(this).parents('tr').remove();
    });
    
    // AP Lite
    $(document).on('click', '#addRemoveNewAPLiteButton', function () {

        ++ap_lite_counter;
        $("#addRemoveNewAPLite").append('<tr><td><input class="form-control data-id="'+ ap_lite_counter +'"name="apLite_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ ap_lite_counter +'"' +
            'name="apLite_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAp', function () {
        $(this).parents('tr').remove();
    });
    
    // PTP
    $(document).on('click', '#addRemoveNewPtpButton', function () {

        ++ptp_counter;
        $("#addRemoveNewPtp").append('<tr><td><input class="form-control data-id="'+ ptp_counter +'" name="ptp_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ ptp_counter +'"' +
            'name="ptp_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePtp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePtp', function () {
        $(this).parents('tr').remove();
    });
    
    // UISP
    $(document).on('click', '#addRemoveNewUISPButton', function () {

        ++uisp_counter;
        $("#addRemoveNewUISP").append('<tr><td><input class="form-control data-id="'+ uisp_counter +'" name="uisp_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ uisp_counter +'"' +
            'name="uisp_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeUisp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeUisp', function () {
        $(this).parents('tr').remove();
    });
    
    // Electrician
    $(document).on('click', '#addRemoveNewElectricianButton', function () {

        ++electrician_counter;
        $("#addRemoveNewElectrician").append('<tr><td><input class="form-control data-id="'+ electrician_counter +'" name="electrician_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ electrician_counter +'"' +
            'name="electrician_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeElectrician">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeElectrician', function () {
        $(this).parents('tr').remove();
    });
    
    // Connectors
    $(document).on('click', '#addRemoveNewConnectorButton', function () {

        ++connector_counter;
        $("#addRemoveNewConnector").append('<tr><td><input class="form-control data-id="'+ connector_counter +'" name="connector_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ connector_counter +'"' +
            'name="connector_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeConnector">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeConnector', function () {
        $(this).parents('tr').remove();
    });
    
    // cabinets
    $(document).on('click', '#addRemoveNewCabinetButton', function () {

        ++cabinet_counter;
        $("#addRemoveNewCabinet").append('<tr><td><input class="form-control data-id="'+ cabinet_counter +'" name="cabinet_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ cabinet_counter +'"' +
            'name="cabinet_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeCabinet">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeCabinet', function () {
        $(this).parents('tr').remove();
    });
    
    // PatchPanel
    $(document).on('click', '#addRemoveNewPatchPanelButton', function () {

        ++patchpanel_counter;
        $("#addRemoveNewPatchPanel").append('<tr><td><input class="form-control data-id="'+ patchpanel_counter +'" name="patchpanel_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ patchpanel_counter +'"' +
            'name="patchpanel_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePatchPanel">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePatchPanel', function () {
        $(this).parents('tr').remove();
    });
    
    // AirPatchPanel
    $(document).on('click', '#addRemoveNewAirPatchPanelButton', function () {

        ++airpatchpanel_counter;
        $("#addRemoveNewAirPatchPanel").append('<tr><td><input class="form-control data-id="'+ airpatchpanel_counter +'" ' +
            'name="airpatchpanel_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ airpatchpanel_counter +'"' +
            'name="airpatchpanel_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAirPatchPanel">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAirPatchPanel', function () {
        $(this).parents('tr').remove();
    });
    
    // CameraShelve
    $(document).on('click', '#addRemoveNewCameraShelveButton', function () {

        ++camerashelve_counter;
        $("#addRemoveNewCameraShelve").append('<tr><td><input class="form-control data-id="'+ camerashelve_counter +'" ' +
            'name="camerashelve_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ camerashelve_counter +'"' +
            'name="camerashelve_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeCameraShelve">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeCameraShelve', function () {
        $(this).parents('tr').remove();
    });
    
    // PatchCord
    $(document).on('click', '#addRemoveNewPatchCordButton', function () {

        ++patchcord_counter;
        $("#addRemoveNewPatchCord").append('<tr><td><input class="form-control data-id="'+ patchcord_counter +'" ' +
            'name="patchcord_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ patchcord_counter +'"' +
            'name="patchcord_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePatchCord">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePatchCord', function () {
        $(this).parents('tr').remove();
    });
    
    // CameraShelve
    $(document).on('click', '#addRemoveNewKeystoneButton', function () {

        ++keystone_counter;
        $("#addRemoveNewKeystone").append('<tr><td><input class="form-control data-id="'+ keystone_counter +'" ' +
            'name="keystone_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ keystone_counter +'"' +
            'name="keystone_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeKeystone">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeKeystone', function () {
        $(this).parents('tr').remove();
    });
    

    // PowerDistributor
    $(document).on('click', '#addRemoveNewPowerDistributorButton', function () {

        ++powerdistributor_counter;
        $("#addRemoveNewPowerDistributor").append('<tr><td><input class="form-control data-id="'+ powerdistributor_counter +'" ' +
            'name="powerdistributor_models[][subject]"></td>' +
            '<td><input class="form-control" data-id="'+ powerdistributor_counter +'"' +
            'name="powerdistributor_brands[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePowerDistributor">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePowerDistributor', function () {
        $(this).parents('tr').remove();
    });
    

</script>
@endsection
