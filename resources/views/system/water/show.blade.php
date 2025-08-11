@extends('layouts/layoutMaster')

@section('title', 'water systems')

@include('layouts.all')

@section('content')
 
@php
    $systems = [
        'connector' => $waterSystemConnectors,
        'filter' => $waterSystemFilters,
        'pipe' => $waterSystemPipes,
        'pump' => $waterSystemPumps,
        'tank' => $waterSystemTanks,
        'tap' => $waterSystemTaps,
        'valve' => $waterSystemValves
    ]; 

    $grandTotalCost = 0;

    foreach($systems as $label => $system) {

        $grandTotalCost += $system->sum(function ($item) use ($label) {

            $cost = $item->{$label . '_costs'} ?? 0;
            $units = $item->{$label . '_units'} ?? 0;
            return $cost * $units;
        });
    }

@endphp



<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light"> {{$waterSystem->name}}</span> Details
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        System Name: 
                        <span class="spanDetails">
                            {{$waterSystem->name}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        Community:  
                        @if($waterSystem->community_id)
                            <span class="spanDetails">{{ $waterSystem->Community->english_name }}</span>
                        @endif
                </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        System Type: 
                        <span class="spanDetails">
                            {{$waterSystem->WaterSystemType->type}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        Installion year: 
                        <span class="spanDetails">
                            {{$waterSystem->year}}
                        </span>
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        Upgrade Year 1: 
                        <span class="spanDetails">
                            {{$waterSystem->upgrade_year1}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        Upgrade Year 2: 
                        <span class="spanDetails">
                            {{$waterSystem->upgrade_year2}}
                        </span>
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <h6>
                        Description: 
                        <span>
                            {{$waterSystem->description}}
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
            @if(count($waterSystemTanks) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Tank Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            Tanks:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table id="internetSystemWaterSystemTanks" class="table table-info">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemTanks as $waterSystemTank)
                                <tr>
                                    <td>{{$waterSystemTank->model}}</td>
                                    <td>{{$waterSystemTank->brand}}</td>
                                    <td>{{$waterSystemTank->tank_units}}</td>
                                    <td>{{$waterSystemTank->tank_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemTanks->sum('tank_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemTanks->sum(function ($tank) {
                                                return ($tank->tank_costs ?? 0) * ($tank->tank_units ?? 0);
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
            
            @if(count($waterSystemPumps) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Pumps Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Pumps:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemPumps as $waterSystemPump)
                                <tr>
                                    <td>{{$waterSystemPump->model}}</td>
                                    <td>{{$waterSystemPump->brand}}</td>
                                    <td>{{$waterSystemPump->pump_units}}</td>
                                    <td>{{$waterSystemPump->pump_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemPumps->sum('pump_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemPumps->sum(function ($pump) {
                                                return ($pump->pump_costs ?? 0) * ($pump->pump_units ?? 0);
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
   
            @if(count($waterSystemTaps) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Taps Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Taps:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemTaps as $waterSystemTap)
                                <tr>
                                    <td>{{$waterSystemTap->model}}</td>
                                    <td>{{$waterSystemTap->brand}}</td>
                                    <td>{{$waterSystemTap->tap_units}}</td>
                                    <td>{{$waterSystemTap->tap_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemTaps->sum('tap_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemTaps->sum(function ($tap) {
                                                return ($tap->tap_costs ?? 0) * ($tap->tap_units ?? 0);
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

            @if(count($waterSystemFilters) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Filters Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Filters:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemFilters as $waterSystemFilter)
                                <tr>
                                    <td>{{$waterSystemFilter->model}}</td>
                                    <td>{{$waterSystemFilter->brand}}</td>
                                    <td>{{$waterSystemFilter->filter_units}}</td>
                                    <td>{{$waterSystemFilter->filter_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemFilters->sum('filter_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemFilters->sum(function ($filter) {
                                                return ($filter->filter_costs ?? 0) * ($filter->filter_units ?? 0);
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
    
            @if(count($waterSystemPipes) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Pipe Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Pipe:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemPipes as $waterSystemPipe)
                                <tr>
                                    <td>{{$waterSystemPipe->model}}</td>
                                    <td>{{$waterSystemPipe->brand}}</td>
                                    <td>{{$waterSystemPipe->pipe_units}}</td>
                                    <td>{{$waterSystemPipe->pipe_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemPipes->sum('pipe_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemPipes->sum(function ($pipe) {
                                                return ($pipe->pipe_costs ?? 0) * ($pipe->pipe_units ?? 0);
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
             
            @if(count($waterSystemConnectors) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No UISP Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        UISP:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemConnectors as $waterSystemConnector)
                                <tr>
                                    <td>{{$waterSystemConnector->model}}</td>
                                    <td>{{$waterSystemConnector->brand}}</td>
                                    <td>{{$waterSystemConnector->connector_units}}</td>
                                    <td>{{$waterSystemConnector->connector_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemConnectors->sum('connector_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemConnectors->sum(function ($connector) {
                                                return ($connector->connector_costs ?? 0) * ($connector->connector_units ?? 0);
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
             
            @if(count($waterSystemValves) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Electrician Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        waterSystemValves:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($waterSystemValves as $waterSystemValve)
                                <tr>
                                    <td>{{$waterSystemValve->model}}</td>
                                    <td>{{$waterSystemValve->brand}}</td>
                                    <td>{{$waterSystemValve->valve_units}}</td>
                                    <td>{{$waterSystemValve->valve_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total</td>
                                    <td>{{$waterSystemValves->sum('valve_units') }}</td>
                                    <td>
                                        {{
                                            $waterSystemValves->sum(function ($valve) {
                                                return ($valve->valve_costs ?? 0) * ($valve->valve_units ?? 0);
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