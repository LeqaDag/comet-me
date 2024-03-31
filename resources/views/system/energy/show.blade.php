
@extends('layouts/layoutMaster')

@section('title', 'energy systems')

@include('layouts.all')

@section('content')
 
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
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Community: 
                        <span class="spanDetails">
                            {{$energySystem->Community->english_name}}
                        </span>
                    </h6>
                </div>
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
                        Upgrade Year 1: 
                        <span class="spanDetails">
                            {{$energySystem->upgrade_year1}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <h6>
                        Upgrade Year 2: 
                        <span class="spanDetails">
                            {{$energySystem->upgrade_year2}}
                        </span>
                    </h6>
                </div>
            </div>
            <div class="row">
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
                                </tr>
                            </thead>
                            @foreach($battarySystems as $battarySystem)
                                <tr>
                                    <td>{{$battarySystem->battery_model}}</td>
                                    <td>{{$battarySystem->battery_brand}}</td>
                                    <td>{{$battarySystem->battery_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$battarySystems->sum('battery_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($pvSystems as $pvSystem)
                                <tr>
                                    <td>{{$pvSystem->pv_model}}</td>
                                    <td>{{$pvSystem->pv_brand}}</td>
                                    <td>{{$pvSystem->pv_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$pvSystems->sum('pv_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($controllerSystems as $controllerSystem)
                                <tr>
                                    <td>{{$controllerSystem->charge_controller_model}}</td>
                                    <td>{{$controllerSystem->charge_controller_brand}}</td>
                                    <td>{{$controllerSystem->controller_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$controllerSystems->sum('controller_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($inverterSystems as $inverterSystem)
                                <tr>
                                    <td>{{$inverterSystem->inverter_model}}</td>
                                    <td>{{$inverterSystem->inverter_brand}}</td>
                                    <td>{{$inverterSystem->inverter_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$inverterSystems->sum('inverter_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($relayDriverSystems as $relayDriverSystem)
                                <tr>
                                    <td>{{$relayDriverSystem->model}}</td>
                                    <td>{{$relayDriverSystem->brand}}</td>
                                    <td>{{$relayDriverSystem->relay_driver_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$relayDriverSystems->sum('relay_driver_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($loadRelaySystems as $loadRelaySystem)
                                <tr>
                                    <td>{{$loadRelaySystem->load_relay_model}}</td>
                                    <td>{{$loadRelaySystem->load_relay_brand}}</td>
                                    <td>{{$loadRelaySystem->load_relay_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$loadRelaySystems->sum('load_relay_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($bspSystems as $bspSystem)
                                <tr>
                                    <td>{{$bspSystem->model}}</td>
                                    <td>{{$bspSystem->brand}}</td>
                                    <td>{{$bspSystem->bsp_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$bspSystems->sum('bsp_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($rccSystems as $rccSystem)
                                <tr>
                                    <td>{{$rccSystem->model}}</td>
                                    <td>{{$rccSystem->brand}}</td>
                                    <td>{{$rccSystem->rcc_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$rccSystems->sum('rcc_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($loggerSystems as $loggerSystem)
                                <tr>
                                    <td>{{$loggerSystem->monitoring_model}}</td>
                                    <td>{{$loggerSystem->monitoring_brand}}</td>
                                    <td>{{$loggerSystem->monitoring_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$loggerSystems->sum('monitoring_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($generatorSystems as $generatorSystem)
                                <tr>
                                    <td>{{$generatorSystem->generator_model}}</td>
                                    <td>{{$generatorSystem->generator_brand}}</td>
                                    <td>{{$generatorSystem->generator_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$generatorSystems->sum('generator_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($turbineSystems as $turbineSystem)
                                <tr>
                                    <td>{{$turbineSystem->wind_turbine_model}}</td>
                                    <td>{{$turbineSystem->wind_turbine_brand}}</td>
                                    <td>{{$turbineSystem->turbine_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$turbineSystems->sum('turbine_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($pvMcbSystems as $pvMcbSystem)
                                <tr>
                                    <td>{{$pvMcbSystem->model}}</td>
                                    <td>{{$pvMcbSystem->brand}}</td>
                                    <td>{{$pvMcbSystem->mcb_pv_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$pvMcbSystems->sum('mcb_pv_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($controllerMcbSystems as $controllerMcbSystem)
                                <tr>
                                    <td>{{$controllerMcbSystem->model}}</td>
                                    <td>{{$controllerMcbSystem->brand}}</td>
                                    <td>{{$controllerMcbSystem->mcb_controller_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$controllerMcbSystems->sum('mcb_controller_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($inventerMcbSystems as $inventerMcbSystem)
                                <tr>
                                    <td>{{$inventerMcbSystem->inverter_MCB_model}}</td>
                                    <td>{{$inventerMcbSystem->inverter_MCB_brand}}</td>
                                    <td>{{$inventerMcbSystem->mcb_inverter_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$inventerMcbSystems->sum('mcb_inverter_units') }}</td>
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
                                </tr>
                            </thead>
                            @foreach($airConditionerSystems as $airConditionerSystem)
                                <tr>
                                    <td>{{$airConditionerSystem->model}}</td>
                                    <td>{{$airConditionerSystem->brand}}</td>
                                    <td>{{$airConditionerSystem->energy_air_conditioner_units}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total Units</td>
                                    <td>{{$airConditionerSystems->sum('energy_air_conditioner_units') }}</td>
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