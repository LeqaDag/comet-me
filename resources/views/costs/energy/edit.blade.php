@extends('layouts/layoutMaster')

@section('title', 'edit energy cost')

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
    <span class="text-muted fw-light">Edit </span> {{$energySystem->name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-cost.update', $energySystem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    @if($energySystem->Community)
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <input type="text" class="form-control" disabled
                            value="{{$energySystem->Community->english_name}}">
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <input type="text" class="form-control" disabled
                            value="{{$energySystem->EnergySystemType->name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" disabled
                            class="form-control" value="{{$energySystem->name}}">
                        </fieldset>
                    </div>
                </div>
                <br>

                @if($gridRoom)
                <div class="row">
                    <h6>Grid/Electricity Costs</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity Room Number</label>
                            <input class="form-control" type="text" name="electricity_room_number"
                                value="{{$gridRoom->electricity_room_number}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity Room Cost</label>
                            <input class="form-control" type="text" name="electricity_room_cost"
                                value="{{$gridRoom->electricity_room_cost}}">
                            <input type="hidden" name="grid_room_id" value="{{ $gridRoom->id }}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity Room BoS Number</label>
                            <input class="form-control" type="text" name="electricity_room_bos_number"
                                value="{{$gridRoom->electricity_room_bos_number}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity Room BoS Cost</label>
                            <input class="form-control" type="text" name="electricity_room_bos_cost"
                                value="{{$gridRoom->electricity_room_bos_cost}}">
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community Grid Number</label>
                            <input class="form-control" type="text" name="grid_number"
                                value="{{$gridRoom->grid_number}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community Grid Cost</label>
                            <input class="form-control" type="text" name="grid_cost"
                                value="{{$gridRoom->grid_cost}}">
                        </fieldset>
                    </div>
                </div>
                @endif

                <br>
                @if(count($battarySystems) > 0)
                <div class="row">
                    <h6>Battery Costs</h6> 
                </div>
                @foreach($battarySystems as $battarySystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$battarySystem->battery_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$battarySystem->battery_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery Cost</label>
                            <input class="form-control" type="text" name="battery_costs[]"
                                value="{{$battarySystem->cost}}">
                            <input type="hidden" name="battery_ids[]" value="{{ $battarySystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                <br>
                @if(count($battaryMountSystems) > 0)
                <div class="row">
                    <h6>Battery Mount Costs</h6> 
                </div>
                @foreach($battaryMountSystems as $battaryMountSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery Mount Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$battaryMountSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery Mount Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$battaryMountSystem->unit}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Battery Mount Cost</label>
                            <input class="form-control" type="text" name="battery_mount_costs[]"
                                value="{{$battaryMountSystem->cost}}">
                            <input type="hidden" name="battery_mount_ids[]" value="{{ $battaryMountSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($pvSystems) > 0)
                <br>
                <div class="row">
                    <h6>PV Costs</h6> 
                </div>
                @foreach($pvSystems as $pvSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$pvSystem->pv_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$pvSystem->pv_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV Cost</label>
                            <input class="form-control" type="text" name="pv_costs[]"
                                value="{{$pvSystem->cost}}">
                            <input type="hidden" name="pv_ids[]" value="{{ $pvSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($pvMountSystems) > 0)
                <br>
                <div class="row">
                    <h6>PV Mount Costs</h6> 
                </div>
                @foreach($pvMountSystems as $pvMountSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV Mount Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$pvMountSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV Mount Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$pvMountSystem->unit}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV Mount Cost</label>
                            <input class="form-control" type="text" name="pv_mount_costs[]"
                                value="{{$pvMountSystem->cost}}">
                            <input type="hidden" name="pv_mount_ids[]" value="{{ $pvMountSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($controllerSystems) > 0)
                <br>
                <div class="row">
                    <h6>Charge Controller Costs</h6> 
                </div>
                @foreach($controllerSystems as $controllerSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Charge Controller Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$controllerSystem->charge_controller_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Charge Controller Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$controllerSystem->controller_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Charge Controller Cost</label>
                            <input class="form-control" type="text" name="controller_costs[]"
                                value="{{$controllerSystem->cost}}">
                            <input type="hidden" name="controller_ids[]" value="{{ $controllerSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($inverterSystems) > 0)
                <br>
                <div class="row">
                    <h6>Inverter Costs</h6> 
                </div>
                @foreach($inverterSystems as $inverterSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inverter Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$inverterSystem->inverter_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inverter Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$inverterSystem->inverter_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inverter Cost</label>
                            <input class="form-control" type="text" name="inverter_costs[]"
                                value="{{$inverterSystem->cost}}">
                            <input type="hidden" name="inverter_ids[]" value="{{ $inverterSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($relayDriverSystems) > 0)
                <br>
                <div class="row">
                    <h6>Relay Driver Costs</h6> 
                </div>
                @foreach($relayDriverSystems as $relayDriverSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Relay Driver Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$relayDriverSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Relay Driver Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$relayDriverSystem->relay_driver_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Relay Driver Cost</label>
                            <input class="form-control" type="text" name="relay_costs[]"
                                value="{{$relayDriverSystem->cost}}">
                            <input type="hidden" name="relay_ids[]" value="{{ $relayDriverSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif


                @if(count($loadRelaySystems) > 0)
                <br>
                <div class="row">
                    <h6>Load Relay Costs</h6> 
                </div>
                @foreach($loadRelaySystems as $loadRelaySystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Load Relay Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$loadRelaySystem->load_relay_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Load Relay Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$loadRelaySystem->load_relay_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Load Relay Cost</label>
                            <input class="form-control" type="text" name="load_costs[]"
                                value="{{$loadRelaySystem->cost}}">
                            <input type="hidden" name="load_ids[]" value="{{ $loadRelaySystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif


                @if(count($bspSystems) > 0)
                <br>
                <div class="row">
                    <h6>BSP Costs</h6> 
                </div>
                @foreach($bspSystems as $bspSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSP Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$bspSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSP Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$bspSystem->bsp_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BSP Cost</label>
                            <input class="form-control" type="text" name="bsp_costs[]"
                                value="{{$bspSystem->cost}}">
                            <input type="hidden" name="bsp_ids[]" value="{{ $bspSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($btsSystems) > 0)
                <br>
                <div class="row">
                    <h6>BTS Costs</h6> 
                </div>
                @foreach($btsSystems as $btsSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BTS Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$btsSystem->BTS_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>BTS Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$btsSystem->bts_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>bts Cost</label>
                            <input class="form-control" type="text" name="bts_costs[]"
                                value="{{$btsSystem->cost}}">
                            <input type="hidden" name="bts_ids[]" value="{{ $btsSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif


                @if(count($rccSystems) > 0)
                <br>
                <div class="row">
                    <h6>RCC Costs</h6> 
                </div>
                @foreach($rccSystems as $rccSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>RCC Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$rccSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>RCC Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$rccSystem->rcc_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>RCC Cost</label>
                            <input class="form-control" type="text" name="rcc_costs[]"
                                value="{{$rccSystem->cost}}">
                            <input type="hidden" name="rcc_ids[]" value="{{ $rccSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif


                @if(count($loggerSystems) > 0)
                <br>
                <div class="row">
                    <h6>Monitoring Costs</h6> 
                </div>
                @foreach($loggerSystems as $loggerSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Monitoring Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$loggerSystem->monitoring_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Monitoring Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$loggerSystem->monitoring_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Monitoring Cost</label>
                            <input class="form-control" type="text" name="logger_costs[]"
                                value="{{$loggerSystem->cost}}">
                            <input type="hidden" name="logger_ids[]" value="{{ $loggerSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($generatorSystems) > 0)
                <br>
                <div class="row">
                    <h6>Generator Costs</h6> 
                </div>
                @foreach($generatorSystems as $generatorSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Generator Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$generatorSystem->generator_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Generator Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$generatorSystem->generator_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Generator Cost</label>
                            <input class="form-control" type="text" name="generator_costs[]"
                                value="{{$generatorSystem->cost}}">
                            <input type="hidden" name="generator_ids[]" value="{{ $generatorSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($turbineSystems) > 0)
                <br>
                <div class="row">
                    <h6>Wind Turbine Costs</h6> 
                </div>
                @foreach($turbineSystems as $turbineSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wind Turbine Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$turbineSystem->wind_turbine_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wind Turbine Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$turbineSystem->turbine_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wind Turbine Cost</label>
                            <input class="form-control" type="text" name="turbine_costs[]"
                                value="{{$turbineSystem->cost}}">
                            <input type="hidden" name="turbine_ids[]" value="{{ $turbineSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($pvMcbSystems) > 0)
                <br>
                <div class="row">
                    <h6>PV MCB Costs</h6> 
                </div>
                @foreach($pvMcbSystems as $pvMcbSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV MCB Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$pvMcbSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV MCB Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$pvMcbSystem->mcb_pv_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>PV MCB Cost</label>
                            <input class="form-control" type="text" name="pvMcb_costs[]"
                                value="{{$pvMcbSystem->cost}}">
                            <input type="hidden" name="pvMcb_ids[]" value="{{ $pvMcbSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($controllerMcbSystems) > 0)
                <br>
                <div class="row">
                    <h6>Controller Mcb Costs</h6> 
                </div>
                @foreach($controllerMcbSystems as $controllerMcbSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Controller Mcb Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$controllerMcbSystem->model}}">
                        </fieldset> 
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Controller Mcb Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$controllerMcbSystem->mcb_controller_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Controller Mcb Cost</label>
                            <input class="form-control" type="text" name="controllerMcb_costs[]"
                                value="{{$controllerMcbSystem->cost}}">
                            <input type="hidden" name="controllerMcb_ids[]" value="{{ $controllerMcbSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($inventerMcbSystems) > 0)
                <br>
                <div class="row">
                    <h6>Inventer Mcb Costs</h6> 
                </div>
                @foreach($inventerMcbSystems as $inventerMcbSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inventer Mcb Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$inventerMcbSystem->inverter_MCB_model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inventer Mcb Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$inventerMcbSystem->mcb_inverter_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Inventer Mcb Cost</label>
                            <input class="form-control" type="text" name="inventerMcb_costs[]"
                                value="{{$inventerMcbSystem->cost}}">
                            <input type="hidden" name="inventerMcb_ids[]" value="{{ $inventerMcbSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                @if(count($airConditionerSystems) > 0)
                <br>
                <div class="row">
                    <h6>Air Conditioner Costs</h6> 
                </div>
                @foreach($airConditionerSystems as $airConditionerSystem)
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Air Conditioner Model</label>
                            <input class="form-control" type="text" disabled
                                value="{{$airConditionerSystem->model}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Air Conditioner Units</label>
                            <input class="form-control" type="text" disabled
                                value="{{$airConditionerSystem->energy_air_conditioner_units}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Air Conditioner Cost</label>
                            <input class="form-control" type="text" name="airConditioner_costs[]"
                                value="{{$airConditionerSystem->cost}}">
                            <input type="hidden" name="airConditioner_ids[]" value="{{ $airConditionerSystem->id }}">
                        </fieldset>
                    </div>
                </div>
                @endforeach
                @endif

                <br>
              
                <div class="row">
                    <h6>House Wiring Costs</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>House Wiring Units</label>
                            @if($houseWiringSystem)
                            <input class="form-control" type="text" name="wiring_unit"
                                value="{{$houseWiringSystem->unit}}">
                            @else
                            <input class="form-control" type="text" name="wiring_unit">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>House Wiring Cost</label>
                            @if($houseWiringSystem)
                            <input class="form-control" type="text" name="wiring_cost"
                                value="{{$houseWiringSystem->cost}}">
                            @else
                            <input class="form-control" type="text" name="wiring_cost">
                            @endif
                        </fieldset>
                    </div>
                </div>

                @if($energySystem->energy_system_type_id == 2)
                <br>
                <div class="row">
                    <h6>FBS Wiring Costs</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Wiring Units</label>
                            @if($fbsWiringSystem)
                            <input class="form-control" type="text" name="fbs_wiring_unit"
                                value="{{$fbsWiringSystem->unit}}">
                            @else
                            <input class="form-control" type="text" name="fbs_wiring_unit">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Wiring Cost</label>
                            @if($fbsWiringSystem)
                            <input class="form-control" type="text" name="fbs_wiring_cost"
                                value="{{$fbsWiringSystem->cost}}">
                            @else
                            <input class="form-control" type="text" name="fbs_wiring_cost">
                            @endif
                        </fieldset>
                    </div>
                </div>

                <br>
                <div class="row">
                    <h6>FBS Locks Costs</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Locks Units</label>
                            @if($fbsLockSystem)
                            <input class="form-control" type="text" name="fbs_lock_unit"
                                value="{{$fbsLockSystem->unit}}">
                            @else
                            <input class="form-control" type="text" name="fbs_lock_unit">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Locks Cost</label>
                            @if($fbsLockSystem)
                            <input class="form-control" type="text" name="fbs_lock_cost"
                                value="{{$fbsLockSystem->cost}}">
                            @else
                            <input class="form-control" type="text" name="fbs_lock_cost">
                            @endif
                        </fieldset>
                    </div>
                </div>

                <br>
                <div class="row">
                    <h6>FBS Fans Costs</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Fans Units</label>
                            @if($fbsFanSystem)
                            <input class="form-control" type="text" name="fbs_fan_unit"
                                value="{{$fbsFanSystem->unit}}">
                            @else
                            <input class="form-control" type="text" name="fbs_fan_unit">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Fans Cost</label>
                            @if($fbsFanSystem)
                            <input class="form-control" type="text" name="fbs_fan_cost"
                                value="{{$fbsFanSystem->cost}}">
                            @else
                            <input class="form-control" type="text" name="fbs_fan_cost">
                            @endif
                        </fieldset>
                    </div>
                </div>


                <br>
                <div class="row">
                    <h6>FBS Cabinet Costs</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Cabinet Units</label>
                            @if($fbsCabinetSystem)
                            <input class="form-control" type="text" name="fbs_cabinet_unit"
                                value="{{$fbsCabinetSystem->unit}}">
                            @else
                            <input class="form-control" type="text" name="fbs_cabinet_unit">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>FBS Cabinet Cost</label>
                            @if($fbsCabinetSystem)
                            <input class="form-control" type="text" name="fbs_cabinet_cost"
                                value="{{$fbsCabinetSystem->cost}}">
                            @else
                            <input class="form-control" type="text" name="fbs_cabinet_cost">
                            @endif
                        </fieldset>
                    </div>
                </div>
                @endif
                <br>
              
                <div class="row">
                    <h6>Refrigerator Costs</h6> 
                </div> 
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Refrigerator Units</label>
                            @if($refrigeratorCostSystem)
                            <input class="form-control" type="text" name="refrigerator_unit"
                                value="{{$refrigeratorCostSystem->unit}}">
                            @else
                            <input class="form-control" type="text" name="refrigerator_unit">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Refrigerator Cost</label>
                            @if($refrigeratorCostSystem)
                            <input class="form-control" type="text" name="refrigerator_cost"
                                value="{{$refrigeratorCostSystem->cost}}">
                            @else
                            <input class="form-control" type="text" name="refrigerator_cost">
                            @endif
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