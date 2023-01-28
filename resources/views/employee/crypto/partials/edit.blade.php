<section class="basic-elements">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">تعديل توصية {{ $recommendation->crypto_name}}</h4>
                </div>
                <form method="POST" enctype='multipart/form-data'
                    action="{{url('employee-crypto', $recommendation->id)}}">
                    @csrf
                    @method('PUT')
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        {!! Form::label('exit_point',trans('main.exitPoint'), ['class' => 'col-md-4  '.trans('main.style.pull').' control-label']) !!}
                                        <input type="text" name="exit_point" class="form-control"
                                            value="{{$recommendation->exit_point}}">
                                    </fieldset>
                                </div>

                                <div class="col-xl-5 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        {!! Form::label('entry_point','سبب تعديل نقطة الخروج', ['class' => 'col-md-8  '.trans('main.style.pull').' control-label']) !!}
                                        <textarea name="exit_point_description" class="form-control" 
                                            id="exit_point_description" style="resize:none" >
                                        </textarea>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        {!! Form::label('entry_point','نقطة الدخول', ['class' => 'col-md-6  '.trans('main.style.pull').' control-label']) !!}
                                        <input type="text" name="entry_point" class="form-control"
                                            value="{{$entryPoint->entry_point}}">
                                    </fieldset>
                                </div>

                                <div class="col-xl-5 col-lg-6 col-md-12 mb-1">
                                    <fieldset class="form-group">
                                        {!! Form::label('entry_point','سبب تعديل نقطة الدخول', ['class' => 'col-md-8  '.trans('main.style.pull').' control-label']) !!}
                                        <textarea name="entry_point_description" class="form-control"
                                            id="entry_point_description" style="resize:none" >
                                        </textarea>
                                    </fieldset>
                                </div>
                            </div>
                            
                                <div class="col-xl-9 col-lg-12 col-md-12  mb-1">
                                    <table class="table table-bordered" id="dynamicEditTargetPoints">
                                        <tr>
                                            <th>{{trans('main.targetPoints')}}</th>
                                            <th> سبب التعديل</th>
                                        </tr>
                                        @foreach($targetPoints as $targetPoint)
                                        <tr>
                                            <td>
                                                <input type="text" name="targetPoints[{{$targetPoint->id}}][subject]"
                                                value="{{$targetPoint->target_point}}/{{$targetPoint->id}}" hidden>

                                                <input type="text" name="editTargetPoints[{{$targetPoint->id}}][subject]" 
                                                value="{{$targetPoint->target_point}}"
                                                placeholder="ادخل نقطة هدف" class="target_point form-control"/>
                                            </td>
                                            <td>
                                            <textarea name="target_point_description[{{$targetPoint->id}}][description]" 
                                                class="form-control" 
                                                style="resize:none">
                                            </textarea>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                            <br>
                            <div class="form-group overflow-hidden" >
                                <div class="col-12">
                                    <button type="submit" class="btn btn-info btn-lg">
                                        <i class="fas fa-edit"></i> تعديل
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>