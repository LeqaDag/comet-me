
@extends('layouts/layoutMaster')

@section('title', 'energy systems')

@include('layouts.all')

@section('content')
 
@php

    $systemsWithoutPrefix = [

        'Battery Mount' => $battaryMountSystems,
        'PV Mount' => $pvMountSystems,
        'FBS Cabinet' => $fbsCabinets,
        'FBS Fan' => $fbsFans,
        'FBS Lock' => $fbsLocks,
        'FBS Wiring' => $fbsWirings,
        'House Wiring' => $houseWirings,
        'Electricity Rooms' => $electricityRooms,
        'Electricity Bos Rooms' => $electricityBosRooms,
        'Community Grids' => $communityGrids,
        'Refrigerators' => $refrigerators
    ]; 

    $systemsWithPrefix = [

        'Battery' => ['data' => $battarySystems, 'unit_prefix' => 'battery'],
        'PV' => ['data' => $pvSystems, 'unit_prefix' => 'pv'],
        'Controller' => ['data' => $controllerSystems, 'unit_prefix' => 'controller'],
        'Inverter' => ['data' => $inverterSystems, 'unit_prefix' => 'inverter'],
        'Relay Driver' => ['data' => $relayDriverSystems, 'unit_prefix' => 'relay_driver'],
        'Load Relay' => ['data' => $loadRelaySystems, 'unit_prefix' => 'load_relay'],
        'BSP' => ['data' => $bspSystems, 'unit_prefix' => 'bsp'],
        'RCC' => ['data' => $rccSystems, 'unit_prefix' => 'rcc'],
        'Logger' => ['data' => $loggerSystems, 'unit_prefix' => 'monitoring'],
        'Generator' => ['data' => $generatorSystems, 'unit_prefix' => 'generator'],
        'Turbine' => ['data' => $turbineSystems, 'unit_prefix' => 'turbine'],
        'PV MCB' => ['data' => $pvMcbSystems, 'unit_prefix' => 'mcb_pv'],
        'Controller MCB' => ['data' => $controllerMcbSystems, 'unit_prefix' => 'mcb_controller'],
        'Inverter MCB' => ['data' => $inventerMcbSystems, 'unit_prefix' => 'mcb_inverter'],
        'Air Conditioner' => ['data' => $airConditionerSystems, 'unit_prefix' => 'energy_air_conditioner'],
        'BTS' => ['data' => $btsSystems, 'unit_prefix' => 'bts']
    ];


    $totalSystemWithoutPrefix = 0;
    $totalSystemWithPrefix = 0;
    $grandTotalCost = 0;

    foreach ($systemsWithoutPrefix as $label => $systemInfo) {

        $totalSystemWithoutPrefix += $systemInfo->sum(function ($item) use ($label) {

            $cost = $item->cost ?? 0;
            $units = $item->unit ?? 0;
            return $cost * $units;
        });
    }

    foreach ($systemsWithPrefix as $label => $system) {
        $data = $system['data'];
        $prefix = $system['unit_prefix']; 

        $totalSystemWithPrefix += $data->sum(function ($item) use ($prefix) {

            $unitsField = $prefix ? $prefix . '_units' : 'units';
            $units = $item->{$unitsField} ?? 0;
            $cost = $item->cost ?? 0;

            return $units * $cost;
        });
    }

    $grandTotalCost = $totalSystemWithoutPrefix + $totalSystemWithPrefix;
