@extends('layouts.home_employee')

@section('content')

@include('partials.breadcrumbs', ['method' =>['name'=>trans('main.recommendations'),
'url'=>url('employee-crypto')], 'action' =>$recommendation->crypto_name])

<h1>{{$recommendation->crypto_name}}</h1>

<div class="row">
	<div class="col-lg-2"></div>	
	<div class="col-lg-8 col-md-6 col-sm-12 text-center">
		<div class="card card-chart">
			<div class="card-body">
				<img src='/EmployeeRecommendations/{{$recommendation->graph_image}}'
				 style="width:100%; margin:10px auto;">
			</div>
		</div>
	</div>
	<div class="col-lg-2"></div>
</div>

@if ($recommendation)

<table class="table table-striped">
	<thead>
		<tr>
			<th class="text-center">{{trans('main.recommendationDate')}}</th>
			<th class="text-center">{{trans('main.logo')}}</th>
			<th class="text-center">{{trans('main.entryPoints')}}</th>
			<th class="text-center">{{trans('main.targetPoints')}}</th>
			<th class="text-center">{{trans('main.exitPoint')}}</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td class="text-center">{{ \Carbon\Carbon::parse($recommendation->date)->format('d/m/Y')}}</td>
			<td class="text-center">
				<img src="{{$recommendation->image}}" width=30>
			</td>	
			@if (count($entryPoints))
				<td class="text-center">
					@foreach($entryPoints as $entryPoint)
						{{ $entryPoint->entry_point }} </br>
					@endforeach
				</td>
			@endif

			@if (count($targetPoints))
				<td class="text-center">
					@foreach($targetPoints as $targetPoint)
						{{ $targetPoint->target_point }} </br>
					@endforeach
				</td>
			@endif

			<td class="text-center">{{ $recommendation->exit_point }}</td>
		</tr>
	</tbody>
</table>

@endif
@endsection