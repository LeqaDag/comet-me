@extends('layouts.home_employee')


@section('content')

<?php
	$total = 0;
	$cost = 0;
	$leverage = 1;
?>

@include("employee.partials.home_style")
@include('partials.breadcrumbs', ['method' =>['name'=>trans('main.wallet'),
	'url'=>url('wallet')], 'action' =>trans('main.view')])

<div class="main-content">
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row">
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      	<h5 class="card-title text-uppercase text-muted mb-0">
                        Spot Wallet
                        </h5>
                        @if($wallet)
                          @if($wallet->total > 0)
                          <?php $total = ($wallet->total - $wallet->remaining) + $wallet->profit; ?>
                          <span class="h2 font-weight-bold mb-0" style="color:green">
                            {{$total}}$
                          </span>
                          <input value="{{$total}}" id="spotTotalWallet" hidden>
                          @else
                          <span class="h2 font-weight-bold mb-0" style="color:red">
                            0$ 
                          </span>
                          @endif
                        @endif
                      </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                       
                        <i class="fas fa-dot-circle-o"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="mr-1 text-nowrap" style="color:white">
                      <a type="button" data-toggle="modal" class="btn btn-primary"
											  data-target="#spotToFutureModal{{$user_id}}">
                        Transfer To Future
                        <i class="fa fa-arrow-left"></i>
                      </a>
                    </span>
                    <span class="text-nowrap"></span>
                  </p>

                  @include('employee.wallet.partials.spot')
                </div>
              </div>
            </div>
       
         
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        Future Wallet
                      </h5>
                      @if($sum)
                        @if($sum > 0)
                          <span class="h2 font-weight-bold mb-0" style="color:green">
                            {{$sum}}$
                          </span>
                          @else
                          <span class="h2 font-weight-bold mb-0" style="color:red">
                            0$ 
                          </span>
                        @endif
                      @else 
                        <span class="h2 font-weight-bold mb-0" style="color:red">
                          0$ 
                        </span>
                      @endif
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <a type="button" data-toggle="modal" class="btn btn-primary"
                          data-target="#allRecommendationsProfitAndLoss">
                            <i class="fas fa-history"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="mr-1 text-nowrap" style="color:white">
                      <a type="button" data-toggle="modal" class="btn btn-primary"
											  data-target="#futureToSpotModal{{$user_id}}">
                        <i class="fa fa-arrow-right"></i>
                        Transfer To Spot
                      </a>
                    </span>
                    <span class="">
                      Total Unrealized PNL = 
                      @if($totalPnl)
                        @if($totalPnl > 0)
                          <span class="h2 font-weight-bold mb-0" style="color:green">
                            {{$totalPnl}}$
                          </span>
                          <input value="{{$totalPnl}}" id="pnlWallet" hidden>
                          @else
                          <span class="h2 font-weight-bold mb-0" style="color:red">
                           {{$totalPnl}}$ 
                          </span>
                        @endif
                      @else 
                      <span class="h2 font-weight-bold mb-0" style="color:red">
                        0$ 
                      </span>
                      @endif
                    </span>

                    
                  </p>
                </div>
                @include('employee.wallet.partials.future')
                @include('employee.wallet.partials.all')
              </div>
            </div>
			    <div class="col-xl-2 col-lg-6"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection