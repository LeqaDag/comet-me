
@extends('layouts/layoutMaster')

@section('title', 'internet systems')

@include('layouts.all')

@section('content')
 
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
        </div>
    </div>
</div>

@endsection