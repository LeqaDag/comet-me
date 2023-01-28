@extends('layouts.home_employee')

@section('content')


	@include('partials.breadcrumbs', ['method' =>['name'=>trans('main.recommendations'),
	'url'=>url('employee-crypto')], 'action' =>trans('main.view')])
	
	<div class="card">
		@include('partials.card_header', ['title' =>trans('main.recommendations')])
		<div class="card-content collapse show">
			<div class="card-body">
				<p class="card-text">
					<div>
						<a href="{{url('employee-crypto', 'create')}}" class="btn btn-primary">@lang('main.create')</a>
					</div>
				</p>
			</div>
			<div class="table-responsive">
				@if (count($recommendations))
					<table class="table table-striped " >
						<thead>
							<tr>
								<th class="text-center">مدير المحفظة</th>
								<th class="text-center">{{trans('main.recommendationDate')}}</th>
								<th class="text-center">وقت التوصية</th>
								<th class="text-center">{{trans('main.coin_name')}}</th>
								<th class="text-center">Stop Loss</th>
								<th class="text-center">تأكيد التوصية</th>
								<th class="text-center">تفاصيل التوصية</th>
								<th class="text-center">ملاحظات باقي المدراء</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($recommendations as $recommendation)
							<tr> 
								<td class="text-center">
									{{ $recommendation->User->fname }} {{ $recommendation->User->lname }}
								</td>
								<td class="text-center">
									{{ \Carbon\Carbon::parse($recommendation->created_at)->format('d/m/Y')}}
								</td>
								<td class="text-center">
									{{ \Carbon\Carbon::parse($recommendation->created_at)->format('h:i a')}}
								</td>
								<td class="text-center">
									<img src="{{$recommendation->image}}" width=30>
									{{ $recommendation->crypto_name }}
								</td>
								<td class="text-center">
									{{ $recommendation->exit_point }}
								</td>
								<?php
									$review = App\Models\EmployeeReview::where("employee_recommendation_id", $recommendation->id)->first();
									$calculation = App\Models\EmployeeCalculation::where("employee_recommendation_id", $recommendation->id)->first();
								?>
								<td class="text-center">
									@if($calculation)
									التوصية جاهزة
									@else
									@if($review)
									<input type="checkbox" data-id="{{ $recommendation->id }}" 
									data-class="{{$recommendation->User->id}}"	name="active" 
									class="check_recommendation_switch" {{ $review->check == 1 ? 'checked' : '' }}>

									@else
									<input type="checkbox" data-id="{{ $recommendation->id }}" 
									data-class="{{$recommendation->User->id}}"	name="active" 
									class="check_recommendation_switch" >

									@endif
									@endif
								</td>
				
								<td class="text-center">
									<a type="button" class="btn btn-success" data-toggle="modal" 
									data-target="#details-RecommendationsModal{{$recommendation->id}}">
									{{trans('main.show')}}</a>
								</td>
								<td class="text-center">
									<a type="button" class="btn btn-info" data-toggle="modal" 
										data-target="#notes-RecommendationsModal{{$recommendation->id}}">
										{{trans('main.show')}}
									</a>
									@include('employee.all_crypto.partials.note_modal')
								</td>
							</tr>
							@include('employee.all_crypto.partials.cancel')
							@include('employee.all_crypto.partials.show_modal')
						@endforeach
						</tbody>
					</table>
			
				@endif
			</div>
		</div>
	</div>



@endsection