
@extends('layouts/layoutMaster')

@section('title', 'internet systems')

@include('layouts.all')

@section('content')
 
@php
    $systems = [
        'router' => $routers,
        'switch' => $switches,
        'controller' => $controllers,
        'ptp' => $ptps,
        'ap' => $aps,
        'ap_lite' => $apLites,
        'uisp' => $uisps,
        'electrician' => $electricians,
        'connector' => $connectors
    ]; 


    $grandTotalCost = 0;

    // Add system costs
    foreach ($systems as $label => $system) {

        $grandTotalCost += $system->sum($label . '_costs');
    }

    // Add cabinet costs (pivot + components)
    foreach ($internetSystem->networkCabinets as $cabinet) {

        $cabinetCost = $cabinet->pivot->cost ?? 0;
        $componentCost = $cabinet->components->sum(fn($c) => $c->unit * $c->cost);
        $grandTotalCost += ($cabinetCost + $componentCost);
    }
@endphp


@foreach($systems as $label => $system)

    @php

        $totalCost = $system->sum($label . '_costs');
        $grandTotalCost += $totalCost;
    @endphp
@endforeach


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light"> {{$internetSystem->system_name}}</span> Details
</h4>

<!-- @foreach($lineOfSightMainCommunities as $lineOfSightMainCommunity)
    <div class="">
        <h4>{{$lineOfSightMainCommunity->main_community_name}}</h4>
        <img src="/assets/images/upload.gif" alt class="img-responsive"
        style=" transform: rotate(90deg)" width=90 height=90>
    </div>
