@extends('layouts.home_employee')

<style>
	/* CSS for Square boxes */
	.image-span {
		display: inline-block;
		width: 80px;
		height: 80px;
		margin: 6px;
		background-color: white;
	}
</style>

@section('content')
<div>
	<div >
		@include('partials.breadcrumbs', ['method' =>['name'=>trans('main.recommendations'),
		'url'=>url('employee-crypto')], 'action' =>trans('main.view')])
		
		<div class="card">
			@include('partials.card_header', ['title' =>trans('main.recommendations')])
			<div class="card-content collapse show">
				<div class="card-body">
					
				</div>
				<div class="table-responsive">
					@if (count($recommendations))
						<table class="table table-striped " >
							<thead>
								<tr>
									<th class="text-center">{{trans('main.date')}}</th>
									<th class="text-center">توصيات العملات</th>
									<th class="text-center">تعديل نقطة الدخول</th>
									<th class="text-center">تعديل Stop Loss</th>
									<th class="text-center">{{trans('main.options')}}</th>
									<th class="text-center">تأكيد التوصية</th>
									<th class="text-center">اضافة المزيد</th>
								</tr>
							</thead>
							<tbody>
							@if($isNotEmpty == 0)
							<tr >
								<td class="text-center">
									{{ \Carbon\Carbon::now()->format('d/m/Y')}}
								</td>
								<td class="text-center">
									@for($i=0 ; $i< 3; $i++)
										<div class="d-flex justify-content-center">
											<span style="background-color:white"
												class="image-span rounded border border-success justify-content-center align-items-center input-group-text">
												<a href="{{url('employee-crypto', 'create')}}" 
													class="btn btn-primary">
													<i class="fa fa-plus"></i>
												</a>

											</span>
										</div>
									@endfor
								</td>
								<td class="text-center">
									<h5>غير متوفر</h5>
								</td>
								<td class="text-center">
									<h5>غير متوفر</h5>
								</td>
								<td class="text-center">
									<h5> خيارات تعديل نفاط الهدف والحذف غير متوفرة</h5>
								</td>
								<td class="text-center">
									<h5></h5>
								</td>
								<td class="text-center">
									<h5>يجب ادخال 3 توصيات ع الأقل</h5>
								</td>
								
							</tr>
							@endif
							@foreach ($recommendations as $recommendation)
								<?php 
									$cryptos = App\Models\EmployeeRecommendation::where('user_id', Auth::guard('user')->user()->id)
									->whereDate("created_at", $recommendation->created_at)->get();
								?>
								<tr> 
									<td class="text-center">{{ \Carbon\Carbon::parse($recommendation->created_at)->format('d/m/Y')}}</td>
								
									<td class="text-center">
										@if (count($cryptos) == 3)
											@foreach($cryptos as $crypto)
												<div class="d-flex justify-content-center">
													<span style="background-color:white"
														class="image-span rounded border border-success justify-content-center align-items-center input-group-text">
													<img src="{{$crypto->image}}" width=30>
													{{ $crypto->crypto_name }}
													</span>
												</div>
											@endforeach

										@else @if (count($cryptos) < 3)
										<?php $numberOfAdds = 3 - count($cryptos); ?>
											@foreach($cryptos as $crypto)
												<div class="d-flex justify-content-center">
													<span style="background-color:white"
														class="image-span rounded border border-success justify-content-center align-items-center input-group-text">
													<img src="{{$crypto->image}}" width=30>
													{{ $crypto->crypto_name }}
													</span>
												</div>
											@endforeach
											@for($i=0 ; $i< $numberOfAdds; $i++)
												@if($date == $recommendation->created_at)
												<div class="d-flex justify-content-center">
													<span style="background-color:white"
														class="image-span rounded border border-success justify-content-center align-items-center input-group-text">
														<a href="{{url('employee-crypto', 'create')}}" 
															class="btn btn-primary">
															<i class="fa fa-plus"></i>
														</a>
													</span>
												</div>
												@endif
											@endfor

										@endif
										@endif
									</td>
									
									<td class="text-center">
										@if (count($cryptos))
											@foreach($cryptos as $crypto)
											<?php
												$entryPoint = App\Models\EmployeeEntryPoint::where("employee_recommendation_id", $crypto->id)->first();
											?>
											<input type="text" class="form-control" class="editEntryPoint"
												id="editEntryPoint" value="{{$entryPoint->entry_point}}"
												data-class="{{$entryPoint->id}}" style="margin-top:40px">
											@endforeach
										@endif
									</td>
									<td class="text-center">
										@if (count($cryptos))
											@foreach($cryptos as $crypto)
											<input type="text" class="form-control" id="editExitPoint"
												id="editEntryPoint" value="{{$crypto->exit_point}}"
												data-class="{{$crypto->id}}" style="margin-top:40px">
											@endforeach
										@endif
									</td>
									<td class="text-center">
										@if (count($cryptos))
											@foreach($cryptos as $crypto)
											<div class="d-flex justify-content-center">
												<span style="background-color:white"
													class="image-span rounded border border-success justify-content-center align-items-center input-group-text">
													
													<a data-toggle="modal" title="نقاط الهدف"
														data-target="#editTargetPointsModal{{$crypto->id}}">
														<i class="fas fa-eye" style="color:blue; margin-left:10px"></i>
													</a>
													<a href="{{ url('employee-crypto/destory', $crypto->id) }}"
														title="حذف">
														<i class="fas fa-trash-alt delete-item"
														style="color:red; margin-left:10px"></i>
														{{ method_field('delete') }} 
													</a>
													<a data-toggle="modal" title="ملاحظات من باقي المدراء"
														data-target="#notesModal{{$crypto->id}}">
														<i class="fas fa-award" style="color:green"></i>
													</a>
												</span>
											</div>
											@include('employee.crypto.partials.target')
											@include('employee.crypto.partials.notes')
											@endforeach
										@endif
									</td>
									

									<td class="text-center">
										@if (count($cryptos))
											@foreach($cryptos as $crypto)
											<?php
												$reviews = App\Models\EmployeeReview::where('user_id', Auth::guard('user')->user()->id)
													->where("employee_recommendation_id", $crypto->id)
													->get();
											?>
											<div class="d-flex justify-content-center">
												@if(count($reviews) >= 2)
													<span style="color:green; font-size:12px; margin:25px">
													تم التأكيد من جميع المدراء
													</span>
													@else
													<span style="color:red; font-size:11px; margin:25px"
													class="justify-content-center align-items-center input-group-text">
													لم يتم التأكيد بعد
													</span>
												@endif
												
											</div>
											@include('employee.crypto.partials.target')
											@include('employee.crypto.partials.notes')
											@endforeach
										@endif
									</td>
									<td class="text-center">
										@if($recommendation->views < 3)
											@if($date == $recommendation->created_at)
												<?php $remainder = 3-$recommendation->views; ?>
												<h5>قم بادخال {{$remainder}} المتبقي</h5>
											@endif
							
										@else @if($recommendation->views == 3)
											@if($date == $recommendation->created_at)
											<h5>بالامكان اضافة المزيد من التوصيات</h5>

											<a href="{{url('employee-crypto', 'create')}}" 
												class="btn btn-primary">@lang('main.create')</a>
											@endif

										@endif
										@endif
									</td>
								</tr>

								
							@endforeach
							</tbody>
						</table>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>


</div>
	
@endsection