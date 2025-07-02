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

</style>

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


                <hr class="mt-4">
                <h5>Routers</h5>

                @if(count($routerSystems) > 0)
                    <table class="table table-striped my-2" id="routerTable">
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
                            @foreach($routerSystems as $index => $router)
                                <tr data-router-id="{{ $router->id }}">
                                    <td class="text-center">{{ $router->model }}</td>
                                    <td>
                                        <input type="number" name="router_units[{{ $router->id }}]" class="form-control router-units" 
                                        data-router-index="{{ $index }}" value="{{ $router->router_units }}">
                                    </td>
                                    <td>
                                        <input type="number" name="router_costs[{{ $router->id }}]" class="form-control router-costs" 
                                        data-router-index="{{ $index }}" value="{{ $router->router_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-router-{{ $index }}">{{ $router->router_units * $router->router_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteRouter" data-id="{{ $router->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Routers --}}
                <h6>Add New Routers</h6>
                <table class="table table-bordered" id="addRemoveRouter">
                    <thead>
                        <tr>
                            <th>Router Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="router_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($routers as $router)
                                        <option value="{{ $router->id }}">{{ $router->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="router_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="router_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveRouterButton">Add Router</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Switches</h5>

                @if(count($switchSystems) > 0)
                    <table class="table table-striped my-2" id="switchTable">
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
                            @foreach($switchSystems as $index => $switch)
                                <tr data-switch-id="{{ $switch->id }}">
                                    <td class="text-center">{{ $switch->model }}</td>
                                    <td>
                                        <input type="number" name="switch_units[{{ $switch->id }}]" class="form-control switch-units" 
                                        data-switch-index="{{ $index }}" value="{{ $switch->switch_units }}">
                                    </td>
                                    <td>
                                        <input type="number" name="switch_costs[{{ $switch->id }}]" class="form-control switch-costs" 
                                        data-switch-index="{{ $index }}" value="{{ $switch->switch_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-switch-{{ $index }}">{{ $switch->switch_units * $switch->switch_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteSwitch" data-id="{{ $switch->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Switchs --}}
                <h6>Add New Switchs</h6>
                <table class="table table-bordered" id="addRemoveSwitch">
                    <thead>
                        <tr>
                            <th>Switch Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="switch_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($switchs as $switch)
                                        <option value="{{ $switch->id }}">{{ $switch->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="switch_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="switch_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveSwitchButton">Add Switch</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Controllers</h5>

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
                                    <td class="text-center">{{ $controller->model }}</td>
                                    <td>
                                        <input type="number" name="controller_units[{{ $controller->id }}]" class="form-control controller-units" 
                                        data-controller-index="{{ $index }}" value="{{ $controller->controller_units }}">
                                    </td>
                                    <td>
                                        <input type="number" name="controller_costs[{{ $controller->id }}]" class="form-control controller-costs" 
                                        data-controller-index="{{ $index }}" value="{{ $controller->controller_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-controller-{{ $index }}">{{ $controller->controller_units * $controller->controller_costs }}</span>
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
                                        <option value="{{ $controller->id }}">{{ $controller->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="controller_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="controller_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveControllerButton">Add Controller</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>APs</h5>

                @if(count($apSystems) > 0)
                    <table class="table table-striped my-2" id="apTable">
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
                            @foreach($apSystems as $index => $ap)
                                <tr data-ap-id="{{ $ap->id }}">
                                    <td class="text-center">{{ $ap->model }}</td>
                                    <td>
                                        <input type="number" name="ap_units[{{ $ap->id }}]" class="form-control ap-units" 
                                        data-ap-index="{{ $index }}" value="{{ $ap->ap_units }}">
                                    </td>
                                    <td>
                                        <input type="number" name="ap_costs[{{ $ap->id }}]" class="form-control ap-costs" 
                                        data-ap-index="{{ $index }}" value="{{ $ap->ap_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-ap-{{ $index }}">{{ $ap->ap_units * $ap->ap_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteAp" data-id="{{ $ap->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More Aps --}}
                <h6>Add New Aps</h6>
                <table class="table table-bordered" id="addRemoveAp">
                    <thead>
                        <tr>
                            <th>AP Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="ap_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($aps as $ap)
                                        <option value="{{ $ap->id }}">{{ $ap->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="ap_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="ap_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveApButton">Add Ap</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>AP Lite</h5>

                @if(count($apLiteSystems) > 0)
                    <table class="table table-striped my-2" id="apLiteTable">
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
                        @foreach($apLiteSystems as $index => $apLite)
                            <tr data-ap-lite-id="{{ $apLite->id }}">
                                <td class="text-center">{{ $apLite->model }}</td>
                                <td>
                                    <input type="number" name="ap_lite_units[{{ $apLite->id }}]" class="form-control ap_lite-units" 
                                    data-ap-lite-index="{{ $index }}" value="{{ $apLite->ap_lite_units }}">
                                </td>
                                <td>
                                    <input type="number" name="ap_lite_costs[{{ $apLite->id }}]" class="form-control ap_lite-costs" 
                                    data-ap-lite-index="{{ $index }}" value="{{ $apLite->ap_lite_costs }}">
                                </td>
                                <td>
                                    <span id="total-ap-lite-{{ $index }}">{{ $apLite->ap_lite_units * $apLite->ap_lite_costs }}</span>
                                </td>
                                <td>
                                    <a class="btn deleteApLite" data-id="{{ $apLite->id }}"><i class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif

                {{-- Add More Aps Lite --}}
                <h6>Add New Ap Lites</h6>
                <table class="table table-bordered" id="addRemoveApLite">
                    <thead>
                        <tr>
                            <th>APLite Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="ap_lite_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($aps as $ap)
                                        <option value="{{ $ap->id }}">{{ $ap->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="ap_lite_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="ap_lite_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveApLiteButton">Add Ap Lite</button></td>
                        </tr>
                    </tbody>
                </table>


                <hr class="mt-4">
                <h5>Air Max / PTP</h5>

                @if(count($ptpSystems) > 0)
                    <table class="table table-striped my-2" id="ptpTable">
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
                            @foreach($ptpSystems as $index => $ptp)
                                <tr data-ptp-id="{{ $ptp->id }}">
                                    <td class="text-center">{{ $ptp->model }}</td>
                                    <td>
                                        <input type="number" name="ptp_units[{{ $ptp->id }}]" class="form-control ptp-units" 
                                        data-ptp-index="{{ $index }}" value="{{ $ptp->ptp_units }}">
                                    </td>
                                    <td>
                                        <input type="number" name="ptp_costs[{{ $ptp->id }}]" class="form-control ptp-costs" 
                                        data-ptp-index="{{ $index }}" value="{{ $ptp->ptp_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-ptp-{{ $index }}">{{ $ptp->ptp_units * $ptp->ptp_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deletePtp" data-id="{{ $ptp->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More ptps --}}
                <h6>Add New PTPs</h6>
                <table class="table table-bordered" id="addRemovePtp">
                    <thead>
                        <tr>
                            <th>Ptp Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="ptp_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($ptps as $ptp)
                                        <option value="{{ $ptp->id }}">{{ $ptp->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="ptp_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="ptp_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemovePtpButton">Add Ptp</button></td>
                        </tr>
                    </tbody>
                </table>



                <hr class="mt-4">
                <h5>UISP Air Max</h5>

                @if(count($uispSystems) > 0)
                    <table class="table table-striped my-2" id="uispTable">
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
                            @foreach($uispSystems as $index => $uisp)
                                <tr data-uisp-id="{{ $uisp->id }}">
                                    <td class="text-center">{{ $uisp->model }}</td>
                                    <td>
                                        <input type="number" name="uisp_units[{{ $uisp->id }}]" class="form-control uisp-units" 
                                        data-uisp-index="{{ $index }}" value="{{ $uisp->uisp_units }}">
                                    </td>
                                    <td>
                                        <input type="number" name="uisp_costs[{{ $uisp->id }}]" class="form-control uisp-costs" 
                                        data-uisp-index="{{ $index }}" value="{{ $uisp->uisp_costs }}">
                                    </td>
                                    <td>
                                        <span id="total-uisp-{{ $index }}">{{ $uisp->uisp_units * $uisp->uisp_costs }}</span>
                                    </td>
                                    <td>
                                        <a class="btn deleteUisp" data-id="{{ $uisp->id }}"><i class="fa fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                {{-- Add More UISPs --}}
                <h6>Add New UISPs</h6>
                <table class="table table-bordered" id="addRemoveUisp">
                    <thead>
                        <tr>
                            <th>Uisp Model</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="uisp_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($uisps as $uisp)
                                        <option value="{{ $uisp->id }}">{{ $uisp->model }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="uisp_units[0][subject]" class="form-control" data-id="0"></td>
                            <td><input type="number" name="uisp_costs[0][subject]" class="form-control" data-id="0"></td>
                            <td><button type="button" class="btn btn-outline-primary" id="addRemoveUispButton">Add Uisp</button></td>
                        </tr>
                    </tbody>
                </table>


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
$(function () {
    let routerIndex = 1;
    const routersData = @json($routers);

    $('#addRemoveRouterButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        routersData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="router_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="router_units[${routerIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="router_costs[${routerIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveRouter tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        routerIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimersRouter = {};
    $(document).on('input', '.router-units, .router-costs', function () {
        const indexRouter = $(this).data('router-index'); 

        // Use correct attribute selector data-router-index
        const unit = parseFloat($(`.router-units[data-router-index="${indexRouter}"]`).val()) || 0;
        const cost = parseFloat($(`.router-costs[data-router-index="${indexRouter}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-router-${indexRouter}`).text(total);

        clearTimeout(debounceTimersRouter[indexRouter]);
        debounceTimersRouter[indexRouter] = setTimeout(() => {
            const row = $(this).closest('tr');
            const routerId = row.data('router-id');

            $.ajax({
                url: `/update-internet-router/${routerId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete internet system router
    $('#routerTable').on('click', '.deleteRouter',function() {
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


    let switchIndex = 1;
    const switchsData = @json($switchs);

    $('#addRemoveSwitchButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        switchsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="switch_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="switch_units[${switchIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="switch_costs[${switchIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveSwitch tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        switchIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimersSwitch = {};
    $(document).on('input', '.switch-units, .switch-costs', function () {
        const indexSwitch = $(this).data('switch-index'); 

        // Use correct attribute selector data-switch-index
        const unit = parseFloat($(`.switch-units[data-switch-index="${indexSwitch}"]`).val()) || 0;
        const cost = parseFloat($(`.switch-costs[data-switch-index="${indexSwitch}"]`).val()) || 0;
        
        const total = (unit * cost).toFixed(2);

        // Update total with correct ID selector
        $(`#total-switch-${indexSwitch}`).text(total);

        clearTimeout(debounceTimersSwitch[indexSwitch]);
        debounceTimersSwitch[indexSwitch] = setTimeout(() => {
            const row = $(this).closest('tr');
            const switchId = row.data('switch-id');

            $.ajax({
                url: `/update-internet-switch/${switchId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete internet system switch
    $('#switchTable').on('click', '.deleteSwitch',function() {
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

    let controllerIndex = 1;
    const controllersData = @json($controllers);

    $('#addRemoveControllerButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        controllersData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="controller_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="controller_units[${controllerIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="controller_costs[${controllerIndex}][subject]" class="form-control"></td>
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

    // Auto-calculate total
    const debounceTimersController = {};
    $(document).on('input', '.controller-units, .controller-costs', function () {

        const indexController = $(this).data('controller-index'); 
        const unit = parseFloat($(`.controller-units[data-controller-index="${indexController}"]`).val()) || 0;
        const cost = parseFloat($(`.controller-costs[data-controller-index="${indexController}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-controller-${indexController}`).text(total);

        clearTimeout(debounceTimersController[indexController]);
        debounceTimersController[indexController] = setTimeout(() => {
            const row = $(this).closest('tr');
            const controllerId = row.data('controller-id');

            $.ajax({
                url: `/update-internet-controller/${controllerId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete internet system controller
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

    
    let apIndex = 1;
    const apsData = @json($aps);

    $('#addRemoveApButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        apsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="ap_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="ap_units[${apIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="ap_costs[${apIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveAp tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        apIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimersAp = {};
    $(document).on('input', '.ap-units, .ap-costs', function () {

        const indexAp = $(this).data('ap-index'); 
        const unit = parseFloat($(`.ap-units[data-ap-index="${indexAp}"]`).val()) || 0;
        const cost = parseFloat($(`.ap-costs[data-ap-index="${indexAp}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-ap-${indexAp}`).text(total);

        clearTimeout(debounceTimersAp[indexAp]);
        debounceTimersAp[indexAp] = setTimeout(() => {
            const row = $(this).closest('tr');
            const apId = row.data('ap-id');

            $.ajax({
                url: `/update-internet-ap/${apId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });
    
    // delete internet system ap
    $('#apTable').on('click', '.deleteAp',function() {
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


    let apLiteIndex = 1;
    const apsLiteData = @json($aps);

    $('#addRemoveApLiteButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        apsLiteData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="ap_lite_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="ap_lite_units[${apLiteIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="ap_lite_costs[${apLiteIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveApLite tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        apLiteIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimersApLite = {};
    $(document).on('input', '.ap_lite-units, .ap_lite-costs', function () {

        const indexApLite = $(this).data('ap-lite-index'); 
                const unit = parseFloat($(`.ap_lite-units[data-ap-lite-index="${indexApLite}"]`).val()) || 0;
        const cost = parseFloat($(`.ap_lite-costs[data-ap-lite-index="${indexApLite}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        
        $(`#total-ap-lite-${indexApLite}`).text(total);

        clearTimeout(debounceTimersApLite[indexApLite]);
        debounceTimersApLite[indexApLite] = setTimeout(() => {
            const row = $(this).closest('tr');
            const apLiteId = row.data('ap-lite-id');

            $.ajax({
                url: `/update-internet-ap-lite/${apLiteId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete internet system ap lite
    $('#apLiteTable').on('click', '.deleteApLite',function() {
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


    let ptpIndex = 1;
    const ptpsData = @json($ptps);

    $('#addRemovePtpButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        ptpsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="ptp_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="ptp_units[${ptpIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="ptp_costs[${ptpIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemovePtp tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        ptpIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimersPtp = {};
    $(document).on('input', '.ptp-units, .ptp-costs', function () {

        const indexPtp = $(this).data('ptp-index'); 
        const unit = parseFloat($(`.ptp-units[data-ptp-index="${indexPtp}"]`).val()) || 0;
        const cost = parseFloat($(`.ptp-costs[data-ptp-index="${indexPtp}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-ptp-${indexPtp}`).text(total);

        clearTimeout(debounceTimersPtp[indexPtp]);
        debounceTimersPtp[indexPtp] = setTimeout(() => {
            const row = $(this).closest('tr');
            const ptpId = row.data('ptp-id');

            $.ajax({
                url: `/update-internet-ptp/${ptpId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete internet system ptp
    $('#ptpTable').on('click', '.deletePtp',function() {
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


    let uispIndex = 1;
    const uispsData = @json($uisps);

    $('#addRemoveUispButton').on('click', function () {
        let options = '<option disabled selected>Choose one...</option>';
        uispsData.forEach(t => {
            options += `<option value="${t.id}">${t.model}</option>`;
        });

        const newRow = `
            <tr>
                <td><select name="uisp_ids[]" class="selectpicker form-control" data-live-search="true">${options}</select></td>
                <td><input type="number" name="uisp_units[${uispIndex}][subject]" class="form-control"></td>
                <td><input type="number" name="uisp_costs[${uispIndex}][subject]" class="form-control"></td>
                <td><button type="button" class="btn btn-outline-danger remove-input-row">Delete</button></td>
            </tr>
        `;

        $('#addRemoveUisp tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        uispIndex++;
    });

    $(document).on('click', '.remove-input-row', function () {
        $(this).closest('tr').remove();
    });

    // Auto-calculate total
    const debounceTimersUisp = {};
    $(document).on('input', '.uisp-units, .uisp-costs', function () {

        const indexUisp = $(this).data('uisp-index'); 
        const unit = parseFloat($(`.uisp-units[data-uisp-index="${indexUisp}"]`).val()) || 0;
        const cost = parseFloat($(`.uisp-costs[data-uisp-index="${indexUisp}"]`).val()) || 0;
        const total = (unit * cost).toFixed(2);
        $(`#total-uisp-${indexUisp}`).text(total);

        clearTimeout(debounceTimersUisp[indexUisp]);
        debounceTimersUisp[indexUisp] = setTimeout(() => {
            const row = $(this).closest('tr');
            const uispId = row.data('uisp-id');

            $.ajax({
                url: `/update-internet-uisp/${uispId}/${unit}/${cost}`,
                method: 'GET',
                success: function (response) {
                    if (response.success === 1) {
                        Swal.fire({ icon: 'success', title: response.msg, confirmButtonText: 'Okay!' });
                    }
                }
            });
        }, 500);
    });

    // delete internet system uisp
    $('#uispTable').on('click', '.deleteUisp',function() {
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
});
</script>

@endsection