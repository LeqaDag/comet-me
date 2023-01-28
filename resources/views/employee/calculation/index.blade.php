@extends('layouts.home_employee')


@section('content')

<?php
	$total = 0;
	$cost = 0;
	$leverage = 1;
?>

<div >
	@include('partials.breadcrumbs', ['method' =>['name'=>trans('main.calculations'),
	'url'=>url('eemployee-calculation')], 'action' =>trans('main.view')])
	
	<div class="card">
		@include('partials.card_header', ['title' =>trans('main.calculations')])
		<div class="card-content collapse show">
			<div class="card-body">
					<h6>المبلغ الكلي للتداول به = 
					@if($wallet)
						@if($wallet->total > 0)
						<?php $total = $wallet->total; ?>
						<span style="color:green">
							{{$wallet->total}}$
						</span>
						@else
						<span style="color:red">
							0$ 
						</span>
						@endif
					@endif
					</h6>
			</div>
			<div class="table-responsive">
				@if (count($recommendations))
					<table class="table table-striped " >
						<thead>
							<tr>
								<th class="text-center">{{trans('main.date')}}</th>
								<th class="text-center">العملة</th>
								<th class="text-center">{{trans('main.leverage')}}</th>
								<th class="text-center"> المبلغ للتداول به</th>
								<th class="text-center">هل قمت بانهاء الصفقة؟</th>
								<th class="text-center">الأرباح</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($recommendations as $recommendation)
							
							<tr> 
								<td class="text-center">{{ \Carbon\Carbon::parse($recommendation->created_at)->format('d/m/Y')}}</td>
								
								<td class="text-center">
									<img src="{{$recommendation->image}}" width=30>
									{{ $recommendation->crypto_name }}
								</td>

								<td class="text-center"> 
									{{ $recommendation->leverage}}
								</td>

								<td class="text-center">
									<?php $employeeCalculation = App\Models\EmployeeCalculation::where('user_id', $recommendation->user_id)
										->where('employee_recommendation_id', $recommendation->id)
										->first();
									?> 
									
									{{$recommendation->profit}}
								</td>


								
								<td class="text-center">
									@if($employeeCalculation)  
										@if(is_null($employeeCalculation->is_profit))
											
											<a type="button" data-toggle="modal" 
											data-target="#targetModal{{$recommendation->id}}">
											<i class="fas fa-check" style="color:green"> ربح </i>
											</a>
											
											<a href="">
												<i class="fas fa-close" style="color:red"> خسارة </i>
											</a>
										@else @if($employeeCalculation->is_profit == 0)
											<span style="color:red">خسارة</span>
										@else @if($employeeCalculation->is_profit == 1)
											<span style="color:green">مربح</span>

										@endif
										@endif
										@endif 
									@else
										<a type="button" data-toggle="modal" 
											data-target="#targetModal{{$recommendation->id}}">
											<i class="fas fa-check" style="color:green"> ربح </i>
										</a>
										
										<a type="button" data-toggle="modal" 
											data-target="#lossModal{{$recommendation->id}}">
											<i class="fas fa-close" style="color:red"> خسارة </i>
										</a>
										<!-- <select name="employee_profit_select" data-id="{{ $recommendation->id }}"
											data-class="{{ $recommendation->user_id }}" 
											class="employee_profit_select form-control" > 
											<option style="font-size:13px; color:green" selected disabled>
												اختر
											</option>
											<option value="1">انهاء بربح</option>
											<option value="0">انهاء بخسارة</option>
										</select>  -->
									@endif
								</td>

								<td class="text-center">
									@if($employeeCalculation) 
										
										@if(is_null($employeeCalculation->is_profit))
											
										@else @if($employeeCalculation->is_profit == 0)
											@if(is_null($employeeCalculation->profit))
											<span style="color:red">
											
											</span>
											@else
											<span style="color:red">
											<?php 
												$remaining = $employeeCalculation->profit; 
												if($recommendation->type == 1) {

													$loss = ($employeeCalculation->entry_point - $employeeCalculation->target_point);												
												} else if($recommendation->type == 0) {

													$loss = ($employeeCalculation->target_point - $employeeCalculation->entry_point);
												}
												
												$lossPercentage = ($loss / $employeeCalculation->entry_point) ;
												$lossPercentage = ($lossPercentage * $recommendation->leverage) * 100;
												$loss = ($remaining * $lossPercentage) * -1;

												$lossPercentage = round($lossPercentage, 4); 
												$loss = round($loss, 4); 

											?>
												نسبة المخسر = {{$lossPercentage}}%
												المخسر = {{$loss}}$
											</span>
											@endif
										@else @if($employeeCalculation->is_profit == 1)
											<span style="color:green">
											<?php 

												$remaining =  $employeeCalculation->profit; 
												if($recommendation->type == 1) {

													$pro = ($employeeCalculation->target_point - $employeeCalculation->entry_point);
												} else if($recommendation->type == 0) {

													$pro = ($employeeCalculation->entry_point - $employeeCalculation->target_point);
												}

												$proPercentage = ($pro / $employeeCalculation->entry_point);
												$proPercentage = ($proPercentage * $recommendation->leverage) *100;
												$pro = ($remaining * $proPercentage);

												$proPercentage = round($proPercentage, 4); 
												$pro = round($pro, 4); 
											?>
												نسبة الأرباح = {{$proPercentage}}%
												الربح = {{$pro}}$
											</span>
										@endif
										@endif
										@endif
									@endif
								</td>
							</tr>
							@include('employee.calculation.partials.target')
							@include('employee.calculation.partials.loss')
						@endforeach
						</tbody>
					</table>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>


@endsection