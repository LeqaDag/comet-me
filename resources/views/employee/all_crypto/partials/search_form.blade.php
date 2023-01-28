
                         <div class="col-lg-3 @lang('main.style.pull')">
	                         	<div class="form-group">
		                         	{!! Form::label('name',trans('main.name'),['class'=>'control-label col-lg-2 '.trans('main.style.pull')]) !!}
		                           <div class="col-lg-10 @lang('main.style.pull')">
		                           {!! Form::text('name',Request::get('name'),['class'=>'form-control','autofocus'=>'autofocus']) !!}
		                           </div>
	                           </div>
                           </div>

                           

                            <div class="clearfix"></div>

                            @include('users.partials.dates',['class'=>'datepicker'])
        		
                    
                          <div class="clearfix"></div>
                      