@endphp


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light"> {{$energySystem->name}}</span> Details
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        System Name:  
                        <span class="spanDetails">
                            {{$energySystem->name}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        System Type: 
                        <span class="spanDetails">
                            {{$energySystem->EnergySystemType->name}}
                        </span>
                    </h6>
                </div>
                @if($energySystem->community_id)
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Community: 
                        <span class="spanDetails">
                            {{$energySystem->Community->english_name}}
                        </span>
                    </h6>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Installation Year: 
                        <span class="spanDetails">
                            {{$energySystem->installation_year}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Cycle Year: 
                        <span class="spanDetails">
                            @if($energySystem->energy_system_cycle_id)
                            {{$energySystem->EnergySystemCycle->name}}
                            @endif
                        </span>
                    </h6>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Upgrade Year 1: 
                        <span class="spanDetails">
                            {{$energySystem->upgrade_year1}}
                        </span>
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Upgrade Year 2: 
                        <span class="spanDetails">
                            {{$energySystem->upgrade_year2}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Rated Solar Power (kW): 
                        <span class="spanDetails">
                            {{$energySystem->total_rated_power}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Generated Power (kW): 
                        <span class="spanDetails">
                            {{$energySystem->generated_power}}
                        </span>
                    </h6>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="alert alert-success text-center">
                        <h5>Total System Cost: <strong>{{ number_format($grandTotalCost, 2) }}</strong> ₪</h5>
                    </div>
                </div>
            </div>

            <hr>
            @if(count($battarySystems) < 1)
                <div class="alert alert-warning">
                    No batteries Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            Batteries:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table class="table table-info">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Brand</th>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($battarySystems as $battarySystem)
                                <tr>
                                    <td>{{$battarySystem->battery_model}}</td>
                                    <td>{{$battarySystem->battery_brand}}</td>
                                    <td>{{$battarySystem->battery_units}}</td>
                                    <td>{{$battarySystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$battarySystems->sum('battery_units') }}</td>
                                    <td>
                                        {{
                                            $battarySystems->sum(function ($battary) {
                                                return ($battary->cost ?? 0) * ($battary->battery_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
            
            @if(count($battaryMountSystems) < 1)
                <div class="alert alert-warning">
                    No Battery Mounts Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            Battery Mounts:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table class="table table-info">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Brand</th>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($battaryMountSystems as $battaryMountSystem)
                                <tr>
                                    <td>{{$battaryMountSystem->model}}</td>
                                    <td>{{$battaryMountSystem->brand}}</td>
                                    <td>{{$battaryMountSystem->unit}}</td>
                                    <td>{{$battaryMountSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$battaryMountSystems->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $battaryMountSystems->sum(function ($battaryMount) {
                                                return ($battaryMount->cost ?? 0) * ($battaryMount->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($pvSystems) < 1)
                <div class="alert alert-warning">
                   No Solar Panel Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Solar Panel:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-warning">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($pvSystems as $pvSystem)
                                <tr>
                                    <td>{{$pvSystem->pv_model}}</td>
                                    <td>{{$pvSystem->pv_brand}}</td>
                                    <td>{{$pvSystem->pv_units}}</td>
                                    <td>{{$pvSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$pvSystems->sum('pv_units') }}</td>
                                    <td>
                                        {{
                                            $pvSystems->sum(function ($pvSystem) {
                                                return ($pvSystem->cost ?? 0) * ($pvSystem->pv_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
   
            @if(count($pvMountSystems) < 1)
                <div class="alert alert-warning">
                   No Solar Panel Mount Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Solar Panel Mounts:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-warning">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($pvMountSystems as $pvSystem)
                                <tr>
                                    <td>{{$pvSystem->model}}</td>
                                    <td>{{$pvSystem->brand}}</td>
                                    <td>{{$pvSystem->unit}}</td>
                                    <td>{{$pvSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$pvMountSystems->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $pvMountSystems->sum(function ($pvMountSystem) {
                                                return ($pvMountSystem->cost ?? 0) * ($pvMountSystem->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
   

            @if(count($controllerSystems) < 1)
                <div class="alert alert-warning">
                    No Controllers Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Controllers:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($controllerSystems as $controllerSystem)
                                <tr>
                                    <td>{{$controllerSystem->charge_controller_model}}</td>
                                    <td>{{$controllerSystem->charge_controller_brand}}</td>
                                    <td>{{$controllerSystem->controller_units}}</td>
                                    <td>{{$controllerSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$controllerSystems->sum('controller_units') }}</td>
                                    <td>
                                        {{
                                            $controllerSystems->sum(function ($controllerSystem) {
                                                return ($controllerSystem->cost ?? 0) * ($controllerSystem->controller_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($inverterSystems) < 1)
                <div class="alert alert-warning">
                    No Inventer Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Inventer:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-success">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($inverterSystems as $inverterSystem)
                                <tr>
                                    <td>{{$inverterSystem->inverter_model}}</td>
                                    <td>{{$inverterSystem->inverter_brand}}</td>
                                    <td>{{$inverterSystem->inverter_units}}</td>
                                    <td>{{$inverterSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$inverterSystems->sum('inverter_units') }}</td>
                                    <td>
                                        {{
                                            $inverterSystems->sum(function ($inverterSystem) {
                                                return ($inverterSystem->cost ?? 0) * ($inverterSystem->inverter_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
                

            @if(count($btsSystems) < 1)
                <div class="alert alert-warning">
                    No BTS Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        BTS:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-success">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($btsSystems as $btsSystem)
                                <tr>
                                    <td>{{$btsSystem->BTS_model}}</td>
                                    <td>{{$btsSystem->BTS_brand}}</td>
                                    <td>{{$btsSystem->bts_units}}</td>
                                    <td>{{$btsSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$btsSystems->sum('bts_units') }}</td>
                                    <td>
                                        {{
                                            $btsSystems->sum(function ($btsSystem) {
                                                return ($btsSystem->cost ?? 0) * ($btsSystem->bts_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif


            @if(count($relayDriverSystems) < 1)
                <div class="alert alert-warning">
                    No Relay Driver Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Relay Driver:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-danger">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($relayDriverSystems as $relayDriverSystem)
                                <tr>
                                    <td>{{$relayDriverSystem->model}}</td>
                                    <td>{{$relayDriverSystem->brand}}</td>
                                    <td>{{$relayDriverSystem->relay_driver_units}}</td>
                                    <td>{{$relayDriverSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$relayDriverSystems->sum('relay_driver_units') }}</td>
                                    <td>
                                        {{
                                            $relayDriverSystems->sum(function ($relayDriverSystem) {
                                                return ($relayDriverSystem->cost ?? 0) * ($relayDriverSystem->relay_driver_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($loadRelaySystems) < 1)
                <div class="alert alert-warning">
                    No Load Relay Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Load Relay:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-secondary">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($loadRelaySystems as $loadRelaySystem)
                                <tr>
                                    <td>{{$loadRelaySystem->load_relay_model}}</td>
                                    <td>{{$loadRelaySystem->load_relay_brand}}</td>
                                    <td>{{$loadRelaySystem->load_relay_units}}</td>
                                    <td>{{$loadRelaySystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$loadRelaySystems->sum('load_relay_units') }}</td>
                                    <td>
                                        {{
                                            $loadRelaySystems->sum(function ($loadRelaySystem) {
                                                return ($loadRelaySystem->cost ?? 0) * ($loadRelaySystem->load_relay_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
             
            @if(count($bspSystems) < 1)
                <div class="alert alert-warning">
                    No BSP Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Battery Proccessor:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-dark">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($bspSystems as $bspSystem)
                                <tr>
                                    <td>{{$bspSystem->model}}</td>
                                    <td>{{$bspSystem->brand}}</td>
                                    <td>{{$bspSystem->bsp_units}}</td>
                                    <td>{{$bspSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$bspSystems->sum('bsp_units') }}</td>
                                    <td>
                                        {{
                                            $bspSystems->sum(function ($bspSystem) {
                                                return ($bspSystem->cost ?? 0) * ($bspSystem->bsp_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($rccSystems) < 1)
                <div class="alert alert-warning">
                    No RCC Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            RCC:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table class="table table-info">
                            <thead>
                                <tr>
                                    <th>Model</th>
                                    <th>Brand</th>
                                    <th>Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($rccSystems as $rccSystem)
                                <tr>
                                    <td>{{$rccSystem->model}}</td>
                                    <td>{{$rccSystem->brand}}</td>
                                    <td>{{$rccSystem->rcc_units}}</td>
                                    <td>{{$rccSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$rccSystems->sum('rcc_units') }}</td>
                                    <td>
                                        {{
                                            $rccSystems->sum(function ($rccSystem) {
                                                return ($rccSystem->cost ?? 0) * ($rccSystem->rcc_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
            
            @if(count($loggerSystems) < 1)
                <div class="alert alert-warning">
                    No Logger Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Logger:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-warning">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($loggerSystems as $loggerSystem)
                                <tr>
                                    <td>{{$loggerSystem->monitoring_model}}</td>
                                    <td>{{$loggerSystem->monitoring_brand}}</td>
                                    <td>{{$loggerSystem->monitoring_units}}</td>
                                    <td>{{$loggerSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$loggerSystems->sum('monitoring_units') }}</td>
                                    <td>
                                        {{
                                            $loggerSystems->sum(function ($loggerSystem) {
                                                return ($loggerSystem->cost ?? 0) * ($loggerSystem->monitoring_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
   
            @if(count($generatorSystems) < 1)
                <div class="alert alert-warning">
                    No Generator Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Generator:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($generatorSystems as $generatorSystem)
                                <tr>
                                    <td>{{$generatorSystem->generator_model}}</td>
                                    <td>{{$generatorSystem->generator_brand}}</td>
                                    <td>{{$generatorSystem->generator_units}}</td>
                                    <td>{{$generatorSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$generatorSystems->sum('generator_units') }}</td>
                                    <td>
                                        {{
                                            $generatorSystems->sum(function ($generatorSystem) {
                                                return ($generatorSystem->cost ?? 0) * ($generatorSystem->generator_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($turbineSystems) < 1)
                <div class="alert alert-warning">
                    No Wind Turbine Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Wind Turbine:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-success">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($turbineSystems as $turbineSystem)
                                <tr>
                                    <td>{{$turbineSystem->wind_turbine_model}}</td>
                                    <td>{{$turbineSystem->wind_turbine_brand}}</td>
                                    <td>{{$turbineSystem->turbine_units}}</td>
                                    <td>{{$turbineSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$turbineSystems->sum('turbine_units') }}</td>
                                    <td>
                                        {{
                                            $turbineSystems->sum(function ($turbineSystem) {
                                                return ($turbineSystem->cost ?? 0) * ($turbineSystem->turbine_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
                

            @if(count($pvMcbSystems) < 1)
                <div class="alert alert-warning">
                    No MCB PV Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        MCB PV:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-danger">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($pvMcbSystems as $pvMcbSystem)
                                <tr>
                                    <td>{{$pvMcbSystem->model}}</td>
                                    <td>{{$pvMcbSystem->brand}}</td>
                                    <td>{{$pvMcbSystem->mcb_pv_units}}</td>
                                    <td>{{$pvMcbSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$pvMcbSystems->sum('mcb_pv_units') }}</td>
                                    <td>
                                        {{
                                            $pvMcbSystems->sum(function ($pvMcbSystem) {
                                                return ($pvMcbSystem->cost ?? 0) * ($pvMcbSystem->mcb_pv_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($controllerMcbSystems) < 1)
                <div class="alert alert-warning">
                    No MCB Controller Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        MCB Controller:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-secondary">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($controllerMcbSystems as $controllerMcbSystem)
                                <tr>
                                    <td>{{$controllerMcbSystem->model}}</td>
                                    <td>{{$controllerMcbSystem->brand}}</td>
                                    <td>{{$controllerMcbSystem->mcb_controller_units}}</td>
                                    <td>{{$controllerMcbSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$controllerMcbSystems->sum('mcb_controller_units') }}</td>
                                    <td>
                                        {{
                                            $controllerMcbSystems->sum(function ($controllerMcbSystem) {
                                                return ($controllerMcbSystem->cost ?? 0) * ($controllerMcbSystem->mcb_controller_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
             
            @if(count($inventerMcbSystems) < 1)
                <div class="alert alert-warning">
                    No MCB Inventer Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        MCB Inventer:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-dark">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($inventerMcbSystems as $inventerMcbSystem)
                                <tr>
                                    <td>{{$inventerMcbSystem->inverter_MCB_model}}</td>
                                    <td>{{$inventerMcbSystem->inverter_MCB_brand}}</td>
                                    <td>{{$inventerMcbSystem->mcb_inverter_units}}</td>
                                    <td>{{$inventerMcbSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$inventerMcbSystems->sum('mcb_inverter_units') }}</td>
                                    <td>
                                        {{
                                            $inventerMcbSystems->sum(function ($inventerMcbSystem) {
                                                return ($inventerMcbSystem->cost ?? 0) * ($inventerMcbSystem->mcb_inverter_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($airConditionerSystems) < 1)
                <div class="alert alert-warning">
                    No Air Conditioner Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Air Conditioner:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-dark">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($airConditionerSystems as $airConditionerSystem)
                                <tr>
                                    <td>{{$airConditionerSystem->model}}</td>
                                    <td>{{$airConditionerSystem->brand}}</td>
                                    <td>{{$airConditionerSystem->energy_air_conditioner_units}}</td>
                                    <td>{{$airConditionerSystem->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$airConditionerSystems->sum('energy_air_conditioner_units') }}</td>
                                    <td>
                                        {{
                                            $airConditionerSystems->sum(function ($airConditionerSystem) {
                                                return ($airConditionerSystem->cost ?? 0) * ($airConditionerSystem->energy_air_conditioner_units ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
       
       
            @if(count($fbsCabinets) < 1)
                <div class="alert alert-warning">
                    No FBS Cabinet Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        FBS Cabinet:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($fbsCabinets as $fbsCabinet)
                                <tr>
                                    <th></th>
                                    <td>{{$fbsCabinet->unit}}</td>
                                    <td>{{$fbsCabinet->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$fbsCabinets->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $fbsCabinets->sum(function ($fbsCabinet) {
                                                return ($fbsCabinet->cost ?? 0) * ($fbsCabinet->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif


            @if(count($fbsFans) < 1)
                <div class="alert alert-warning">
                    No FBS Fan Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        FBS Fan:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($fbsFans as $fbsFan)
                                <tr>
                                    <th></th>
                                    <td>{{$fbsFan->unit}}</td>
                                    <td>{{$fbsFan->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$fbsFans->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $fbsFans->sum(function ($fbsFan) {
                                                return ($fbsFan->cost ?? 0) * ($fbsFan->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif


            @if(count($fbsLocks) < 1)
                <div class="alert alert-warning">
                    No FBS Lock Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        FBS Lock:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($fbsLocks as $fbsLock)
                                <tr>
                                    <th></th>
                                    <td>{{$fbsLock->unit}}</td>
                                    <td>{{$fbsLock->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$fbsLocks->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $fbsLocks->sum(function ($fbsLock) {
                                                return ($fbsLock->cost ?? 0) * ($fbsLock->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif


            @if(count($fbsWirings) < 1)
                <div class="alert alert-warning">
                    No FBS Wiring Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        FBS Wiring:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($fbsWirings as $fbsWiring)
                                <tr>
                                    <th></th>
                                    <td>{{$fbsWiring->unit}}</td>
                                    <td>{{$fbsWiring->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$fbsWirings->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $fbsWirings->sum(function ($fbsWiring) {
                                                return ($fbsWiring->cost ?? 0) * ($fbsWiring->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif


            @if(count($refrigerators) < 1)
                <div class="alert alert-warning">
                    No Refrigerators Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Refrigerators:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($refrigerators as $refrigerator)
                                <tr>
                                    <th></th>
                                    <td>{{$refrigerator->unit}}</td>
                                    <td>{{$refrigerator->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$refrigerators->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $refrigerators->sum(function ($refrigerator) {
                                                return ($refrigerator->cost ?? 0) * ($refrigerator->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

       
            @if(count($electricityRooms) < 1)
                <div class="alert alert-danger">
                    No Electricity Rooms Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Electricity Rooms:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-danger">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($electricityRooms as $electricityRoom)
                                <tr>
                                    <th></th>
                                    <td>{{$electricityRoom->unit}}</td>
                                    <td>{{$electricityRoom->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$electricityRooms->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $electricityRooms->sum(function ($electricityRoom) {
                                                return ($electricityRoom->cost ?? 0) * ($electricityRoom->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($electricityBosRooms) < 1)
                <div class="alert alert-danger">
                    No Electricity Bos Rooms Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Electricity Bos Rooms:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-danger">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($electricityBosRooms as $electricityBosRoom)
                                <tr>
                                    <th></th>
                                    <td>{{$electricityBosRoom->unit}}</td>
                                    <td>{{$electricityBosRoom->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$electricityBosRooms->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $electricityBosRooms->sum(function ($electricityBosRoom) {
                                                return ($electricityBosRoom->cost ?? 0) * ($electricityBosRoom->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($communityGrids) < 1)
                <div class="alert alert-warning">
                    No Community Grids Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Community Grids:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($communityGrids as $communityGrid)
                                <tr>
                                    <th></th>
                                    <td>{{$communityGrid->unit}}</td>
                                    <td>{{$communityGrid->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$communityGrids->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $communityGrids->sum(function ($communityGrid) {
                                                return ($communityGrid->cost ?? 0) * ($communityGrid->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($houseWirings) < 1)
                <div class="alert alert-warning">
                    No House Wirings Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        House Wirings:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table  class="table table-primary">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th >Units</th>
                                    <th>Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($houseWirings as $houseWiring)
                                <tr>
                                    <th></th>
                                    <td>{{$houseWiring->unit}}</td>
                                    <td>{{$houseWiring->cost}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td >Total Units</td>
                                    <td>{{$houseWirings->sum('unit') }}</td>
                                    <td>
                                        {{
                                            $houseWirings->sum(function ($houseWiring) {
                                                return ($houseWiring->cost ?? 0) * ($houseWiring->unit ?? 0);
                                            })
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

       
        </div>
    </div>
</div>

@endsection