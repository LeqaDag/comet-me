
<style>
        .slider-wrap {
      position: relative;
      height: 30px;
      width: 80%;
      margin: 10px auto;
      padding-top: 10px;
    }
    .slider-handler {
      width: 10px;
      height: 20px;
      border-radius: 5px;
      box-shadow: 1px 1px 9px #09c, -1px -1px 9px #09c;
      background-color: #4df;
      position: absolute;
      left: 0;
      top: 5px;
    }

    .slider-range {
      background-color: #4df;
      border-radius: 5px;
      height: 10px;
    }
</style>



 <section class="basic-elements">
    <div class="row"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('main.recommendations')</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('cost', 'المبلغ الكلي للتداول به', ['class' => 'col-md-8  '.trans('main.style.pull').' control-label']) !!}
                                    @if($wallet)
                                        @if($wallet->total > 0)
                                        <?php $total = $wallet->total; ?>
                                        <input id="totalWalletEmployee" class="form-control" style="color:green" type="text" 
                                            value="{{$wallet->total}}" disabled>
                                        @else
                                        <input id="totalWalletEmployee" class="form-control" style="color:red" type="text" 
                                            value="0" disabled>
                                        @endif
                                    @endif
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('cost','النسبة من المبلغ الكلي', ['class' => 'col-md-8  '.trans('main.style.pull').' control-label']) !!}
                                    <div id="ss"></div>
                                    <div class="tt">
                                    </div>
                                    <span>0 </span>
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('profit', 'المبلغ للتداول ', ['class' => 'col-md-8 '.trans('main.style.pull').' control-label']) !!}
                                 
                                    {!! Form::text('profit',old('profit'),['id'=> "sliderRangeReaming",'class'=>"form-control", 
                                          'disabled']) !!}
                                
                                  
                                </fieldset>
                            </div>
                        </div>
                        <input type="text" name="cost" id="costRangeReaming" hidden>
                        <div class="row">

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    <label class='col-md-4 control-label'> @lang('main.cryptoCurrency') </label>
                                    <select  name="coin_id" id="cryptoCurrencySelectEmployee" 
                                        class="cryptoCurrencySelectEmployee form-control" required> 
                                        <option style="font-size:13px"></option>
                                            @foreach($currencies as $currency)
                                            <option value="{{$currency->id}}" 
                                                data-img_src="{{ $currency->logo_url }}"
                                                data-img_width="30px"
                                                data-img_height="30px">
                                                {{$currency->symbol}}
                                            </option>
                                            @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            
                            <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'> 
                                        العملة التي تبحث عنها غير موجودة؟ قم باضافتها الان
                                    </label>
                                    <a class="col-md-3 btn btn-info form-control"
                                        type="button" data-toggle="modal" 
										data-target="#moreCurrenciesModal">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </fieldset>
                            </div>
                            @include('employee.crypto.partials.more')

                            <input type="text" name="recommendation_id" id="recommendation_id" hidden>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('type',trans('main.type_of_crypto'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    <select name="type" id="short_or_long" class="form-control select2l" disabled>
                                        <option selected disabled>اختر</option>
                                        <option value="yes">Long</option>
                                        <option value="no">Short</option>
                                    </select>
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('leverage',trans('main.leverage'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::text('leverage',old('leverage'),['id'=> "leverage",'class'=>"form-control", 
                                        'required'=>'required', 'placeholder' => 'ادخل مقدار الرافعة', 'disabled']) !!}
                                
                                    </fieldset>
                            </div>

                           

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('exit_point',trans('main.exitPoint'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::text('exit_point',old('exit_point'),['id'=> "exit_point",
                                        'class'=>"form-control",'required'=>'required', 'disabled', 'placeholder' => 'ادخل الـ Stop Loss']) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('entry_point','نقطة الدخول', ['class' => 'col-md-6  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::text('entry_point',old('entry_point'),['id'=> "entry_point",'class'=>"form-control",
                                        'required'=>'required', 'disabled', 'placeholder' => 'ادخل نقطة الدخول']) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('graph_image',trans('main.graphImage'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::file('graph_image',old('graph_image'),['id'=> "graph_image",'class'=>"form-control", 'required'=>'required']) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                            </div>
                           
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <table class="table table-bordered" id="dynamicAddRemoveTargetPoints">
                                    <tr>
                                        <th>{{trans('main.targetPoints')}}</th>
                                        <th>{{trans('main.options')}}</th>
                                        <th> ملاحظات</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsTargetPoints[0][subject]" 
                                            placeholder="ادخل نقطة هدف" class="target_point form-control" 
                                            data-id="0" required/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addTargetPointsButton" 
                                            class="btn btn-outline-primary">
                                                {{trans('main.addMore')}}
                                            </button>
                                        </td>
                                        <td>
                                            <?php $j=0 ?>
                                            <span id="targetPointsNote{{$j}}" 
                                            class="targetPointsNote"></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <br>
                        <div class="form-group overflow-hidden" >
                            <div class="col-12">
                                <a class=" " data-toggle="modal" style="color:orange;font-size:25px"
                                data-target="#employeeQuestionsModal">
                                 <i class="fas fa-warning"></i>
                                    <span style="">تأكيد</span>
                                    
                                </a>
                            </div>
                        </div>

                        @include('employee.crypto.partials.question')
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>