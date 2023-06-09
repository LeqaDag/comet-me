@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit household')

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
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery</label>
                            <select name="battery_type_id" class="form-control">
                                @if($fbsSystem->battery_type_id)
                                    <option value="{{$fbsSystem->EnergyBattery->id}}" disabled selected>
                                        {{$fbsSystem->EnergyBattery->battery_model}}
                                    </option>
                                    @foreach($batteries as $battery)
                                    <option value="{{$battery->id}}">
                                        {{$battery->battery_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($batteries as $battery)
                                    <option value="{{$battery->id}}">
                                        {{$battery->battery_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of batteries</label> 
                            <input type="number" class="form-control" name="battery_units"
                                value="{{$fbsSystem->battery_units}}"> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Solar Panel</label>
                            <select name="solar_panel_type_id" class="form-control">
                                @if($fbsSystem->solar_panel_type_id)
                                    <option value="{{$fbsSystem->EnergyPv->id}}" disabled selected>
                                        {{$fbsSystem->EnergyPv->pv_model}}
                                    </option>
                                    @foreach($solarPanles as $solarPanle)
                                    <option value="{{$solarPanle->id}}">
                                        {{$solarPanle->pv_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($solarPanles as $solarPanle)
                                    <option value="{{$solarPanle->id}}">
                                        {{$solarPanle->pv_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of solar panels</label> 
                            <input type="number" class="form-control" name="solar_panel_units"
                                value="{{$fbsSystem->solar_panel_units}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Charge Controller</label>
                            <select name="charge_controller_type_id" class="form-control">
                                @if($fbsSystem->charge_controller_type_id)
                                    <option value="{{$fbsSystem->EnergyChargeController->id}}" disabled selected>
                                        {{$fbsSystem->EnergyChargeController->charge_controller_model}}
                                    </option>
                                    @foreach($chargeControllers as $chargeController)
                                    <option value="{{$chargeController->id}}">
                                        {{$chargeController->charge_controller_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($chargeControllers as $chargeController)
                                    
                                    <option value="{{$chargeController->id}}">
                                        {{$chargeController->charge_controller_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of charge controllers</label> 
                            <input type="number" class="form-control" name="charge_controller_units"
                                value="{{$fbsSystem->charge_controller_units}}"> 
                        </fieldset> 
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Charge Controller MCB</label>
                            <select name="charge_controller_mcb_type_id" class="form-control">
                                @if($fbsSystem->charge_controller_mcb_type_id)
                                    <option value="{{$fbsSystem->EnergyMcbChargeController->id}}" disabled selected>
                                        {{$fbsSystem->EnergyMcbChargeController->model}}
                                    </option>
                                    @foreach($mcbChargeControllers as $mcbChargeController)
                                    <option value="{{$mcbChargeController->id}}">
                                        {{$mcbChargeController->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($mcbChargeControllers as $mcbChargeController)
                                    
                                    <option value="{{$mcbChargeController->id}}">
                                        {{$mcbChargeController->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of charge controllers MCB</label> 
                            <input type="number" class="form-control" name="charge_controller_mcb_units"
                                value="{{$fbsSystem->charge_controller_mcb_units}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Relay Driver</label>
                            <select name="relay_driver_type_id" class="form-control">
                                @if($fbsSystem->relay_driver_type_id)
                                    <option value="{{$fbsSystem->EnergyRelayDriver->id}}" disabled selected>
                                        {{$fbsSystem->EnergyRelayDriver->model}}
                                    </option>
                                    @foreach($relayDrivers as $relayDriver)
                                    <option value="{{$relayDriver->id}}">
                                        {{$relayDriver->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($relayDrivers as $relayDriver)
                                    <option value="{{$relayDriver->id}}">
                                        {{$relayDriver->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of relay drivers</label> 
                            <input type="number" class="form-control" name="relay_driver_units"
                                value="{{$fbsSystem->relay_driver_units}}"> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV MCB</label>
                            <select name="pv_mcb_type_id" class="form-control">
                                @if($fbsSystem->pv_mcb_type_id)
                                    <option value="{{$fbsSystem->EnergyMcbPv->id}}" disabled selected>
                                        {{$fbsSystem->EnergyMcbPv->model}}
                                    </option>
                                    @foreach($mcbPvs as $mcbPv)
                                    <option value="{{$mcbPv->id}}">
                                        {{$mcbPv->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($mcbPvs as $mcbPv)
                                    <option value="{{$mcbPv->id}}">
                                        {{$mcbPv->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># PV MCB</label> 
                            <input type="number" class="form-control" name="pv_mcb_units"
                                value="{{$fbsSystem->pv_mcb_units}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inverter</label>
                            <select name="invertor_type_id" class="form-control">
                                @if($fbsSystem->invertor_type_id)
                                    <option value="{{$fbsSystem->EnergyInverter->id}}" disabled selected>
                                        {{$fbsSystem->EnergyInverter->inverter_model}}
                                    </option>
                                    @foreach($inventers as $inventer)
                                    <option value="{{$inventer->id}}">
                                        {{$inventer->inverter_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($inventers as $inventer)
                                    <option value="{{$inventer->id}}">
                                        {{$inventer->inverter_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of inverters</label> 
                            <input type="number" class="form-control" name="invertor_units"
                                value="{{$fbsSystem->invertor_units}}"> 
                        </fieldset> 
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>MCB Inventers</label>
                            <select name="invertor_mcb_type_id" class="form-control">
                                @if($fbsSystem->invertor_mcb_type_id)
                                    <option value="{{$fbsSystem->EnergyMcbInverter->id}}" disabled selected>
                                        {{$fbsSystem->EnergyMcbInverter->inverter_MCB_model}}
                                    </option>
                                    @foreach($mcbInventers as $mcbInventer)
                                    <option value="{{$mcbInventer->id}}">
                                        {{$mcbInventer->inverter_MCB_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($mcbInventers as $mcbInventer)
                                    <option value="{{$mcbInventer->id}}">
                                        {{$mcbInventer->inverter_MCB_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of MCB inventers</label> 
                            <input type="number" class="form-control" name="invertor_mcb_units"
                                value="{{$fbsSystem->invertor_mcb_units}}"> 
                        </fieldset> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSP</label>
                            <select name="bsp_type_id" class="form-control">
                                @if($fbsSystem->bsp_type_id)
                                    <option value="{{$fbsSystem->EnergyBatteryStatusProcessor->id}}" disabled selected>
                                        {{$fbsSystem->EnergyBatteryStatusProcessor->model}}
                                    </option>
                                    @foreach($bsps as $bsp)
                                    <option value="{{$bsp->id}}">
                                        {{$bsp->model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($bsps as $bsp)
                                    <option value="{{$bsp->id}}">
                                        {{$bsp->model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of BSPs</label> 
                            <input type="number" class="form-control" name="bsp_type_units"
                                value="{{$fbsSystem->bsp_type_units}}"> 
                        </fieldset> 
                    </div>
                    
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Load Relay</label>
                            <select name="load_relay_id" class="form-control">
                                @if($fbsSystem->load_relay_id)
                                    <option value="{{$fbsSystem->EnergyLoadRelay->id}}" disabled selected>
                                        {{$fbsSystem->EnergyLoadRelay->load_relay_model}}
                                    </option>
                                    @foreach($loadRelaies as $loadRelay)
                                    <option value="{{$loadRelay->id}}">
                                        {{$loadRelay->load_relay_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($loadRelaies as $loadRelay)
                                    <option value="{{$loadRelay->id}}">
                                        {{$loadRelay->load_relay_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of load relaies</label> 
                            <input type="number" class="form-control" name="load_relay_units"
                                value="{{$fbsSystem->load_relay_units}}"> 
                        </fieldset> 
                    </div>
                   
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Logger</label>
                            <select name="logger_type_id" class="form-control">
                                @if($fbsSystem->logger_type_id)
                                    <option value="{{$fbsSystem->EnergyMonitoring->id}}" disabled selected>
                                        {{$fbsSystem->EnergyMonitoring->monitoring_model}}
                                    </option>
                                    @foreach($loggers as $logger)
                                    <option value="{{$logger->id}}">
                                        {{$logger->monitoring_model}}
                                    </option>
                                    @endforeach
                                @else
                                <option selected disabled>Choose one...</option>
                                @foreach($loggers as $logger)
                                    <option value="{{$logger->id}}">
                                        {{$logger->monitoring_model}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of loggers</label> 
                            <input type="number" class="form-control" name="logger_type_units"
                                value="{{$fbsSystem->logger_type_units}}"> 
                        </fieldset> 
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