@extends('layouts/layoutMaster')

@section('title', 'edit all incident')

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
    <span class="text-muted fw-light">Edit </span> 
        @if($allEnergyIncident) 

            @if($allEnergyIncident->all_energy_meter_id)
                @if($allEnergyIncident->AllEnergyMeter->household_id)
                    {{$allEnergyIncident->AllEnergyMeter->Household->english_name}}
                @else @if($allEnergyIncident->AllEnergyMeter->public_structure_id)

                    {{$allEnergyIncident->AllEnergyMeter->PublicStructure->english_name}}
                @endif
                @endif
            @else @if($allEnergyIncident->energy_system_id)
                {{$allEnergyIncident->EnergySystem->name}} - <span class="text-warning">Energy System</span>
            @endif
            @endif
        @else @if($allWaterIncident)

            @if($allWaterIncident->all_water_holder_id)
                @if($allWaterIncident->AllWaterHolder->household_id)
                    {{$allWaterIncident->AllWaterHolder->Household->english_name}}
                @else @if($allWaterIncident->AllWaterHolder->public_structure_id)

                    {{$allWaterIncident->AllWaterHolder->PublicStructure->english_name}}
                @endif
                @endif
            @else @if($allWaterIncident->water_system_id)
                {{$allWaterIncident->WaterSystem->name}} - <span class="text-info">Water System</span>
            @endif
            @endif
        @else @if($allInternetIncident)

            @if($allInternetIncident->internet_user_id)
                @if($allInternetIncident->InternetUser->household_id)
                    {{$allInternetIncident->InternetUser->Household->english_name}}
                @else @if($allInternetIncident->InternetUser->public_structure_id)

                    {{$allInternetIncident->InternetUser->PublicStructure->english_name}}
                @endif
                @endif
            @else @if($allInternetIncident->community_id)
                {{$allInternetIncident->InternetSystemCommunity->InternetSystem->system_name}} - <span class="text-success">Internet System</span>
            @endif
            @endif
        @else @if($allCameraIncident)

            {{$allCameraIncident->Community->english_name}} - <span class="text-primary">Camera</span>
        @endif
        @endif
        @endif
        @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('all-incident.update', $allIncident->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                <option value="{{$allIncident->community_id}}">
                                    {{$allIncident->Community->english_name}}
                                </option>                               
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Service Type</label>
                            <select class=" form-control" name="service_id" disabled>
                                <option value="{{$allIncident->service_id}}">
                                    {{$allIncident->ServiceType->service_name}}
                                </option>                               
                            </select>
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Incident Type</label>
                            <select name="incident_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($allIncident->incident_id)
                                    <option value="{{$allIncident->incident_id}}">
                                        {{$allIncident->Incident->english_name}}
                                    </option>
                                    @foreach($incidents as $incident)
                                        <option value="{{$incident->id}}">
                                            {{$incident->english_name}}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidents as $incident)
                                        <option value="{{$incident->id}}">
                                            {{$incident->english_name}}
                                        </option>
                                    @endforeach
                                @endif                                 
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Incident</label>
                            <input type="date" name="date" value="{{$allIncident->date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Response Date</label>
                            <input type="date" name="response_date" value="{{$allIncident->response_date}}" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                @if($allIncident->incident_id == 4)
                <div id="swoDiv">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Number</label>
                                <input type="number" name="order_number" class="form-control"
                                    value="{{$allIncident->order_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Date</label>
                                <input type="date" name="order_date" class="form-control"
                                    value="{{$allIncident->order_date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Lat</label>
                                <input type="text" name="geolocation_lat" class="form-control"
                                value="{{$allIncident->geolocation_lat}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Geolocation Long</label>
                                <input type="text" name="geolocation_long" class="form-control"
                                value="{{$allIncident->geolocation_long}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date of hearing</label>
                                <input type="date" name="hearing_date" class="form-control"
                                value="{{$allIncident->hearing_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                <input type="text" name="building_permit_request_number" class="form-control"
                                value="{{$allIncident->building_permit_request_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                <input type="date" name="building_permit_request_submission_date" class="form-control"
                                value="{{$allIncident->building_permit_request_submission_date}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                <input type="text" name="illegal_construction_case_number" class="form-control"
                                value="{{$allIncident->illegal_construction_case_number}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>District Court Case Number</label>
                                <input type="text" name="district_court_case_number" class="form-control"
                                value="{{$allIncident->district_court_case_number}}">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                <input type="text" name="supreme_court_case_number" class="form-control"
                                value="{{$allIncident->supreme_court_case_number}}">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Description of structure</label>
                                <textarea name="structure_description" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$allIncident->structure_description}}
                                </textarea>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Case Chronology</label>
                                <textarea name="case_chronology" class="form-control" 
                                    style="resize:none" cols="20" rows="3">
                                    {{$allIncident->case_chronology}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row"> 
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Description (User - USS)</label>
                            <textarea name="description" class="form-control" 
                                style="resize:none" cols="20" rows="5">
                            {{$allIncident->description}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Description (Manager - USS)</label>
                            <textarea name="manager_description" class="form-control" 
                                style="resize:none" cols="20" rows="5">
                            {{$allIncident->manager_description}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Description (Platform)</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="5">
                            {{$allIncident->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <hr>
                <div class="row" style="margin-top: 40px">
                    <h5>Incident Statuses</h5>
                </div>

                @if(count($incidentStatuses) > 0)
                    <table class="table table-striped my-2" id="incidentStatusesTable">
                        <tbody>
                            @foreach($incidentStatuses as $incidentStatus)
                                <tr id="incidentStatusRow">
                                    <td class="text-center">
                                        {{ $incidentStatus->AllIncidentStatus->status ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteIncidentStatus" 
                                        data-id="{{ $incidentStatus->id }}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Add Incident Status</label>
                            <select class="selectpicker form-control" multiple data-live-search="true"
                                name="new_statuses[]">
                                <option disabled selected>Choose one...</option>
                                @php
                                    $filteredStatuses = $statuses->where('incident_id', $allIncident->incident_id);
                                @endphp

                                @foreach($filteredStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->status }}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>


                <hr>
                <div class="row" style="margin-top: 40px">
                    <h5 class="text-danger">Equipments Damaged</h5>
                </div>

                @if($allEnergyIncident && $allEnergyIncident->equipmentDamaged && 
                    count($allEnergyIncident->equipmentDamaged) > 0)
                    <table class="table table-striped my-2" id="equipmentEnergyDamagedTable">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allEnergyIncident->equipmentDamaged as $index => $equipment)
                                <tr id="equipmentEnergyDamagedRow" data-energy-equipment-id="{{ $equipment->id }}">
                                    <td class="text-center">
                                        {{ $equipment->IncidentEquipment->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="energy_equipment_units[{{ $equipment->id }}]"
                                            class="form-control energy-equipment-units" step="any"
                                            data-energy-equipment-index="{{ $index }}"
                                            value="{{ $equipment->count }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="energy_equipment_costs[{{ $equipment->id }}]"
                                            class="form-control energy-equipment-costs" step="any"
                                            data-energy-equipment-index="{{ $index }}"
                                            value="{{ $equipment->cost }}">
                                    </td>
                                    <td class="text-center" id="total-energy-equipment-{{ $index }}">
                                        {{ number_format($equipment->count * $equipment->cost, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteEquipmentEnergyDamaged" data-id="{{ $equipment->id }}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else @if($allEnergyIncident && $allEnergyIncident->damagedSystemEquipments)
                    @if(count($allEnergyIncident->damagedSystemEquipments) > 0)
                        <table class="table table-striped my-2" id="energySystemDamagedEquipmentTable">
                            <tbody>
                                @foreach($allEnergyIncident->damagedSystemEquipments as $index => $equipment)
                                @php
                                    $model = '';
                                    $type = '';
                                    if ($equipment->batteryMount) {

                                        $model = $equipment->batteryMount->model->model ?? '-';
                                        $type = "BatteryMount";
                                    } elseif ($equipment->pv) {

                                        $model = $equipment->pv->model->pv_model ?? '-';
                                        $type = "EnergyPV";
                                    } elseif ($equipment->inverter) {

                                        $model = $equipment->inverter->model->inverter_model ?? '-';
                                        $type = "EnergyInverter";
                                    } elseif ($equipment->battery) {

                                        $model = $equipment->battery->model->battery_model ?? '-';
                                        $type = "EnergyBattery";
                                    } elseif ($equipment->batteryStatusProcessor) {

                                        $model = $equipment->batteryStatusProcessor->model->model ?? '-';
                                        $type = "EnergyBSP";
                                    } elseif ($equipment->batteryTemperatureSensor) {

                                        $model = $equipment->batteryTemperatureSensor->model->BTS_model ?? '-';
                                        $type = "EnergyBTS";
                                    } elseif ($equipment->chargeController) {

                                        $model = $equipment->chargeController->model->charge_controller_model ?? '-';
                                        $type = "EnergyChargeController";
                                    } elseif ($equipment->generator) {

                                        $model = $equipment->generator->model->generator_model ?? '-';
                                        $type = "EnergyGenerator";
                                    } elseif ($equipment->loadRelay) {

                                        $model = $equipment->loadRelay->model->load_relay_model ?? '-';
                                        $type = "EnergyLoadRelay";
                                    } elseif ($equipment->mcbChargeController) {

                                        $model = $equipment->mcbChargeController->model->model ?? '-';
                                        $type = "EnergyMcbChargeController";
                                    } elseif ($equipment->mcbInverter) {

                                        $model = $equipment->mcbInverter->model->inverter_MCB_model ?? '-';
                                        $type = "EnergyMcbInverter";
                                    } elseif ($equipment->mcbPv) {

                                        $model = $equipment->mcbPv->model->model ?? '-';
                                        $type = "EnergyMcbPv";
                                    } elseif ($equipment->pvMount) {

                                        $model = $equipment->pvMount->model->model ?? '-';
                                        $type = "EnergyPvMount";
                                    } elseif ($equipment->relayDriver) {

                                        $model = $equipment->relayDriver->model->model ?? '-';
                                        $type = "EnergyRelayDriver";
                                    } elseif ($equipment->remoteControlCenter) {

                                        $model = $equipment->remoteControlCenter->model->model ?? '-';
                                        $type = "EnergyRemoteControlCenter";
                                    } elseif ($equipment->windTurbine) {

                                        $model = $equipment->windTurbine->model->wind_turbine_model ?? '-';
                                        $type = "EnergyWindTurbine";
                                    } elseif ($equipment->airConditioner) {

                                        $model = $equipment->airConditioner->model->model ?? '-';
                                        $type = "EnergyAirConditioner";
                                    } elseif ($equipment->monitoring) {

                                        $model = $equipment->monitoring->model->monitoring_model ?? '-';
                                        $type = "EnergyMonitoring";
                                    }
                                @endphp

                                    <tr data-energy-system-equipment-id="{{ $equipment->id }}">
                                        <td class="text-center">
                                            {{ $model ?? 'N/A' }} - ( {{$type}})
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="energy_system_equipment_units[{{ $equipment->component_energy_system_id }}]"
                                                class="form-control energy-system-equipment-units" step="any"
                                                data-energy-system-equipment-index="{{ $index }}"
                                                value="{{ $equipment->count }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="energy_system_equipment_costs[{{ $equipment->component_energy_system_id }}]"
                                                class="form-control energy-system-equipment-costs" step="any"
                                                data-energy-system-equipment-index="{{ $index }}"
                                                value="{{ $equipment->cost }}">
                                        </td>
                                        <td class="text-center" id="total-energy-system-equipment-{{ $index }}">
                                            {{ number_format($equipment->count * $equipment->cost, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteEnergySystemDamagedEquipment" 
                                            data-id="{{ $equipment->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
                @endif

                @if($allEnergyIncident && $allEnergyIncident->AllEnergyMeter)

                    <h6>Add New Energy Equipments Damaged</h6>
                    <table class="table table-bordered" id="addRemoveEnergyEquipmentsDamaged">
                        <thead>
                            <tr>
                                <th>Energy Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="energy_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @php
                                            $userPublicEnergyEquipments = $userPublicEnergyEquipments->where('incident_equipment_type_id', 2);
                                        @endphp
                                        @foreach($userPublicEnergyEquipments as $equipment)
                                            <option value="{{ $equipment->id }}"
                                            data-cost="{{$equipment['cost']}}">{{ $equipment->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="any" name="energy_equipment_damaged_units[0][subject]"
                                        class="form-control" data-energy-equipment-index="0">
                                </td>
                                <td>
                                    <input type="number" step="any" name="energy_equipment_damaged_costs[0][subject]"
                                        class="form-control" data-energy-equipment-index="0">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary" id="addRemoveEnergyEquipmentsDamagedButton">
                                        Add Energy Equipments Damaged
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @else @if($allEnergyIncident && $allEnergyIncident->EnergySystem)

                    <h6>Add New Energy System Equipments Damaged</h6>
                    <table class="table table-bordered" id="addRemoveEnergySystemEquipmentsDamaged">
                        <thead>
                            <tr>
                                <th>Energy System Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="energy_system_equipment_damaged_ids[]" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($energySystemComponents as $systemComponent)
                                            <option value="{{ $systemComponent['component_energy_system_id'] }}"
                                            data-cost="{{$systemComponent['cost']}}" data-type="{{$systemComponent['type']}}">
                                                {{ $systemComponent['model_name'] }} - ({{ $systemComponent['type'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="energy_system_equipment_types[0][subject]" 
                                        class="energy-system-equipment-type-hidden" />
                                </td>
                                <td>
                                    <input type="number" step="any" name="energy_system_equipment_damaged_units[0][subject]"
                                        class="form-control" data-energy-system-equipment-index="0">
                                </td>
                                <td>
                                    <input type="number" step="any" name="energy_system_equipment_damaged_costs[0][subject]"
                                        class="form-control" data-energy-system-equipment-index="0">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary" 
                                        id="addRemoveEnergySystemEquipmentsDamagedButton">
                                        Add Energy System Equipments Damaged
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
                @endif


                @if($allWaterIncident && $allWaterIncident->equipmentDamaged && 
                    count($allWaterIncident->equipmentDamaged) > 0)
                    <table class="table table-striped my-2" id="equipmentWaterDamagedTable">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allWaterIncident->equipmentDamaged as $index => $equipment)
                                <tr id="equipmentWaterDamagedRow" data-water-equipment-id="{{ $equipment->id }}">
                                    <td class="text-center">
                                        {{ $equipment->IncidentEquipment->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="water_equipment_units[{{ $equipment->id }}]"
                                            class="form-control water-equipment-units" step="any"
                                            data-water-equipment-index="{{ $index }}"
                                            value="{{ $equipment->count }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="water_equipment_costs[{{ $equipment->id }}]"
                                            class="form-control water-equipment-costs" step="any"
                                            data-water-equipment-index="{{ $index }}"
                                            value="{{ $equipment->cost }}">
                                    </td>
                                    <td class="text-center" id="total-water-equipment-{{ $index }}">
                                        {{ number_format($equipment->count * $equipment->cost, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteEquipmentWaterDamaged" data-id="{{ $equipment->id }}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else @if($allWaterIncident && $allWaterIncident->damagedSystemEquipments)
                    @if(count($allWaterIncident->damagedSystemEquipments) > 0)
                        <table class="table table-striped my-2" id="waterSystemDamagedEquipmentTable">
                            <tbody>
                                @foreach($allWaterIncident->damagedSystemEquipments as $index => $equipment)
                                @php
                                    $model = '';
                                    $type = '';
                                    
                                    if ($equipment->tank) {

                                        $model = $equipment->tank->model->model ?? '-';
                                        $type = "WaterTank";
                                    } elseif ($equipment->tap) {

                                        $model = $equipment->tap->model->model ?? '-';
                                        $type = "WaterTap";
                                    } elseif ($equipment->filter) {

                                        $model = $equipment->filter->model->model ?? '-';
                                        $type = "WaterFilter";
                                    } elseif ($equipment->connector) {

                                        $model = $equipment->connector->model->model ?? '-';
                                        $type = "WaterConnector";
                                    } elseif ($equipment->pipe) {

                                        $model = $equipment->pipe->model->model ?? '-';
                                        $type = "WaterPipe";
                                    } elseif ($equipment->pump) {

                                        $model = $equipment->pump->model->model ?? '-';
                                        $type = "WaterPump";
                                    } elseif ($equipment->valve) {

                                        $model = $equipment->valve->model->model ?? '-';
                                        $type = "WaterValve";
                                    } 
                                @endphp

                                    <tr data-water-system-equipment-id="{{ $equipment->id }}">
                                        <td class="text-center">
                                            {{ $model ?? 'N/A' }} - ( {{$type}})
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="water_system_equipment_units[{{ $equipment->component_water_system_id }}]"
                                                class="form-control water-system-equipment-units" step="any"
                                                data-water-system-equipment-index="{{ $index }}"
                                                value="{{ $equipment->count }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="water_system_equipment_costs[{{ $equipment->component_water_system_id }}]"
                                                class="form-control water-system-equipment-costs" step="any"
                                                data-water-system-equipment-index="{{ $index }}"
                                                value="{{ $equipment->cost }}">
                                        </td>
                                        <td class="text-center" id="total-water-system-equipment-{{ $index }}">
                                            {{ number_format($equipment->count * $equipment->cost, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteWaterSystemDamagedEquipment" 
                                            data-id="{{ $equipment->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
                @endif

                @if($allWaterIncident && $allWaterIncident->AllWaterHolder)
                <h6>Add New Water Equipments Damaged</h6>
                <table class="table table-bordered" id="addRemoveWaterEquipmentsDamaged">
                    <thead>
                        <tr>
                            <th>Water Equipment</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="water_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @php
                                        $userPublicWaterEquipments = $userPublicEnergyEquipments->where('incident_equipment_type_id', 1);
                                    @endphp
                                    @foreach($userPublicWaterEquipments as $equipment)
                                        <option value="{{ $equipment->id }}"
                                            data-cost="{{$equipment['cost']}}">
                                            {{ $equipment->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="any" name="water_equipment_damaged_units[0][subject]"
                                    class="form-control" data-water-equipment-index="0">
                            </td>
                            <td>
                                <input type="number" step="any" name="water_equipment_damaged_costs[0][subject]"
                                    class="form-control" data-water-equipment-index="0">
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary" id="addRemoveWaterEquipmentsDamagedButton">
                                    Add Water Equipments Damaged
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @else @if($allWaterIncident && $allWaterIncident->WaterSystem)

                    <h6>Add New Water System Equipments Damaged</h6>
                    <table class="table table-bordered" id="addRemoveWaterSystemEquipmentsDamaged">
                        <thead>
                            <tr>
                                <th>Water System Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="water_system_equipment_damaged_ids[]" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterSystemComponents as $systemComponent)
                                            <option value="{{ $systemComponent['component_water_system_id'] }}"
                                            data-cost="{{$systemComponent['cost']}}" data-type="{{$systemComponent['type']}}">
                                                {{ $systemComponent['model_name'] }} - ({{ $systemComponent['type'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="water_system_equipment_types[0][subject]" 
                                        class="water-system-equipment-type-hidden" />
                                </td>
                                <td>
                                    <input type="number" step="any" name="water_system_equipment_damaged_units[0][subject]"
                                        class="form-control" data-water-system-equipment-index="0">
                                </td>
                                <td>
                                    <input type="number" step="any" name="water_system_equipment_damaged_costs[0][subject]"
                                        class="form-control" data-water-system-equipment-index="0">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary" 
                                        id="addRemoveWaterSystemEquipmentsDamagedButton">
                                        Add Water System Equipments Damaged
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
                @endif               


                @if($allInternetIncident && $allInternetIncident->equipmentDamaged && 
                    count($allInternetIncident->equipmentDamaged) > 0)
                    <table class="table table-striped my-2" id="equipmentInternetDamagedTable">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allInternetIncident->equipmentDamaged as $index => $equipment)
                                <tr id="equipmentInternetDamagedRow" data-internet-equipment-id="{{ $equipment->id }}">
                                    <td class="text-center">
                                        {{ $equipment->IncidentEquipment->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="internet_equipment_units[{{ $equipment->id }}]"
                                            class="form-control internet-equipment-units" step="any"
                                            data-internet-equipment-index="{{ $index }}"
                                            value="{{ $equipment->count }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="internet_equipment_costs[{{ $equipment->id }}]"
                                            class="form-control internet-equipment-costs" step="any"
                                            data-internet-equipment-index="{{ $index }}"
                                            value="{{ $equipment->cost }}">
                                    </td>
                                    <td class="text-center" id="total-internet-equipment-{{ $index }}">
                                        {{ number_format($equipment->count * $equipment->cost, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteEquipmentInternetDamaged" data-id="{{ $equipment->id }}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else @if($allInternetIncident && $allInternetIncident->damagedSystemEquipments)
                    @if(count($allInternetIncident->damagedSystemEquipments) > 0)
                        <table class="table table-striped my-2" id="internetSystemDamagedEquipmentTable">
                            <tbody>
                                @foreach($allInternetIncident->damagedSystemEquipments as $index => $equipment)
                                @php
                                    $model = '';
                                    $type = ''; 
                                    
                                    if ($equipment->networkCabinetComponent) {
                    
                                        $componentModel = $equipment->networkCabinetComponent->component->model 
                                            ?? 'Unknown Model';
                                        $componentType = class_basename($equipment->networkCabinetComponent->component_type 
                                            ?? 'Unknown');
                                        $cabinet = $equipment->networkCabinetComponent->networkCabinetInternetSystem->networkCabinet 
                                            ?? null;

                                        $cabinetModel = $cabinet->model ?? 'Unknown Cabinet';

                                        $model = "{$cabinetModel} - {$componentModel} ";
                                        $type = $componentType; 
                                    } elseif ($equipment->router) {

                                        $model = $equipment->router->model->model ?? '-';
                                        $type = "Router";
                                    } elseif ($equipment->switch) {

                                        $model = $equipment->switch->model->model ?? '-';
                                        $type = "Switche";
                                    } elseif ($equipment->controller) {

                                        $model = $equipment->controller->model->model ?? '-';
                                        $type = "InternetController";
                                    } elseif ($equipment->uisp) {

                                        $model = $equipment->uisp->model->model ?? '-';
                                        $type = "InternetUisp";
                                    } elseif ($equipment->ptp) {

                                        $model = $equipment->ptp->model->model ?? '-';
                                        $type = "InternetPtp";
                                    } elseif ($equipment->ap) {

                                        $model = $equipment->ap->model->model ?? '-';
                                        $type = "InternetAp";
                                    } elseif ($equipment->aplite) {

                                        $model = $equipment->aplite->model->model ?? '-';
                                        $type = "InternetApLite";
                                    } elseif ($equipment->connector) {

                                        $model = $equipment->connector->model->model ?? '-';
                                        $type = "InternetConnector";
                                    } elseif ($equipment->electrician) {

                                        $model = $equipment->electrician->model->model ?? '-';
                                        $type = "InternetElectrician";
                                    } 
                                @endphp

                                    <tr data-internet-system-equipment-id="{{ $equipment->id }}">
                                        <td class="text-center">
                                            {{ $model ?? 'N/A' }} - ( {{$type}})
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="internet_system_equipment_units[{{ $equipment->component_internet_system_id }}]"
                                                class="form-control internet-system-equipment-units" step="any"
                                                data-internet-system-equipment-index="{{ $index }}"
                                                value="{{ $equipment->count }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="internet_system_equipment_costs[{{ $equipment->component_internet_system_id }}]"
                                                class="form-control internet-system-equipment-costs" step="any"
                                                data-internet-system-equipment-index="{{ $index }}"
                                                value="{{ $equipment->cost }}">
                                        </td>
                                        <td class="text-center" id="total-internet-system-equipment-{{ $index }}">
                                            {{ number_format($equipment->count * $equipment->cost, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteInternetSystemDamagedEquipment" 
                                            data-id="{{ $equipment->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
                @endif

                @if($allInternetIncident && $allInternetIncident->InternetUser)
                <h6>Add New Internet Equipments Damaged</h6>
                <table class="table table-bordered" id="addRemoveInternetEquipmentsDamaged">
                    <thead>
                        <tr>
                            <th>Internet Equipment</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="internet_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @php
                                        $userPublicInternetEquipments = $userPublicEnergyEquipments->where('incident_equipment_type_id', 4);
                                    @endphp
                                    @foreach($userPublicInternetEquipments as $equipment)
                                        <option value="{{ $equipment->id }}"
                                            data-cost="{{$equipment['cost']}}" >
                                            {{ $equipment->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="any" name="internet_equipment_damaged_units[0][subject]"
                                    class="form-control" data-internet-equipment-index="0">
                            </td>
                            <td>
                                <input type="number" step="any" name="internet_equipment_damaged_costs[0][subject]"
                                    class="form-control" data-internet-equipment-index="0">
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary" id="addRemoveInternetEquipmentsDamagedButton">
                                    Add Internet Equipments Damaged
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @else @if($allInternetIncident && $allInternetIncident->Community)

                    <h6>Add New Internet System Equipments Damaged</h6>
                    <table class="table table-bordered" id="addRemoveInternetSystemEquipmentsDamaged">
                        <thead>
                            <tr>
                                <th>Internet System Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="internet_system_equipment_damaged_ids[]" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Choose one...</option>
                                        @foreach($internetSystemComponents as $systemComponent)
                                            <option value="{{ $systemComponent['component_internet_system_id'] }}"
                                            data-cost="{{$systemComponent['cost']}}" 
                                            data-type="{{$systemComponent['type']}}"
                                            data-cabinet-model="{{ $systemComponent['cabinet_model'] }}">
                                                {{ $systemComponent['model_name'] }} - ({{ $systemComponent['type'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="internet_system_equipment_types[0][subject]" 
                                        class="internet-system-equipment-type-hidden" />
                                    <input type="hidden" name="equipment_is_cabinet[0]" 
                                        class="equipment-cabinet-hidden" />
                                </td>
                                <td>
                                    <input type="number" step="any" name="internet_system_equipment_damaged_units[0][subject]"
                                        class="form-control" data-internet-system-equipment-index="0">
                                </td>
                                <td>
                                    <input type="number" step="any" name="internet_system_equipment_damaged_costs[0][subject]"
                                        class="form-control" data-internet-system-equipment-index="0">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary" 
                                        id="addRemoveInternetSystemEquipmentsDamagedButton">
                                        Add Internet System Equipments Damaged
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
                @endif  


                @if($allCameraIncident && $allCameraIncident->equipmentDamaged && 
                    count($allCameraIncident->equipmentDamaged) > 0)
                    <table class="table table-striped my-2" id="equipmentCameraDamagedTable">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Units</th>
                                <th>Cost per Unit</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allCameraIncident->equipmentDamaged as $index => $equipment)
                                <tr id="equipmentCameraDamagedRow" data-camera-equipment-id="{{ $equipment->id }}">
                                    <td class="text-center">
                                        {{ $equipment->IncidentEquipment->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="camera_equipment_units[{{ $equipment->id }}]"
                                            class="form-control camera-equipment-units" step="any"
                                            data-camera-equipment-index="{{ $index }}"
                                            value="{{ $equipment->count }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="camera_equipment_costs[{{ $equipment->id }}]"
                                            class="form-control camera-equipment-costs" step="any"
                                            data-camera-equipment-index="{{ $index }}"
                                            value="{{ $equipment->cost }}">
                                    </td>
                                    <td class="text-center" id="total-camera-equipment-{{ $index }}">
                                        {{ number_format($equipment->count * $equipment->cost, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteEquipmentCameraDamaged" data-id="{{ $equipment->id }}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if($allCameraIncident)
                <h6>Add New Camera Equipments Damaged</h6>
                <table class="table table-bordered" id="addRemoveCameraEquipmentsDamaged">
                    <thead>
                        <tr>
                            <th>Camera Equipment</th>
                            <th>Units</th>
                            <th>Cost per Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="camera_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @php
                                        $userPublicCameraEquipments = $userPublicEnergyEquipments->where('incident_equipment_type_id', 5);
                                    @endphp
                                    @foreach($userPublicCameraEquipments as $equipment)
                                        <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="any" name="camera_equipment_damaged_units[0][subject]"
                                    class="form-control" data-camera-equipment-index="0">
                            </td>
                            <td>
                                <input type="number" step="any" name="camera_equipment_damaged_costs[0][subject]"
                                    class="form-control" data-camera-equipment-index="0">
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary" id="addRemoveCameraEquipmentsDamagedButton">
                                    Add Camera Equipments Damaged
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endif 


                @if($allEnergyIncident && $allEnergyIncident->EnergySystem && $allEnergyIncident->affectedHouseholds)
                    <hr>
                    <div class="row" style="margin-top: 40px">
                        <h5 class="text-primary">Affected Households</h5>
                    </div>
                    @if(count($allEnergyIncident->affectedHouseholds) > 0)
                        <table class="table table-striped my-2" id="energyAffectedHouseholdsTable">
                            <tbody>
                                @foreach($allEnergyIncident->affectedHouseholds as $index => $household)
 
                                    <tr data-energy-household-id="{{ $household->id }}">
                                        <td class="text-center">
                                            {{$household->Household->english_name}}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteEnergyAffectedHousehold" 
                                            data-id="{{ $household->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif

                @if($allEnergyIncident && $allEnergyIncident->EnergySystem)

                    <h6>Add New Energy Affected Households</h6>
                    <table class="table table-bordered" id="addRemoveEnergyAffectedHouseholds">
                        <thead>
                            <tr>
                                <th>Energy Affected Households</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="energy_affected_households_ids[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected>Choose one...</option>
                                        @foreach($energyAffectedHouseholds as $energyAffectedHousehold)
                                            <option value="{{ $energyAffectedHousehold->id }}">
                                                {{ $energyAffectedHousehold->english_name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif


                @if($allWaterIncident && $allWaterIncident->WaterSystem && $allWaterIncident->affectedHouseholds)
                    <hr>
                    <div class="row" style="margin-top: 40px">
                        <h5 class="text-primary">Affected Households</h5>
                    </div>
                    @if(count($allWaterIncident->affectedHouseholds) > 0)
                        <table class="table table-striped my-2" id="waterAffectedHouseholdsTable">
                            <tbody>
                                @foreach($allWaterIncident->affectedHouseholds as $index => $household)
 
                                    <tr data-water-household-id="{{ $household->id }}">
                                        <td class="text-center">
                                            {{$household->Household->english_name}}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteWaterAffectedHousehold" 
                                            data-id="{{ $household->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif

                @if($allWaterIncident && $allWaterIncident->WaterSystem)

                    <h6>Add New Water Affected Households</h6>
                    <table class="table table-bordered" id="addRemoveWaterAffectedHouseholds">
                        <thead>
                            <tr>
                                <th>Water Affected Households</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="water_affected_households_ids[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected>Choose one...</option>
                                        @foreach($waterAffectedHouseholds as $waterAffectedHousehold)
                                            <option value="{{ $waterAffectedHousehold->id }}">
                                                {{ $waterAffectedHousehold->english_name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif


                @if($allInternetIncident && $allInternetIncident->Community && $allInternetIncident->affectedHouseholds)
                    <hr>
                    <div class="row" style="margin-top: 40px">
                        <h5 class="text-primary">Affected Households</h5>
                    </div>
                    @if(count($allInternetIncident->affectedHouseholds) > 0)
                        <table class="table table-striped my-2" id="internetAffectedHouseholdsTable">
                            <tbody>
                                @foreach($allInternetIncident->affectedHouseholds as $index => $household)
 
                                    <tr data-internet-household-id="{{ $household->id }}">
                                        <td class="text-center">
                                            {{$household->Household->english_name}}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteInternetAffectedHousehold" 
                                            data-id="{{ $household->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif

                @if($allInternetIncident && $allInternetIncident->Community)

                    <h6>Add New Internet Affected Households</h6>
                    <table class="table table-bordered" id="addRemoveInternetAffectedHouseholds">
                        <thead>
                            <tr>
                                <th>Internet Affected Households</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="internet_affected_households_ids[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected>Choose one...</option>
                                        @foreach($internetAffectedHouseholds as $internetAffectedHousehold)
                                            <option value="{{ $internetAffectedHousehold->id }}">
                                                {{ $internetAffectedHousehold->english_name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif


                
                @if($allInternetIncident && $allInternetIncident->Community && $allInternetIncident->affectedAreas)
                    <hr>
                    <div class="row" style="margin-top: 40px">
                        <h5 class="text-primary">Affected Areas</h5>
                    </div>
                    @if(count($allInternetIncident->affectedAreas) > 0)
                        <table class="table table-striped my-2" id="internetAffectedAreasTable">
                            <tbody>
                                @foreach($allInternetIncident->affectedAreas as $index => $area)
 
                                    <tr data-internet-area-id="{{ $area->id }}">
                                        <td class="text-center">
                                            {{$area->AffectedCommunity->english_name}}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn deleteInternetAffectedAreas" 
                                            data-id="{{ $area->id }}">
                                                <i class="fa fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif

                @if($allInternetIncident && $allInternetIncident->Community)

                    <h6>Add New Internet Affected Areas</h6>
                    <table class="table table-bordered" id="addRemoveInternetAffectedAreas">
                        <thead>
                            <tr>
                                <th>Internet Affected Areas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="internet_affected_areas_ids[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected>Choose one...</option>
                                        @foreach($internetAffectedAreas as $internetAffectedArea)
                                            <option value="{{ $internetAffectedArea->id }}">
                                                {{ $internetAffectedArea->english_name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif


                <hr>
                <div class="row" style="margin-top: 40px">
                    <h5 class="text-info">Incident Photos</h5>
                </div>

                @if($allEnergyIncident && $allEnergyIncident->photos && 
                    count($allEnergyIncident->photos) > 0)

                    <table id="energyIncidentPhotosTable" 
                        class="table table-striped data-table-energy-photos my-2">
                        <tbody>
                            @foreach($allEnergyIncident->photos as $index => $photo)
                            <tr id="energyIncidentPhotoRow">
                                <td class="text-center">
                                    @if($allEnergyIncident->all_energy_meter_id)
                                        <img src="{{url('/incidents/energy/'.$photo->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                    @else
                                        <img src="{{url('/incidents/mg/'.$photo->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergyIncidentPhoto" id="deleteEnergyIncidentPhoto" 
                                        data-id="{{$photo->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if($allWaterIncident && $allWaterIncident->photos && 
                    count($allWaterIncident->photos) > 0)

                    <table id="waterIncidentPhotosTable" 
                        class="table table-striped data-table-water-photos my-2">
                        <tbody>
                            @foreach($allWaterIncident->photos as $index => $photo)
                            <tr id="waterIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/water/'.$photo->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteWaterIncidentPhoto" id="deleteWaterIncidentPhoto" 
                                        data-id="{{$photo->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if($allInternetIncident && $allInternetIncident->photos && 
                    count($allInternetIncident->photos) > 0)

                    <table id="internetIncidentPhotosTable" 
                        class="table table-striped data-table-internet-photos my-2">
                        <tbody>
                            @foreach($allInternetIncident->photos as $index => $photo)
                            <tr id="internetIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/internet/'.$photo->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetIncidentPhoto" id="deleteInternetIncidentPhoto" 
                                        data-id="{{$photo->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif


                @if($allCameraIncident && $allCameraIncident->photos && 
                    count($allCameraIncident->photos) > 0)

                    <table id="cameraIncidentPhotosTable" 
                        class="table table-striped data-table-camera-photos my-2">
                        <tbody>
                            @foreach($allCameraIncident->photos as $index => $photo)
                            <tr id="cameraIncidentPhotoRow">
                                <td class="text-center">
                                    <img src="{{url('/incidents/camera/'.$photo->slug)}}" 
                                        class="d-block w-100" style="max-height:40vh;max-width:40vh;">
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteCameraIncidentPhoto" id="deleteCameraIncidentPhoto" 
                                        data-id="{{$photo->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <h6>Add New Photos</h6>
                <div class="row">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Upload More photos</label>
                        <input type="file" name="more_photos[]"
                            class="btn btn-info me-2 mb-4 block w-full mt-1 rounded-md"
                            accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                    </fieldset>
                    <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
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

    // delete the statues
    $('#incidentStatusesTable').on('click', '.deleteIncidentStatus',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this incident status?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteAllIncidentStatus') }}",
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

    // delete energy photo
    $('#energyIncidentPhotosTable').on('click', '.deleteEnergyIncidentPhoto',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this incident energy photo?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyPhoto') }}",
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

    // delete water photo
    $('#waterIncidentPhotosTable').on('click', '.deleteWaterIncidentPhoto',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this incident water photo?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWaterPhoto') }}",
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

    // delete internet photo
    $('#internetIncidentPhotosTable').on('click', '.deleteInternetIncidentPhoto',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this incident internet photo?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetPhoto') }}",
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

    // delete camera photo
    $('#cameraIncidentPhotosTable').on('click', '.deleteCameraIncidentPhoto',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this incident camera photo?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCameraPhoto') }}",
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


    // delete energy afeected household
    $('#energyAffectedHouseholdsTable').on('click', '.deleteEnergyAffectedHousehold',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this household?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyAffectedHousehold') }}",
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


    // delete water afeected household
    $('#waterAffectedHouseholdsTable').on('click', '.deleteWaterAffectedHousehold',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this household?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWaterAffectedHousehold') }}",
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


    // delete internet afeected household
    $('#internetAffectedHouseholdsTable').on('click', '.deleteInternetAffectedHousehold',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this household?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetAffectedHousehold') }}",
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

    // delete internet affected area
    $('#internetAffectedAreasTable').on('click', '.deleteInternetAffectedAreas',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Area?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetAffectedArea') }}",
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

    // Energy USER/PUBLIC
    function initEnergyEquipmentDamagedHandler(equipmentData = []) {
        let equipmentEnergyDamagedIndex = 1;
        const equipmentEnergyDamagedsData = equipmentData;

        $('#addRemoveEnergyEquipmentsDamagedButton').on('click', function () {
            let options = '<option disabled selected>Choose one...</option>';
            equipmentEnergyDamagedsData.forEach(t => {
                options += `<option value="${t.id}" data-cost="${t.cost}">${t.name}</option>`;
            });

            const newRow = `
                <tr>
                    <td>
                        <select name="energy_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any" name="energy_equipment_damaged_units[${equipmentEnergyDamagedIndex}][subject]"
                            class="form-control" data-energy-equipment-index="${equipmentEnergyDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="energy_equipment_damaged_costs[${equipmentEnergyDamagedIndex}][subject]"
                            class="form-control" data-energy-equipment-index="${equipmentEnergyDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveEnergyEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentEnergyDamagedIndex++;
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        $(document).on('change', 'select[name="energy_equipment_damaged_ids[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="energy_equipment_damaged_costs"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }
        });

        const debounceTimers = {};
        $(document).on('input', '.energy-equipment-units, .energy-equipment-costs', function () {
            const index = $(this).data('energy-equipment-index');
            const unit = parseFloat($(`.energy-equipment-units[data-energy-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.energy-equipment-costs[data-energy-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-energy-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('energy-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-energy-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the equipment
        $('#equipmentEnergyDamagedTable').on('click', '.deleteEquipmentEnergyDamaged',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this energy equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergyEquipmentDamaged') }}",
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
    }

    // Water USER/PUBLIC
    function initWaterEquipmentDamagedHandler(equipmentData = []) {
        let equipmentWaterDamagedIndex = 1;
        const equipmentWaterDamagedsData = equipmentData;

        $('#addRemoveWaterEquipmentsDamagedButton').on('click', function () {
            let options = '<option disabled selected>Choose one...</option>';
            equipmentWaterDamagedsData.forEach(t => {
                options += `<option value="${t.id}" data-cost="${t.cost}">${t.name}</option>`;
            });

            const newRow = `
                <tr>
                    <td>
                        <select name="water_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any" name="water_equipment_damaged_units[${equipmentWaterDamagedIndex}][subject]"
                            class="form-control" data-water-equipment-index="${equipmentWaterDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="water_equipment_damaged_costs[${equipmentWaterDamagedIndex}][subject]"
                            class="form-control" data-water-equipment-index="${equipmentWaterDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveWaterEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentWaterDamagedIndex++;
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        $(document).on('change', 'select[name="water_equipment_damaged_ids[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="water_equipment_damaged_costs"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }
        });

        const debounceTimers = {};
        $(document).on('input', '.water-equipment-units, .water-equipment-costs', function () {
            const index = $(this).data('water-equipment-index');
            const unit = parseFloat($(`.water-equipment-units[data-water-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.water-equipment-costs[data-water-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-water-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('water-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-water-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the equipment
        $('#equipmentWaterDamagedTable').on('click', '.deleteEquipmentWaterDamaged',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this water equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterEquipmentDamaged') }}",
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
    }

    // Internet USER/PUBLIC
    function initInternetEquipmentDamagedHandler(equipmentData = []) {

        let equipmentInternetDamagedIndex = 1;
        const equipmentInternetDamagedsData = equipmentData;

        $('#addRemoveInternetEquipmentsDamagedButton').on('click', function () {
            let options = '<option disabled selected>Choose one...</option>';
            equipmentInternetDamagedsData.forEach(t => {
                options += `<option value="${t.id}"
                    data-cost="${t.cost}">${t.name}</option>`;
            });

            const newRow = `
                <tr>
                    <td>
                        <select name="internet_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any" name="internet_equipment_damaged_units[${equipmentInternetDamagedIndex}][subject]"
                            class="form-control" data-internet-equipment-index="${equipmentInternetDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="internet_equipment_damaged_costs[${equipmentInternetDamagedIndex}][subject]"
                            class="form-control" data-internet-equipment-index="${equipmentInternetDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveInternetEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentInternetDamagedIndex++;
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        $(document).on('change', 'select[name="internet_equipment_damaged_ids[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="internet_equipment_damaged_costs"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }
        });


        const debounceTimers = {};
        $(document).on('input', '.internet-equipment-units, .internet-equipment-costs', function () {
            const index = $(this).data('internet-equipment-index');
            const unit = parseFloat($(`.internet-equipment-units[data-internet-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.internet-equipment-costs[data-internet-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-internet-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('internet-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-internet-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the equipment
        $('#equipmentInternetDamagedTable').on('click', '.deleteEquipmentInternetDamaged',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this internet equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteInternetEquipmentDamaged') }}",
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
    }

    // Camera
    function initCameraEquipmentDamagedHandler(equipmentData = []) {
        let equipmentCameraDamagedIndex = 1;
        const equipmentCameraDamagedsData = equipmentData;

        $('#addRemoveCameraEquipmentsDamagedButton').on('click', function () {
            let options = '<option disabled selected>Choose one...</option>';
            equipmentCameraDamagedsData.forEach(t => {
                options += `<option value="${t.id}">${t.name}</option>`;
            });

            const newRow = `
                <tr>
                    <td>
                        <select name="camera_equipment_damaged_ids[]" class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any" name="camera_equipment_damaged_units[${equipmentCameraDamagedIndex}][subject]"
                            class="form-control" data-camera-equipment-index="${equipmentCameraDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="camera_equipment_damaged_costs[${equipmentCameraDamagedIndex}][subject]"
                            class="form-control" data-camera-equipment-index="${equipmentCameraDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveCameraEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentCameraDamagedIndex++;
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        const debounceTimers = {};
        $(document).on('input', '.camera-equipment-units, .camera-equipment-costs', function () {
            const index = $(this).data('camera-equipment-index');
            const unit = parseFloat($(`.camera-equipment-units[data-camera-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.camera-equipment-costs[data-camera-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-camera-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('camera-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-camera-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the equipment
        $('#equipmentCameraDamagedTable').on('click', '.deleteEquipmentCameraDamaged',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Camera equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCameraEquipmentDamaged') }}",
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
    }


    // ENERGY SYSTEM
    function initEnergySystemEquipmentDamagedHandler(equipmentData = []) {
        let equipmentEnergySystemDamagedIndex = 1;
        const equipmentEnergySystemDamagedsData = equipmentData;

        $('#addRemoveEnergySystemEquipmentsDamagedButton').on('click', function () {
            let options = '<option disabled selected>Choose one...</option>';
            equipmentEnergySystemDamagedsData.forEach(t => {

                options += `<option value="${t.component_energy_system_id}" data-cost="${t.cost}"
                    data-type="${t.type}"> ${t.model_name} (${t.type}) </option>`;

            });

            const newRow = `
                <tr>
                    <td>
                        <select name="energy_system_equipment_damaged_ids[]" 
                            class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                        <input type="hidden" name="energy_system_equipment_types[${equipmentEnergySystemDamagedIndex}][subject]" 
                            class="energy-system-equipment-type-hidden" />
                    </td>
                    <td>
                        <input type="number" step="any" name="energy_system_equipment_damaged_units[${equipmentEnergySystemDamagedIndex}][subject]"
                            class="form-control" data-energy-system-equipment-index="${equipmentEnergySystemDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="energy_system_equipment_damaged_costs[${equipmentEnergySystemDamagedIndex}][subject]"
                            class="form-control" data-energy-system-equipment-index="${equipmentEnergySystemDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveEnergySystemEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentEnergySystemDamagedIndex++;
        });

        $(document).on('change', 'select[name="energy_system_equipment_damaged_ids[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');
            const type = selectedOption.data('type');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="energy_system_equipment_damaged_costs"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }

            // Set hidden type
            row.find('.energy-system-equipment-type-hidden').val(type);
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        const debounceTimers = {};
        $(document).on('input', '.energy-system-equipment-units, .energy-system-equipment-costs', function () {
            const index = $(this).data('energy-system-equipment-index');
            const unit = parseFloat($(`.energy-system-equipment-units[data-energy-system-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.energy-system-equipment-costs[data-energy-system-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-energy-system-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('energy-system-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-energy-system-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the system equipment
        $('#energySystemDamagedEquipmentTable').on('click', '.deleteEnergySystemDamagedEquipment',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this energy system equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergySystemEquipmentDamaged') }}",
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
    }


    // WATER SYSTEM
    function initWaterSystemEquipmentDamagedHandler(equipmentData = []) {
        let equipmentWaterSystemDamagedIndex = 1;
        const equipmentWaterSystemDamagedsData = equipmentData;

        $('#addRemoveWaterSystemEquipmentsDamagedButton').on('click', function () {
            let options = '<option disabled selected>Choose one...</option>';
            equipmentWaterSystemDamagedsData.forEach(t => {

                options += `<option value="${t.component_water_system_id}" data-cost="${t.cost}"
                    data-type="${t.type}"> ${t.model_name} (${t.type}) </option>`;

            });

            const newRow = `
                <tr>
                    <td>
                        <select name="water_system_equipment_damaged_ids[]" 
                            class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                        <input type="hidden" name="water_system_equipment_types[${equipmentWaterSystemDamagedIndex}][subject]" 
                            class="water-system-equipment-type-hidden" />
                    </td>
                    <td>
                        <input type="number" step="any" name="water_system_equipment_damaged_units[${equipmentWaterSystemDamagedIndex}][subject]"
                            class="form-control" data-water-system-equipment-index="${equipmentWaterSystemDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="water_system_equipment_damaged_costs[${equipmentWaterSystemDamagedIndex}][subject]"
                            class="form-control" data-water-system-equipment-index="${equipmentWaterSystemDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveWaterSystemEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentWaterSystemDamagedIndex++;
        });

        $(document).on('change', 'select[name="water_system_equipment_damaged_ids[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');
            const type = selectedOption.data('type');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="water_system_equipment_damaged_costs"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }

            // Set hidden type
            row.find('.water-system-equipment-type-hidden').val(type);
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        const debounceTimers = {};
        $(document).on('input', '.water-system-equipment-units, .water-system-equipment-costs', function () {
            const index = $(this).data('water-system-equipment-index');
            const unit = parseFloat($(`.water-system-equipment-units[data-water-system-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.water-system-equipment-costs[data-water-system-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-water-system-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('water-system-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-water-system-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the system equipment
        $('#waterSystemDamagedEquipmentTable').on('click', '.deleteWaterSystemDamagedEquipment',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Water system equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterSystemEquipmentDamaged') }}",
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
    }


    // INTERNET SYSTEM
    function initInternetSystemEquipmentDamagedHandler(equipmentData = []) {

        let equipmentInternetSystemDamagedIndex = 1;
        const equipmentInternetSystemDamagedsData = equipmentData;

        $('#addRemoveInternetSystemEquipmentsDamagedButton').on('click', function () {

            let options = '<option disabled selected>Choose one...</option>';
            equipmentInternetSystemDamagedsData.forEach(t => {

                options += `<option value="${t.component_internet_system_id}" data-cost="${t.cost}"
                    data-type="${t.type}" data-cabinet-model="${t.cabinet_model}"> 
                    ${t.model_name} (${t.type}) </option>`;

            });

            const newRow = `
                <tr> 
                    <td>
                        <select name="internet_system_equipment_damaged_ids[]" 
                            class="selectpicker form-control" data-live-search="true">
                            ${options}
                        </select>
                        <input type="hidden" name="internet_system_equipment_types[${equipmentInternetSystemDamagedIndex}][subject]" 
                            class="internet-system-equipment-type-hidden" />
                        <input type="hidden" name="equipment_is_cabinet[${equipmentInternetSystemDamagedIndex}]" 
                            class="equipment-cabinet-hidden" />
                    </td>
                    <td>
                        <input type="number" step="any" name="internet_system_equipment_damaged_units[${equipmentInternetSystemDamagedIndex}][subject]"
                            class="form-control" data-internet-system-equipment-index="${equipmentInternetSystemDamagedIndex}">
                    </td>
                    <td>
                        <input type="number" step="any" name="internet_system_equipment_damaged_costs[${equipmentInternetSystemDamagedIndex}][subject]"
                            class="form-control" data-internet-system-equipment-index="${equipmentInternetSystemDamagedIndex}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-row">Delete</button>
                    </td>
                </tr>
            `;

            $('#addRemoveInternetSystemEquipmentsDamaged tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');
            equipmentInternetSystemDamagedIndex++;
        });

        $(document).on('change', 'select[name="internet_system_equipment_damaged_ids[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');
            const type = selectedOption.data('type');
            const isCabinet = selectedOption.data('cabinet-model') ? '1' : '0';

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="internet_system_equipment_damaged_costs"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }

            // Set hidden type
            row.find('.internet-system-equipment-type-hidden').val(type);
            row.find('.equipment-cabinet-hidden').val(isCabinet);
        });

        $(document).on('click', '.remove-input-row', function () {
            $(this).closest('tr').remove();
        });

        const debounceTimers = {};
        $(document).on('input', '.internet-system-equipment-units, .internet-system-equipment-costs', function () {
            
            const index = $(this).data('internet-system-equipment-index');
            const unit = parseFloat($(`.internet-system-equipment-units[data-internet-system-equipment-index="${index}"]`).val()) || 0;
            const cost = parseFloat($(`.internet-system-equipment-costs[data-internet-system-equipment-index="${index}"]`).val()) || 0;
            const total = (unit * cost).toFixed(2);
            $(`#total-internet-system-equipment-${index}`).text(total);

            clearTimeout(debounceTimers[index]);
            debounceTimers[index] = setTimeout(() => {
                const row = $(this).closest('tr');
                const equipmentId = row.data('internet-system-equipment-id');
                if (!equipmentId) return;

                $.ajax({
                    url: `/update-internet-system-equipment/${equipmentId}/${unit}/${cost}`,
                    method: 'GET',
                    success: function (response) {
                        if (response.success === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                confirmButtonText: 'Okay!'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update failed',
                            confirmButtonText: 'Close'
                        });
                    }
                });
            }, 500);
        });

        // delete the system equipment
        $('#internetSystemDamagedEquipmentTable').on('click', '.deleteInternetSystemDamagedEquipment',function() {
            var id = $(this).data('id');
            var $ele = $(this).parent().parent();

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Internet system equipment?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteInternetSystemEquipmentDamaged') }}",
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
    }

    // Pass PHP data to JS and call the function for Energy equipment damaged (USER/PUBLIC)
    const energyEquipmentsFromServer = @json($userPublicEnergyEquipments->where('incident_equipment_type_id', 2)->values());
    initEnergyEquipmentDamagedHandler(energyEquipmentsFromServer);

    // Pass PHP data to JS and call the function for Water equipment damaged (USER/PUBLIC)
    const waterEquipmentsFromServer = @json($userPublicEnergyEquipments->where('incident_equipment_type_id', 1)->values());
    initWaterEquipmentDamagedHandler(waterEquipmentsFromServer);

    // Pass PHP data to JS and call the function for Internet equipment damaged (USER/PUBLIC)
    const internetEquipmentsFromServer = @json($userPublicEnergyEquipments->where('incident_equipment_type_id', 4)->values());
    initInternetEquipmentDamagedHandler(internetEquipmentsFromServer);

    // Pass PHP data to JS and call the function for Camera equipment damaged
    const cameraEquipmentsFromServer = @json($userPublicEnergyEquipments->where('incident_equipment_type_id', 5)->values());
    initCameraEquipmentDamagedHandler(cameraEquipmentsFromServer);

    // Here are the code to pass data for the systems

    // Pass PHP data to JS and call the function for Energy equipment damaged (SYSTEM)
    const energySystemEquipmentsFromServer = @json($energySystemComponents);
    initEnergySystemEquipmentDamagedHandler(energySystemEquipmentsFromServer);


    // Pass PHP data to JS and call the function for Water equipment damaged (SYSTEM)
    const waterSystemEquipmentsFromServer = @json($waterSystemComponents);
    initWaterSystemEquipmentDamagedHandler(waterSystemEquipmentsFromServer);

    // Pass PHP data to JS and call the function for Internet equipment damaged (SYSTEM)
    const internetSystemEquipmentsFromServer = @json($internetSystemComponents);
    initInternetSystemEquipmentDamagedHandler(internetSystemEquipmentsFromServer);
</script>


@endsection