@endforeach

 -->

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        System Name: 
                        <span class="spanDetails">
                            {{$internetSystem->system_name}}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        System Types: 
                        @foreach($internetSystemTypes as $internetSystemType)
                            <span class="spanDetails">
                                {{$internetSystemType->InternetSystemType->name}},
                            </span>
                        @endforeach 
                    </h6>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        Community: 
                        <span class="spanDetails">
                            {{ $internetSystem->Community->english_name ?? 'N/A' }}
                        </span>
                    </h6>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <h6>
                        Compound: 
                        <span class="spanDetails">
                            {{ $internetSystem->Compound->english_name ?? 'N/A' }}
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
            @if(count($routers) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Router Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            Routers:
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12" >
                        <table id="internetSystemRouters" class="table table-info">
                            <thead>
                                <tr>
                                    <th >Model</th>
                                    <th >Brand</th>
                                    <th >Units</th>
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($routers as $router)
                                <tr>
                                    <td>{{$router->model}}</td>
                                    <td>{{$router->brand_name}}</td>
                                    <td>{{$router->router_units}}</td>
                                    <td>{{$router->router_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$routers->sum('router_units') }}</td>
                                    <td>{{$routers->sum('router_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
            
            @if(count($switches) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Switches Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Switches:
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
                            @foreach($switches as $switch)
                                <tr>
                                    <td>{{$switch->model}}</td>
                                    <td>{{$switch->brand_name}}</td>
                                    <td>{{$switch->switch_units}}</td>
                                    <td>{{$switch->switch_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$switches->sum('switch_units') }}</td>
                                    <td>{{$switches->sum('switch_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
   
            @if(count($controllers) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Controllers Found.
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($controllers as $controller)
                                <tr>
                                    <td>{{$controller->model}}</td>
                                    <td>{{$controller->brand}}</td>
                                    <td>{{$controller->controller_units}}</td>
                                    <td>{{$controller->controller_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$controllers->sum('controller_units') }}</td>
                                    <td>{{$controllers->sum('controller_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($aps) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No AP Meshes Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Ap Meshes:
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
                            @foreach($aps as $ap)
                                <tr>
                                    <td>{{$ap->model}}</td>
                                    <td>{{$ap->brand}}</td>
                                    <td>{{$ap->ap_units}}</td>
                                    <td>{{$ap->ap_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$aps->sum('ap_units') }}</td>
                                    <td>{{$aps->sum('ap_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
                

            @if(count($apLites) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No AP Lites Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        AP Lites:
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
                                    <th >Cost per unit</th>
                                </tr>
                            </thead>
                            @foreach($apLites as $apLite)
                                <tr>
                                    <td>{{$apLite->model}}</td>
                                    <td>{{$apLite->brand}}</td>
                                    <td>{{$apLite->ap_lite_units}}</td>
                                    <td>{{$apLite->ap_lite_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$apLites->sum('ap_lite_units') }}</td>
                                    <td>{{$apLites->sum('ap_lite_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif

            @if(count($ptps) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No PTP Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        PTP:
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
                            @foreach($ptps as $ptp)
                                <tr>
                                    <td>{{$ptp->model}}</td>
                                    <td>{{$ptp->brand}}</td>
                                    <td>{{$ptp->ptp_units}}</td>
                                    <td>{{$ptp->ptp_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-dark">
                                    <td colspan=2>Total</td>
                                    <td>{{$ptps->sum('ptp_units') }}</td>
                                    <td>{{$ptps->sum('ptp_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
             
            @if(count($uisps) < 1)
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
                            @foreach($uisps as $uisp)
                                <tr>
                                    <td>{{$uisp->model}}</td>
                                    <td>{{$uisp->brand}}</td>
                                    <td>{{$uisp->uisp_units}}</td>
                                    <td>{{$uisp->uisp_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total</td>
                                    <td>{{$uisps->sum('uisp_units') }}</td>
                                    <td>{{$uisps->sum('uisp_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
             
            @if(count($electricians) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Electrician Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Electricians:
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
                            @foreach($electricians as $electrician)
                                <tr>
                                    <td>{{$electrician->model}}</td>
                                    <td>{{$electrician->brand}}</td>
                                    <td>{{$electrician->electrician_units}}</td>
                                    <td>{{$electrician->electrician_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total</td>
                                    <td>{{$electricians->sum('electrician_units') }}</td>
                                    <td>{{$electricians->sum('electrician_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif
             

            @if(count($connectors) < 1)
                <div class="alert alert-warning">
                    <strong>Sorry!</strong> No Connector Found.
                </div>                                      
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                        Connector:
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
                            @foreach($connectors as $connector)
                                <tr>
                                    <td>{{$connector->model}}</td>
                                    <td>{{$connector->brand}}</td>
                                    <td>{{$connector->connector_units}}</td>
                                    <td>{{$connector->connector_costs}}</td>
                                </tr>
                            @endforeach 
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2>Total</td>
                                    <td>{{$connectors->sum('connector_units') }}</td>
                                    <td>{{$connectors->sum('connector_costs') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                    </div>
                </div>
                <hr>
            @endif


        @if($internetSystem->networkCabinets->isEmpty())
    <div class="alert alert-warning text-center">
        <strong>Sorry!</strong> No Network Cabinets Found.
    </div>
@else
    <div class="row">
        <div class="col-12 mb-3">
            <h5><i class="fa fa-server me-1"></i> Network Cabinets</h5>
        </div>
    </div>

    @foreach($internetSystem->networkCabinets as $cabinet)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <strong>{{ $cabinet->model }}</strong>
                <span class="badge bg-light text-dark">Cabinet Cost: {{ number_format($cabinet->pivot->cost ?? 0, 2) }} ₪</span>
            </div>

            <div class="card-body">
                @php
                    $componentTotal = $cabinet->components->sum(fn($c) => $c->unit * $c->cost);
                    $grandCabinetTotal = ($cabinet->pivot->cost ?? 0) + $componentTotal;
                    $grouped = $cabinet->components->groupBy('component_type');
                @endphp

                <p>
                    <strong>Total Cost for Cabinet & its components:</strong> 
                    <span class="badge bg-success">{{ number_format($grandCabinetTotal, 2) }} ₪</span>
                </p>

                @foreach($grouped as $type => $components)
                    <div class="mt-4">
                        <h6 class="text-muted">{{ class_basename($type) }}s</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Model</th>
                                        <th>Units</th>
                                        <th>Cost</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($components as $component)
                                        <tr>
                                            <td>{{ $component->component->model ?? '—' }}</td>
                                            <td>{{ $component->unit }}</td>
                                            <td>{{ number_format($component->cost, 2) }} ₪</td>
                                            <td class="fw-bold">
                                                {{ number_format($component->unit * $component->cost, 2) }} ₪
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif

        </div>
    </div>
</div>

@endsection