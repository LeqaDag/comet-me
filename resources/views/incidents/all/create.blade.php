@extends('layouts/layoutMaster')

@section('title', 'create incident')

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
  <span class="text-muted fw-light">Add </span> New Incident
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('all-incident')}}" id="allIncidentForm"
                enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communitySelected">
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="community_id_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Incident Type</label>
                            <select name="incident_id" class="selectpicker form-control" 
                                id="incidentTypeSelected" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($incidents as $incident)
                                <option value="{{$incident->id}}">
                                    {{$incident->english_name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="incident_id_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Service Types</label>
                            <select name="service_type_ids[]" class="selectpicker form-control"
                                data-live-search="true" id="serviceTypeSelected" multiple>
                                <option disabled selected>Choose one...</option>
                                @foreach($serviceTypes as $serviceType)
                                    <option value="{{$serviceType->id}}">
                                        {{$serviceType->service_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="service_type_ids_error" style="color: red;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Incident</label>
                            <input type="date" name="date" class="form-control" required>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Incident Attack Type</label>
                            <select name="all_incident_impact_type_id" class="selectpicker form-control">
                                <option disabled selected>Choose one...</option>
                                @foreach($impactTypes as $impactType)
                                <option value="{{$impactType->id}}">
                                    {{$impactType->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row" id="energyIncidentsDiv" style="margin-top:20px"> 

                    <hr>
                    <h6>Energy Incidents</h6>

                    <div class="row"> 
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Statuses</label>
                                <select name="energy_incident_status_ids[]"
                                    class="selectpicker form-control" id="energyIncidentStatusSelected" 
                                        data-live-search="true" required multiple>
                                </select>
                            </fieldset>
                            <div id="energy_incident_status_ids_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy System/ Holder</label>
                                <select id="chooseEnergyHolderSystem" class="selectpicker form-control" 
                                    name="energy_system_holder" data-live-search="true" disabled required>
                                </select>
                            </fieldset>
                            <div id="energy_system_holder_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Holder/System</label>
                                <select name="energy_holder_system" class="selectpicker form-control" 
                                    id="energyHolderSelected" data-live-search="true" disabled>
                                </select>
                            </fieldset>
                            <div id="energy_holder_system_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Affected Households</label>
                                <select name="affected_households[]" class="selectpicker form-control" 
                                    id="affectedHouseholds" data-live-search="true" disabled multiple>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Response Date</label>
                                <input type="date" name="energy_response_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row" style="margin-top:20px">
                        <label>Equipment Damaged</label>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveEnergyEquipment">
                                <tbody></tbody>
                            </table>
                            <button type="button" name="add" id="addEnergyEquipmentButton" class="btn btn-outline-primary mt-2" style="display:none;">
                                Add More
                            </button>
                        </div>
                    </div>

                    <div div="row" id="energyIncidentsSWO" style="display:none; visiblity: none">
                        <label for="">SWO for Energy</label>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Number</label>
                                    <input type="number" name="energy_order_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Date</label>
                                    <input type="date" name="energy_order_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Lat</label>
                                    <input type="text" name="energy_geolocation_lat" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Long</label>
                                    <input type="text" name="energy_geolocation_long" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Date of hearing</label>
                                    <input type="date" name="energy_hearing_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                    <input type="text" name="energy_building_permit_request_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                    <input type="date" name="energy_building_permit_request_submission_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                    <input type="text" name="energy_illegal_construction_case_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>District Court Case Number</label>
                                    <input type="text" name="energy_district_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                    <input type="text" name="energy_supreme_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Description of structure</label>
                                    <textarea name="energy_structure_description" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Case Chronology</label>
                                    <textarea name="energy_case_chronology" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="energy_notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload Energy Incident Photos</label>
                            <input type="file" name="energy_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                </div>

                <div class="row" id="waterIncidentsDiv" style="margin-top:20px"> 

                    <hr>
                    <h6>Water Incidents</h6>

                    <div class="row"> 
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Statuses</label>
                                <select name="water_incident_status_ids[]"
                                    class="selectpicker form-control" id="waterIncidentStatusSelected" 
                                        data-live-search="true" multiple>
                                </select>
                            </fieldset>
                            <div id="water_incident_status_ids_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System/ Holder</label>
                                <select id="chooseWaterHolderSystem" class="selectpicker form-control" 
                                    name="water_system_holder" data-live-search="true" disabled required>
                                </select>
                            </fieldset>
                            <div id="water_system_holder_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Holder/System</label>
                                <select name="water_holder_system" class="selectpicker form-control" 
                                    id="waterHolderSelected" data-live-search="true" disabled>
                                </select>
                            </fieldset>
                            <div id="water_holder_system_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Affected Households</label>
                                <select name="water_affected_households[]" class="selectpicker form-control" 
                                    id="waterAffectedHouseholds" data-live-search="true" disabled multiple>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Response Date</label>
                                <input type="date" name="water_response_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row" style="margin-top:20px">
                        <label>Equipment Damaged</label>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveWaterEquipment">
                                <tbody></tbody>
                            </table>
                            <button type="button" name="add" id="addWaterEquipmentButton" class="btn btn-outline-primary mt-2" style="display:none;">
                                Add More
                            </button>
                        </div>
                    </div>

                    <div div="row" id="waterIncidentsSWO" style="display:none; visiblity: none">
                        <label for="">SWO for Water</label>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Number</label>
                                    <input type="number" name="water_order_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Date</label>
                                    <input type="date" name="water_order_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Lat</label>
                                    <input type="text" name="water_geolocation_lat" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Long</label>
                                    <input type="text" name="water_geolocation_long" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Date of hearing</label>
                                    <input type="date" name="water_hearing_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                    <input type="text" name="water_building_permit_request_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                    <input type="date" name="water_building_permit_request_submission_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                    <input type="text" name="water_illegal_construction_case_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>District Court Case Number</label>
                                    <input type="text" name="water_district_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                    <input type="text" name="water_supreme_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Description of structure</label>
                                    <textarea name="water_structure_description" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Case Chronology</label>
                                    <textarea name="water_case_chronology" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="water_notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload Water Incident Photos</label>
                            <input type="file" name="water_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>

                </div>

                <div class="row" id="internetIncidentsDiv" style="margin-top:20px">  

                    <hr>
                    <h6>Internet Incidents</h6>

                    <div class="row"> 
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Statuses</label>
                                <select name="internet_incident_status_ids[]" 
                                    class="selectpicker form-control" id="internetIncidentStatusSelected" 
                                        data-live-search="true" multiple>
                                </select>
                            </fieldset>
                            <div id="internet_incident_status_ids_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet System/ Holder</label>
                                <select id="chooseInternetHolderSystem" class="selectpicker form-control" 
                                    name="internet_system_holder" data-live-search="true" disabled required>
                                </select>
                            </fieldset>
                            <div id="internet_system_holder_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Holder/System</label>
                                <select name="internet_holder_system" class="selectpicker form-control" 
                                    id="internetHolderSelected" data-live-search="true" disabled>
                                </select>
                            </fieldset>
                            <div id="internet_holder_system_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Affected Households</label>
                                <select name="internet_affected_households[]" class="selectpicker form-control" 
                                    id="internetAffectedHouseholds" data-live-search="true" disabled multiple>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Affected Areas</label>
                                <select name="internet_affected_areas[]" class="selectpicker form-control" 
                                    id="internetAffectedAreas" data-live-search="true" disabled multiple>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Response Date</label>
                                <input type="date" name="internet_response_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row" style="margin-top:20px">
                        <label>Equipment Damaged</label>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveInternetEquipment">
                                <tbody></tbody>
                            </table>
                            <button type="button" name="add" id="addInternetEquipmentButton" class="btn btn-outline-primary mt-2" style="display:none;">
                                Add More
                            </button>
                        </div>
                    </div>

                    <div div="row" id="internetIncidentsSWO" style="display:none; visiblity: none">
                        <label for="">SWO for Internet</label>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Number</label>
                                    <input type="number" name="internet_order_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Date</label>
                                    <input type="date" name="internet_order_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Lat</label>
                                    <input type="text" name="internet_geolocation_lat" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Long</label>
                                    <input type="text" name="internet_geolocation_long" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Date of hearing</label>
                                    <input type="date" name="internet_hearing_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                    <input type="text" name="internet_building_permit_request_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                    <input type="date" name="internet_building_permit_request_submission_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                    <input type="text" name="internet_illegal_construction_case_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>District Court Case Number</label>
                                    <input type="text" name="internet_district_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                    <input type="text" name="internet_supreme_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Description of structure</label>
                                    <textarea name="internet_structure_description" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Case Chronology</label>
                                    <textarea name="internet_case_chronology" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="internet_notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload Internet Incident Photos</label>
                            <input type="file" name="internet_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>

                </div>

                <div class="row" id="cameraIncidentsDiv" style="margin-top:20px">  

                    <hr>
                    <h6>Camera Incidents</h6>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Statuses</label>
                                <select name="camera_incident_status_ids[]" disabled
                                    class="selectpicker form-control" id="cameraIncidentStatusSelected" 
                                        data-live-search="true" multiple>
                                </select>
                            </fieldset>
                            <div id="camera_incident_status_ids_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Response Date</label>
                                <input type="date" name="camera_response_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px">
                        <label>Equipment Damaged</label>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="dynamicAddRemoveCameraEquipment">
                                <tr>
                                    <th>Equipment</th>
                                    <th>Unit</th>
                                    <th>Cost</th>
                                    <th>Options</th>
                                </tr> 
                                <tr> 
                                    <td  style="white-space: nowrap; width: 300px;">
                                        <select class="selectpicker form-control" 
                                            data-live-search="true" name="camera_equipment[]">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($cameraEquipments as $cameraEquipment)
                                            <option value="{{$cameraEquipment->id}}">
                                                {{$cameraEquipment->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div id="camera_equipment_error" style="color: red;"></div>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsCameraUnit[0][subject]" 
                                        placeholder="Unit" class="target_point form-control" 
                                        data-id="0"/>
                                    </td>
                                    <td>
                                        <input type="text" name="addMoreInputFieldsCameraCost[0][subject]" 
                                        placeholder="Cost" class="target_point form-control" 
                                        data-id="0"/>
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addCameraEquipmentButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div div="row" id="cameraIncidentsSWO" style="display:none; visiblity: none">
                        <label for="">SWO for Camera</label>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Number</label>
                                    <input type="number" name="camera_order_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Date</label>
                                    <input type="date" name="camera_order_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Lat</label>
                                    <input type="text" name="camera_geolocation_lat" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Long</label>
                                    <input type="text" name="camera_geolocation_long" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Date of hearing</label>
                                    <input type="date" name="camera_hearing_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                    <input type="text" name="camera_building_permit_request_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                    <input type="date" name="camera_building_permit_request_submission_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                    <input type="text" name="camera_illegal_construction_case_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>District Court Case Number</label>
                                    <input type="text" name="camera_district_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                    <input type="text" name="camera_supreme_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Description of structure</label>
                                    <textarea name="camera_structure_description" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Case Chronology</label>
                                    <textarea name="camera_case_chronology" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="camera_notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload Camera Incident Photos</label>
                            <input type="file" name="camera_photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
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

    $('#energyIncidentsDiv').hide();
    $('#waterIncidentsDiv').hide();
    $('#internetIncidentsDiv').hide();
    $('#cameraIncidentsDiv').hide();

    $(document).ready(function() {

        function handleIncidentAndServiceChange() {
            
            var incident_id = $('#incidentTypeSelected').val();
            var service_ids = $('#serviceTypeSelected').val(); 

            // Check if incident is SWO and service type includes Energy
            if (incident_id === "4" && service_ids && service_ids.includes("1")) $("#energyIncidentsSWO").show();
            else $("#energyIncidentsSWO").hide();
           
            // Check if incident is SWO and service type includes Water
            if (incident_id === "4" && service_ids && service_ids.includes("2")) $("#waterIncidentsSWO").show();
            else $("#waterIncidentsSWO").hide();
           
           // Check if incident is SWO and service type includes Internet
           if (incident_id === "4" && service_ids && service_ids.includes("3")) $("#internetIncidentsSWO").show();
           else $("#internetIncidentsSWO").hide();
           
           // Check if incident is SWO and service type includes Camera
           if (incident_id === "4" && service_ids && service_ids.includes("4")) $("#cameraIncidentsSWO").show();
           else $("#cameraIncidentsSWO").hide();

        }

        $(document).on('change', '#incidentTypeSelected', handleIncidentAndServiceChange);
        $(document).on('change', '#serviceTypeSelected', handleIncidentAndServiceChange);
        
        
        $('#allIncidentForm').on('submit', function (event) {

            event.preventDefault(); 

            let valid = true;

            var communityValue = $('#communitySelected').val();
            var incidentTypeValue = $('#incidentTypeSelected').val();
            var serviceTypeValues = $('#serviceTypeSelected').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else  if (communityValue != null) {

                $('#community_id_error').empty();
            }

            if (incidentTypeValue == null) {

                $('#incident_id_error').html('Please select an incident type!'); 
                return false;
            } else  if (incidentTypeValue != null) {

                $('#incident_id_error').empty();
            }

            if (!serviceTypeValues || serviceTypeValues.length === 0) {

                $('#service_type_ids_error').html('Please select at least one service type!');
                valid = false;
                return false;
            } else {

                $('#service_type_ids_error').empty();
            }


            $('#community_id_error').empty();
            $('#incident_id_error').empty();
            $('#service_type_ids_error').empty();

            if (valid) {

                $(this).addClass('was-validated');
                this.submit(); // submit the form
            }
        });

        // This event handles the change of #communitySelected
        $(document).on('change', '#communitySelected', function () {

            var community_id = $(this).val();
            $('#energyHolderSelected').empty();
            $('#chooseEnergyHolderSystem').prop('disabled', false);
            $('#chooseEnergyHolderSystem').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option><option value="system">Energy System</option>');
            $('#chooseEnergyHolderSystem').selectpicker('refresh');
            
            $('#waterHolderSelected').empty();
            $('#chooseWaterHolderSystem').prop('disabled', false);
            $('#chooseWaterHolderSystem').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option><option value="system">Water System</option>');
            $('#chooseWaterHolderSystem').selectpicker('refresh');

            $('#internetHolderSelected').empty();
            $('#chooseInternetHolderSystem').prop('disabled', false);
            $('#chooseInternetHolderSystem').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option><option value="system">Internet System</option>');
            $('#chooseInternetHolderSystem').selectpicker('refresh');
        });

        // This event handles the change of #incidentTypeSelected
        $(document).on('change', '#incidentTypeSelected', function () {

            var incident_id = $('#incidentTypeSelected').val();

            var select = $('#energyIncidentStatusSelected');
            select.prop('disabled', false);
            select.empty(); 
            select.selectpicker('refresh');

            var selectWater = $('#waterIncidentStatusSelected');
            selectWater.prop('disabled', false);
            selectWater.empty(); 
            selectWater.selectpicker('refresh');

            var selectInternet = $('#internetIncidentStatusSelected');
            selectInternet.prop('disabled', false);
            selectInternet.empty(); 
            selectInternet.selectpicker('refresh');

            var selectCamera = $('#cameraIncidentStatusSelected');
            selectCamera.prop('disabled', false);
            selectCamera.empty(); 
            selectCamera.selectpicker('refresh');

            $.ajax({
                url: "/all-incident/get_incident_statuses/" + incident_id,
                method: 'GET',
                success: function(data) {

                    select.html(data.html);
                    select.selectpicker('refresh');

                    selectWater.html(data.html);
                    selectWater.selectpicker('refresh');

                    selectInternet.html(data.html);
                    selectInternet.selectpicker('refresh');

                    selectCamera.html(data.html);
                    selectCamera.selectpicker('refresh');
                }
            });
        });

    

        // This for choosing the system
        $(document).on('change', '#energyHolderSelected', function() {

            const systemId = $(this).val();
            const agent = $("#chooseEnergyHolderSystem").val();

            if (!systemId) {

                $('#dynamicAddRemoveEnergyEquipment tbody').empty();
                $('#addEnergyEquipmentButton').hide();
                return;
            }

            if (agent == "system") {
                $.ajax({ 
                    url: `/energy-systems/${systemId}/components`,
                    method: 'GET',
                    success: function(response) {

                        currentEquipmentData = response.equipment || [];
                        $('#dynamicAddRemoveEnergyEquipment tbody').empty();

                        if (currentEquipmentData.length) {

                            appendEnergyEquipmentRow(currentEquipmentData, agent);
                            $('#addEnergyEquipmentButton').show();
                        } else {

                            $('#addEnergyEquipmentButton').hide();
                        }
                    },
                    error: function() {
                        
                        alert('Failed to load components for selected system');
                        $('#addEnergyEquipmentButton').hide();
                    }
                });
            }
        });

        // Add More Energy Equipment
        let energyRowIndex = 0; 
        let energyHolderIndex = 0;
        function appendEnergyEquipmentRow(equipmentList, agent) {

            let options = '<option disabled selected>Choose one...</option>';

            if(agent === "system") {

                for (const item of equipmentList) {

                    options += `<option value="${item.component_energy_system_id}" data-cost="${item.cost}"
                        data-type="${item.type}"> ${item.model_name} (${item.type}) </option>`;
                }

                const newRow = `
                    <tr>
                        <td style="white-space: nowrap; width: 300px;">
                            <select class="selectpicker form-control" data-live-search="true" name="energy_equipment[]">
                                ${options}
                            </select>
                            <input type="hidden" name="equipment_type[${energyRowIndex}]" class="equipment-type-hidden" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsEnergyUnit[${energyRowIndex}][subject]" 
                                placeholder="Unit" class="form-control" data-id="${energyRowIndex}" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsEnergyCost[${energyRowIndex}][subject]" 
                                placeholder="Cost" class="form-control" data-id="${energyRowIndex}" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-energy-target-points">Delete</button>
                        </td>
                    </tr>
                `;

                $('#dynamicAddRemoveEnergyEquipment tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh');
                energyRowIndex++;
            } else {

                for (const item of equipmentList) {
                    options += `<option value="${item.id}">
                        ${item.name}</option>`;
                }

                const newRow = `
                    <tr>
                        <td style="white-space: nowrap; width: 300px;">
                            <select class="selectpicker form-control" data-live-search="true" name="energy_equipment[]">
                                ${options}
                            </select>
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsEnergyUnit[${energyHolderIndex}][subject]" 
                                placeholder="Unit" class="form-control" data-id="${energyHolderIndex}" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsEnergyCost[${energyHolderIndex}][subject]" 
                                placeholder="Cost" class="form-control" data-id="${energyHolderIndex}" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-energy-target-points">Delete</button>
                        </td>
                    </tr>
                `;

                $('#dynamicAddRemoveEnergyEquipment tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh');
                energyHolderIndex++;
            }
        }

        $(document).on('change', 'select[name="energy_equipment[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');
            const type = selectedOption.data('type');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="addMoreInputFieldsEnergyCost"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }

            // Set hidden type
            type = row.find('.equipment-type-hidden').val(type);
        });


        $(document).on('click', '#addEnergyEquipmentButton', function () {

            const agent = $("#chooseEnergyHolderSystem").val(); 
            if(currentEquipmentData.length > 0) {

                appendEnergyEquipmentRow(currentEquipmentData, agent);
            } else {

                alert("Please select a system or user first.");
            }
        });


        $(document).on('click', '.remove-input-energy-target-points', function () {
            $(this).closest('tr').remove();
        });

        // This event handles the change of #chooseEnergyHolderSystem
        let currentEquipmentData = [];  // to store equipment list for current selection

        $(document).on('change', '#chooseEnergyHolderSystem', function () {

            var community_id = $('#communitySelected').val();
            var publicUserSystem = $(this).val();

            $('#addEnergyEquipmentButton').hide(); // Hide Add button
            $('#dynamicAddRemoveEnergyEquipment tbody').empty(); // Clear table

            var selectAffectedHouseholds = $('#affectedHouseholds');
            selectAffectedHouseholds.empty().prop('disabled', true).selectpicker('refresh');

            var select = $('#energyHolderSelected');
            select.prop('disabled', false).empty().selectpicker('refresh');

            $.ajax({
                url: "/all-incident/get_energy_holder/" + community_id + "/" + publicUserSystem,
                method: 'GET',
                success: function(data) {
                    select.html(data.html).selectpicker('refresh');

                    if(publicUserSystem === "system") {

                        selectAffectedHouseholds.prop('disabled', false);
                        selectAffectedHouseholds.html(data.htmlAffectedHouseholds).selectpicker('refresh');
                    } else {
                        // Only for user/public: load general equipment list now
                        currentEquipmentData = data.userPublicEquipments || [];
                        if (currentEquipmentData.length) {

                            appendEnergyEquipmentRow(currentEquipmentData, "user/public");
                            $('#addEnergyEquipmentButton').show();
                        }
                    }
                }
            });
        });



        // This for choosing the water system
        $(document).on('change', '#waterHolderSelected', function() {

            const systemId = $(this).val();
            const agent = $("#chooseWaterHolderSystem").val();

            if (!systemId) {

                $('#dynamicAddRemoveWaterEquipment tbody').empty();
                $('#addWaterEquipmentButton').hide();
                return;
            }

            if (agent == "system") {
                $.ajax({ 
                    url: `/water-systems/${systemId}/components`,
                    method: 'GET',
                    success: function(response) {

                        currentWaterEquipmentData = response.equipmentWater || [];
                        $('#dynamicAddRemoveWaterEquipment tbody').empty();

                        if (currentWaterEquipmentData.length) {

                            appendWaterEquipmentRow(currentWaterEquipmentData, agent);
                            $('#addWaterEquipmentButton').show();
                        } else {

                            $('#addWaterEquipmentButton').hide();
                        }
                    },
                    error: function() {
                        
                        alert('Failed to load components for selected system');
                        $('#addWaterEquipmentButton').hide();
                    }
                });
            }
        });

        // Add More Water Equipment
        let waterRowIndex = 0; 
        let waterHolderIndex = 0;
        function appendWaterEquipmentRow(equipmentList, agent) {

            let options = '<option disabled selected>Choose one...</option>';

            if(agent === "system") {

                for (const item of equipmentList) {

                    options += `<option value="${item.component_water_system_id}" data-cost="${item.cost}"
                        data-type="${item.type}"> ${item.model_name} (${item.type}) </option>`;
                }

                const newRow = `
                    <tr>
                        <td style="white-space: nowrap; width: 300px;">
                            <select class="selectpicker form-control" data-live-search="true" name="water_equipment[]">
                                ${options}
                            </select>
                            <input type="hidden" name="equipment_type[${waterRowIndex}]" class="equipment-type-hidden" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsWaterUnit[${waterRowIndex}][subject]" 
                                placeholder="Unit" class="form-control" data-id="${waterRowIndex}" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsWaterCost[${waterRowIndex}][subject]" 
                                placeholder="Cost" class="form-control" data-id="${waterRowIndex}" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-water-target-points">Delete</button>
                        </td>
                    </tr>
                `;

                $('#dynamicAddRemoveWaterEquipment tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh');
                waterRowIndex++;
            } else {

                for (const item of equipmentList) {
                    options += `<option value="${item.id}">
                        ${item.name}</option>`;
                }

                const newRow = `
                    <tr>
                        <td style="white-space: nowrap; width: 300px;">
                            <select class="selectpicker form-control" data-live-search="true" name="water_equipment[]">
                                ${options}
                            </select>
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsWaterUnit[${waterHolderIndex}][subject]" 
                                placeholder="Unit" class="form-control" data-id="${waterHolderIndex}" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsWaterCost[${waterHolderIndex}][subject]" 
                                placeholder="Cost" class="form-control" data-id="${waterHolderIndex}" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-water-target-points">Delete</button>
                        </td>
                    </tr>
                `;

                $('#dynamicAddRemoveWaterEquipment tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh');
                waterHolderIndex++;
            }
        }

        $(document).on('change', 'select[name="water_equipment[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');
            const type = selectedOption.data('type');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="addMoreInputFieldsWaterCost"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }

            // Set hidden type
            type = row.find('.equipment-type-hidden').val(type);
        });


        $(document).on('click', '#addWaterEquipmentButton', function () {

            const agent = $("#chooseWaterHolderSystem").val(); 
            if(currentWaterEquipmentData.length > 0) {

                appendWaterEquipmentRow(currentWaterEquipmentData, agent);
            } else {

                alert("Please select a system or user first.");
            }
        });


        $(document).on('click', '.remove-input-water-target-points', function () {
            $(this).closest('tr').remove();
        });

        // This event handles the change of #chooseWaterHolderSystem
        let currentWaterEquipmentData = [];  // to store equipment list for current selection

        $(document).on('change', '#chooseWaterHolderSystem', function () {

            var community_id = $('#communitySelected').val();
            var publicUserSystem = $(this).val();

            $('#addWaterEquipmentButton').hide(); // Hide Add button
            $('#dynamicAddRemoveWaterEquipment tbody').empty(); // Clear table

            var selectAffectedHouseholds = $('#waterAffectedHouseholds');
            selectAffectedHouseholds.empty().prop('disabled', true).selectpicker('refresh');

            var select = $('#waterHolderSelected');
            select.prop('disabled', false).empty().selectpicker('refresh');

            $.ajax({
                url: "/all-incident/get_water_holder/" + community_id + "/" + publicUserSystem,
                method: 'GET',
                success: function(data) {

                    select.html(data.html).selectpicker('refresh');

                    if(publicUserSystem === "system") {

                        selectAffectedHouseholds.prop('disabled', false);
                        selectAffectedHouseholds.html(data.htmlAffectedHouseholds).selectpicker('refresh');
                    } else {
                        // Only for user/public: load general equipment list now
                        currentWaterEquipmentData = data.userPublicEquipments || [];
                        if (currentWaterEquipmentData.length) {

                            appendWaterEquipmentRow(currentWaterEquipmentData, "user/public");
                            $('#addWaterEquipmentButton').show();
                        }
                    }
                }
            });
        });

 
        // This for choosing the internet system
        $(document).on('change', '#internetHolderSelected', function() {

            const systemId = $(this).val();
            const agent = $("#chooseInternetHolderSystem").val();

            if (!systemId) {

                $('#dynamicAddRemoveInternetEquipment tbody').empty();
                $('#addInternetEquipmentButton').hide();
                return;
            }

            if (agent == "system") {
                $.ajax({ 
                    url: `/internet-systems/${systemId}/components`,
                    method: 'GET',
                    success: function(response) {

                        currentInternetEquipmentData = response.equipmentInternet || [];
                        $('#dynamicAddRemoveInternetEquipment tbody').empty();

                        if (currentInternetEquipmentData.length) {

                            appendInternetEquipmentRow(currentInternetEquipmentData, agent);
                            $('#addInternetEquipmentButton').show();
                        } else {

                            $('#addInternetEquipmentButton').hide();
                        }
                    },
                    error: function() {
                        
                        alert('Failed to load components for selected system');
                        $('#addInternetEquipmentButton').hide();
                    }
                });
            }
        });

        // Add More internet Equipment
        let internetRowIndex = 0; 
        let internetHolderIndex = 0;
        function appendInternetEquipmentRow(equipmentList, agent) {

            let options = '<option disabled selected>Choose one...</option>';

            if(agent === "system") {

                for (const item of equipmentList) { 

                    options += `<option value="${item.component_internet_system_id}" data-cost="${item.cost}"
                        data-type="${item.type}" data-is-cabinet="${item.cabinet_model}"
                        > ${item.model_name} (${item.type}) </option>`;
                }

                const newRow = `
                    <tr>
                        <td style="white-space: nowrap; width: 300px;">
                            <select class="selectpicker form-control" data-live-search="true" name="internet_equipment[]">
                                ${options}
                            </select>
                            <input type="hidden" name="equipment_type[${internetRowIndex}]" class="equipment-type-hidden" />
                            <input type="hidden" name="equipment_is_cabinet[${internetRowIndex}]" 
                                class="equipment-cabinet-hidden" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsInternetUnit[${internetRowIndex}][subject]" 
                                placeholder="Unit" class="form-control" data-id="${internetRowIndex}" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsInternetCost[${internetRowIndex}][subject]" 
                                placeholder="Cost" class="form-control" data-id="${internetRowIndex}" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-internet-target-points">Delete</button>
                        </td>
                    </tr>
                `;

                $('#dynamicAddRemoveInternetEquipment tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh');
                internetRowIndex++;
            } else {

                for (const item of equipmentList) {

                    const isCabinet = item.cabinet_model !== null;

                    options += `<option 
                        value="${item.id}" 
                        data-cost="${item.cost}" >
                        ${item.name} 
                    </option>`;
                }

                const newRow = `
                    <tr>
                        <td style="white-space: nowrap; width: 300px;">
                            <select class="selectpicker form-control" data-live-search="true" name="internet_equipment[]">
                                ${options}
                            </select>
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsInternetUnit[${internetHolderIndex}][subject]" 
                                placeholder="Unit" class="form-control" data-id="${internetHolderIndex}" />
                        </td>
                        <td>
                            <input type="text" step="any" name="addMoreInputFieldsInternetCost[${internetHolderIndex}][subject]" 
                                placeholder="Cost" class="form-control" data-id="${internetHolderIndex}" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-internet-target-points">Delete</button>
                        </td>
                    </tr>
                `;

                $('#dynamicAddRemoveInternetEquipment tbody').append(newRow);
                $('.selectpicker').selectpicker('refresh');
                internetHolderIndex++;
            }
        }

        $(document).on('change', 'select[name="internet_equipment[]"]', function () {

            const selectedOption = $(this).find('option:selected');
            const cost = selectedOption.data('cost');
            const type = selectedOption.data('type');
            const isCabinet = selectedOption.data('is-cabinet');

            const row = $(this).closest('tr');

            // Find the closest <tr> and the corresponding cost input
            const costInput = row.find('input[name^="addMoreInputFieldsInternetCost"]');

            if (cost !== undefined) {
                costInput.val(cost);
            }

            // Set hidden type
            row.find('.equipment-type-hidden').val(type);
            row.find('.equipment-cabinet-hidden').val(isCabinet);
        });


        $(document).on('click', '#addInternetEquipmentButton', function () {

            const agent = $("#chooseInternetHolderSystem").val(); 
            if(currentInternetEquipmentData.length > 0) {

                appendInternetEquipmentRow(currentInternetEquipmentData, agent);
            } else {

                alert("Please select a system or user first.");
            }
        });


        $(document).on('click', '.remove-input-internet-target-points', function () {
            $(this).closest('tr').remove();
        });

        // This event handles the change of #chooseInternetHolderSystem
        let currentInternetEquipmentData = [];  // to store equipment list for current selection

        $(document).on('change', '#chooseInternetHolderSystem', function () {

            var community_id = $('#communitySelected').val();
            var publicUserSystem = $(this).val();
 
            $('#addInternetEquipmentButton').hide(); // Hide Add button
            $('#dynamicAddRemoveInternetEquipment tbody').empty(); // Clear table

            var selectAffectedHouseholds = $('#internetAffectedHouseholds');
            selectAffectedHouseholds.empty().prop('disabled', true).selectpicker('refresh');

            var selectAffectedAreas = $('#internetAffectedAreas');
            selectAffectedAreas.empty().prop('disabled', true).selectpicker('refresh');

            var select = $('#internetHolderSelected');
            select.prop('disabled', false).empty().selectpicker('refresh');

            $.ajax({
                url: "/all-incident/get_internet_holder/" + community_id + "/" + publicUserSystem,
                method: 'GET',
                success: function(data) {

                    select.html(data.html).selectpicker('refresh');

                    if(publicUserSystem === "system") {

                        select.prop('disabled', false);
                        select.html(data.html).selectpicker('refresh');

                        selectAffectedHouseholds.prop('disabled', false);
                        selectAffectedHouseholds.html(data.htmlAffectedHouseholds).selectpicker('refresh');

                        selectAffectedAreas.prop('disabled', false);
                        selectAffectedAreas.html(data.htmlAffectedAreas).selectpicker('refresh');
                    } else {
                        // Only for user/public: load general equipment list now
                        currentInternetEquipmentData = data.userPublicEquipments || [];
                        if (currentInternetEquipmentData.length) {

                            appendInternetEquipmentRow(currentInternetEquipmentData, "user/public");
                            $('#addInternetEquipmentButton').show();
                        }
                    }
                }
            });
        });


        // This code is to open the selected div while selecting the service type
        $(document).on('change', '#serviceTypeSelected', function () {

            var selectedValues = $(this).val();

            // First, hide all divs
            $('#energyIncidentsDiv').hide();
            $('#waterIncidentsDiv').hide();
            $('#internetIncidentsDiv').hide();
            $('#cameraIncidentsDiv').hide();

            if (selectedValues.includes("1")) {

                $('#energyIncidentsDiv').show();
            }
            if (selectedValues.includes("2")) {

                $('#waterIncidentsDiv').show();
            }
            if (selectedValues.includes("3")) {

                $('#internetIncidentsDiv').show();
            }
            if (selectedValues.includes("4")) {

                $('#cameraIncidentsDiv').show();
            }

        });


 
        // Add More Camera Equipment
        let y = 1; 
        const cameraEquipments = {!! json_encode($cameraEquipments) !!};
        $('#addCameraEquipmentButton').click(function () {
            let options = '<option disabled selected>Choose one...</option>';
            for (const key in cameraEquipments) {
                options += `<option value="${cameraEquipments[key].id}">${cameraEquipments[key].name}</option>`;
            }

            const newRow = `
                <tr>
                    <td style="white-space: nowrap; width: 300px;">
                        <select class="selectpicker form-control" data-live-search="true" name="camera_equipment[]">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="addMoreInputFieldsCameraUnit[${y}][subject]" 
                            placeholder="Unit" class="form-control" data-id="${y}" />
                    </td>
                    <td>
                        <input type="text" name="addMoreInputFieldsCameraCost[${y}][subject]" 
                            placeholder="Cost" class="form-control" data-id="${y}" />
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-danger remove-input-camera-target-points">Delete</button>
                    </td>
                </tr>
            `;

            $('#dynamicAddRemoveCameraEquipment tbody').append(newRow);
            $('.selectpicker').selectpicker('refresh');

            y++; 
        });
        $(document).on('click', '.remove-input-camera-target-points', function () {

            $(this).parents('tr').remove();
        });

    });
</script>
@endsection