

 {!! Form::hidden('form', $form) !!}


 @if($numberOfRecommendations == 3)
 <br>
    <div class="alert alert-danger">
     لقد تجاوزت الحد المسموح لاضافة توصيات لليوم، يرجى اضافة التوصيات المرادة غدا
    </div>
 @else 
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
                            @if($form == "adding")

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    <label class='col-md-4 control-label'> @lang('main.cryptoCurrency') </label>
                                    <select  name="coin_id" id="cryptoCurrencySelect" 
                                        class="cryptoCurrencySelect form-control" > 
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
                            
                            <input type="text" name="recommendation_id" id="recommendation_id" hidden>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('leverage',trans('main.leverage'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::text('leverage',old('leverage'),['id'=> "leverage",'class'=>"form-control"]) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('type',trans('main.type_of_crypto'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::select('type',trans('main.type_of_crypto_select'),null,['class'=>'form-control select2l']) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('exit_point',trans('main.exitPoint'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::text('exit_point',old('exit_point'),['id'=> "exit_point",'class'=>"form-control"]) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                <fieldset class="form-group">
                                    {!! Form::label('graph_image',trans('main.graphImage'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                    {!! Form::file('graph_image',old('graph_image'),['id'=> "graph_image",'class'=>"form-control"]) !!}
                                </fieldset>
                            </div>

                            <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12">
                                <table class="table table-bordered" id="dynamicAddRemoveEntryPoints">
                                    <tr>
                                        <th>{{trans('main.entryPoints')}}</th>
                                        <th>{{trans('main.options')}}</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsEntryPoints[0][subject]" 
                                            placeholder="ادخل نقطة دخول" class="form-control" />
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addEntryPointsButton" 
                                            class="btn btn-outline-primary">
                                                {{trans('main.addMore')}}
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                           
                            <div class="col-xl-6 col-lg-6 col-md-12">
                                <table class="table table-bordered" id="dynamicAddRemoveTargetPoints">
                                    <tr>
                                        <th>{{trans('main.targetPoints')}}</th>
                                        <th>{{trans('main.options')}}</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsTargetPoints[0][subject]" 
                                            placeholder="ادخل نقطة هدف" class="form-control" />
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addTargetPointsButton" 
                                            class="btn btn-outline-primary">
                                                {{trans('main.addMore')}}
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @else
                                <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        <label class='col-md-4 control-label'> @lang('main.date') </label>
                                        <input name="date" type="datetime-local" 
                                        value='{{$trade->date}}' class="form-control">
                                    </fieldset>
                                </div>
                                <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        {!! Form::label('archive',trans('main.visibility'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::select('archive',trans('main.yes_no'),null,['class'=>'form-control select2l']) !!}
                                    </fieldset>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        {!! Form::label('type', trans('main.type'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::select('type',trans('main.location_type'),null,['id'=> "type", 'class'=>'form-control select2l']) !!}
                                    </fieldset>
                                </div>

                                <div id="locationDiv" class="col-xl-4 col-lg-6 col-md-12 mb-1" style="visibility:visible">
                                    <fieldset class="form-group">
                                        {!! Form::label('location',trans('main.location'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::text('location',$trade->location,['class'=>"form-control"]) !!}
                                    </fieldset>
                                </div>

                                <div id="codeDiv" class="col-xl-4 col-lg-6 col-md-12 mb-1" style="visibility:visible">
                                    <fieldset class="form-group">
                                    @if($trade)
                                        {!! Form::label('code',trans('main.code'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::text('code',value($trade->code),['id'=>"code",'class'=>"form-control"]) !!}
                                    @else
                                    {!! Form::label('code',trans('main.code'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::text('code',old('code'),['id'=>"code",'class'=>"form-control"]) !!}
                                    @endif
                                    </fieldset>
                                </div>
                            
                                <div id="linkDiv" class="col-xl-4 col-lg-6 col-md-12 mb-1" style="visibility:visible">
                                    <fieldset class="form-group">
                                    @if($trade)
                                        {!! Form::label('link',trans('main.link'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::text('link',value($trade->link),['id'=> "link",'class'=>"form-control"]) !!}
                                    @else
                                        {!! Form::label('link',trans('main.link'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        {!! Form::text('link',old('link'),['id'=> "link",'class'=>"form-control"]) !!}
                                    @endif
                                    </fieldset>
                                </div>
                            @endif

                            
                        </div>
                        <div class="form-group overflow-hidden">
                            <div class="col-12">
                                <button data-repeater-create="" class="btn btn-primary btn-lg">
                                    <i class="icon-plus4"></i>  {{$btn}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endif