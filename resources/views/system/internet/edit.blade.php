@extends('layouts/layoutMaster')

@section('title', 'edit internet system')

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
    <span class="text-muted fw-light">Edit </span> {{$internetSystem->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('internet-system.update', $internetSystem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="system_name" 
                            class="form-control" value="{{$internetSystem->system_name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Start Year</label>
                            <input type="number" name="start_year" 
                            class="form-control" value="{{$internetSystem->start_year}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="1">
                                {{$internetSystem->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <div class="row" style="margin-top:10px">
                    <span>Internet System Types</span>
                </div>
                @if(count($internetSystemTypes) > 0)

                    <table id="internetSystemTypesTable" class="table table-striped 
                        data-table-internet-system-type my-2">
                        
                        <tbody>
                            @foreach($internetSystemTypes as $internetSystemTypes)
                            <tr id="internetSystemTypesRow">
                                <td class="text-center">
                                    {{$internetSystemTypes->InternetSystemType->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemType" id="deleteInternetSystemType"
                                        data-id="{{$internetSystemTypes->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add System Types</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_internet_types[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($internetTypes as $internetType)
                                        <option value="{{$internetType->id}}">
                                            {{$internetType->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    
                @else
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add System Types</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_internet_types[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($internetTypes as $internetType)
                                        <option value="{{$internetType->id}}">
                                            {{$internetType->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>Routers</h6>
                </div>
                @if(count($routerSystems) > 0)

                    <table id="internetSystemRouterTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($routerSystems as $routerSystem)
                            <tr id="routerSystemsRow">
                                <td class="text-center">
                                    {{$routerSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$routerSystem->router_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemRouter" 
                                        id="deleteInternetSystemRouter"
                                        data-id="{{$routerSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Routers</span>
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
                    
                @else
                    <div class="row">
                        <h6>Add New Routers</h6>
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
                @endif

                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>Switches</h6>
                </div>
                @if(count($switcheSystems) > 0)

                    <table id="internetSystemSwitchTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($switcheSystems as $switcheSystem)
                            <tr id="switcheSystemsRow">
                                <td class="text-center">
                                    {{$switcheSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$switcheSystem->switch_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemSwitch" 
                                        id="deleteInternetSystemSwitch"
                                        data-id="{{$switcheSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More Switches</span>
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
                    
                @else
                    <div class="row">
                        <h6>Add New Switches</h6>
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
                @endif


                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>Controllers</h6>
                </div>
                @if(count($controllerSystems) > 0)

                    <table id="internetSystemControllerTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($controllerSystems as $controllerSystem)
                            <tr id="controllerSystemsRow">
                                <td class="text-center">
                                    {{$controllerSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$controllerSystem->controller_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemController" 
                                        id="deleteInternetSystemController"
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
                @endif


                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>APs</h6>
                </div>
                @if(count($apSystems) > 0)

                    <table id="internetSystemApTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($apSystems as $apSystem)
                            <tr id="apSystemsRow">
                                <td class="text-center">
                                    {{$apSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$apSystem->ap_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemAp" 
                                        id="deleteInternetSystemAp"
                                        data-id="{{$apSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More APs</span>
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
                    
                @else
                    <div class="row">
                        <h6>Add New APs</h6>
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
                @endif

                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>AP Lite</h6>
                </div>
                @if(count($apLiteSystems) > 0)

                    <table id="internetSystemApLiteTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($apLiteSystems as $apLiteSystem)
                            <tr id="apLiteSystemsRow">
                                <td class="text-center">
                                    {{$apLiteSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$apLiteSystem->ap_lite_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemApLite" 
                                        id="deleteInternetSystemApLite"
                                        data-id="{{$apLiteSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More AP Lite</span>
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
                    
                @else
                    <div class="row">
                        <h6>Add New AP Lite</h6>
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
                @endif


                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>Air Max / PTP</h6>
                </div>
                @if(count($ptpSystems) > 0)

                    <table id="internetSystemPtpTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($ptpSystems as $ptpSystem)
                            <tr id="ptpSystemsRow">
                                <td class="text-center">
                                    {{$ptpSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$ptpSystem->ptp_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemPtp" 
                                        id="deleteInternetSystemPtp"
                                        data-id="{{$ptpSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More PTPs</span>
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
                    
                @else
                    <div class="row">
                        <h6>Add New PTPs</h6>
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
                @endif
                
                <hr style="margin-top:30px">
                <div class="row" >
                    <h6>UISP Air Max</h6>
                </div>
                @if(count($uispSystems) > 0)

                    <table id="internetSystemUispTable" class="table table-striped my-2">
                        <tbody>
                            @foreach($uispSystems as $uispSystem)
                            <tr id="uispSystemsRow">
                                <td class="text-center">
                                    {{$uispSystem->model}}
                                </td>
                                <td class="text-center">
                                    {{$uispSystem->uisp_units}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetSystemUisp" 
                                        id="deleteInternetSystemUisp"
                                        data-id="{{$uispSystem->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <span>Add More UISPs</span>
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
                    
                @else
                    <div class="row">
                        <h6>Add New UISPs</h6>
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>

    // delete internet system type
    $('#internetSystemTypesTable').on('click', '.deleteInternetSystemType',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this internet system type?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemType') }}",
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

    // delete internet system router
    $('#internetSystemRouterTable').on('click', '.deleteInternetSystemRouter',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this router?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemRouter') }}",
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

    // delete internet system switch
    $('#internetSystemSwitchTable').on('click', '.deleteInternetSystemSwitch',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this switch?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemSwitch') }}",
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

    // delete internet system controller
    $('#internetSystemControllerTable').on('click', '.deleteInternetSystemController',function() {
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
                    url: "{{ route('deleteInternetSystemController') }}",
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

    // delete internet system ap
    $('#internetSystemApTable').on('click', '.deleteInternetSystemAp',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this ap?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemAp') }}",
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

    // delete internet system ap lite
    $('#internetSystemApLiteTable').on('click', '.deleteInternetSystemApLite',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this ap lite?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemApLite') }}",
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

    // delete internet system ptp
    $('#internetSystemPtpTable').on('click', '.deleteInternetSystemPtp',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this ptp?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemPtp') }}",
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

    // delete internet system uisp
    $('#internetSystemUispTable').on('click', '.deleteInternetSystemUisp',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this uisp?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystemUisp') }}",
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

@endsection