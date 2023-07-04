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

    .dropdown-toggle{
        height: 40px;
        
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Internet System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('internet-system')}}" enctype="multipart/form-data" >
                @csrf

                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
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
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet System Type</label>
                            <select name="internet_system_type_id" class="form-control">
                                <option disabled selected>Choose one...</option>
                                @foreach($internetSystemTypes as $internetSystemType)
                                    <option value="{{$internetSystemType->id}}">
                                        {{$internetSystemType->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="system_name" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Start Year</label>
                            <input type="number" name="start_year" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="1">
                            </textarea>
                        </fieldset>
                    </div>
                </div>
               
                <hr>
                
                <div class="row">
                    <h6>Routers</h6>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveRouter">
                            <tr>
                                <th>Router Models</th>
                                <th>Units</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="router_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($routers as $router)
                                            <option value="{{$router->id}}">
                                                {{$router->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="router_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveRouterButton" 
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
                        <table class="table table-bordered" id="addRemoveSwitch">
                            <tr>
                                <th>Switch Models</th>
                                <th>Units</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="switch_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($switches as $switch)
                                            <option value="{{$switch->id}}">
                                                {{$switch->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="switch_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveSwitchButton" 
                                        class="btn btn-outline-primary">
                                        Add Switch Units
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
                                                {{$controller->model}}
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
                    <h6>AP</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="addRemoveAp">
                            <tr>
                                <th>AP Models</th>
                                <th>Units</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="ap_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($aps as $ap)
                                            <option value="{{$ap->id}}">
                                                {{$ap->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="ap_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveApButton" 
                                        class="btn btn-outline-primary">
                                        Add AP Units
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
                        <table class="table table-bordered" id="addRemoveApLite">
                            <tr>
                                <th>AP Lite Models</th>
                                <th>Units</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="ap_lite_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($aps as $ap)
                                            <option value="{{$ap->id}}">
                                                {{$ap->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="ap_lite_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveApLiteButton" 
                                        class="btn btn-outline-primary">
                                        Add AP Lite Units
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
                        <table class="table table-bordered" id="addRemovePtp">
                            <tr>
                                <th>PTP Models</th>
                                <th>Units</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="ptp_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($ptps as $ptp)
                                            <option value="{{$ptp->id}}">
                                                {{$ptp->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="ptp_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemovePtpButton" 
                                        class="btn btn-outline-primary">
                                        Add PTP Units
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
                        <table class="table table-bordered" id="addRemoveUisp">
                            <tr>
                                <th>UISP Models</th>
                                <th>Units</th>
                                <th>Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <select name="uisp_id[]" class="selectpicker form-control"
                                        multiple data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($uisps as $uisp)
                                            <option value="{{$uisp->id}}">
                                                {{$uisp->model}}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="uisp_units[0][subject]" class="form-control"
                                        data-id="0">
                                </td>
                                <td>
                                    <button type="button" name="add" id="addRemoveUispButton" 
                                        class="btn btn-outline-primary">
                                        Add UISP Units
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

    var router_counter = 0;
    var switch_counter = 0;
    var controller_counter = 0;
    var ap_counter = 0;
    var ap_lite_counter = 0;
    var ptp_counter = 0;
    var uisp_counter = 0;

    // Routers
    $(document).on('click', '#addRemoveRouterButton', function () {

        ++router_counter;
        $("#addRemoveRouter").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ router_counter +'" name="router_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeRouter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRouter', function () {
        $(this).parents('tr').remove();
    });

    // Switchs
    $(document).on('click', '#addRemoveSwitchButton', function () {

        ++switch_counter;
        $("#addRemoveSwitch").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ switch_counter +'"' +
            'name="switch_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeSwitch">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeSwitch', function () {
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
    
    // AP
    $(document).on('click', '#addRemoveApButton', function () {

        ++ap_counter;
        $("#addRemoveAp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ ap_counter +'"' +
            'name="ap_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAp', function () {
        $(this).parents('tr').remove();
    });
    
    // AP Lite
    $(document).on('click', '#addRemoveApLiteButton', function () {

        ++ap_lite_counter;
        $("#addRemoveApLite").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ ap_lite_counter +'"' +
            'name="ap_lite_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAp', function () {
        $(this).parents('tr').remove();
    });
    
    // PTP
    $(document).on('click', '#addRemovePtpButton', function () {

        ++ptp_counter;
        $("#addRemovePtp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ ptp_counter +'"' +
            'name="ptp_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePtp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePtp', function () {
        $(this).parents('tr').remove();
    });
    
    // UISP
    $(document).on('click', '#addRemoveUispButton', function () {

        ++uisp_counter;
        $("#addRemoveUisp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ uisp_counter +'"' +
            'name="uisp_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeUisp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeUisp', function () {
        $(this).parents('tr').remove();
    });

</script>