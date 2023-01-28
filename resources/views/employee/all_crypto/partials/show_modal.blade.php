
<?php
    $entryPoints = App\Models\EmployeeEntryPoint::where("employee_recommendation_id", $recommendation->id)->get();
    $targetPoints = App\Models\EmployeeTargetPoint::where("employee_recommendation_id", $recommendation->id)->get();
    $answers = App\Models\EmployeeAnswer::where("employee_recommendation_id", $recommendation->id)->get();
?>
<div id="details-RecommendationsModal{{$recommendation->id}}" class="modal fade"  
    role="dialog" tabindex="-1"style="z-index: 9999;"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
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

                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <h5>{{trans('main.exitPoint')}}</h5>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <h5>{{ $recommendation->exit_point }}</h5>
                    </div>

                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <h5>{{trans('main.entryPoints')}}</h5>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        @if (count($entryPoints))
                            @foreach($entryPoints as $entryPoint)
                                {{ $entryPoint->entry_point }} </br>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <h5>{{trans('main.targetPoints')}}</h5>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        @if (count($targetPoints))
                            @foreach($targetPoints as $targetPoint)
                                {{ $targetPoint->target_point }} </br>
                            @endforeach
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    @if (count($answers))
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h3>ملاحظات مهمة عن الصفقة : </h3>
                    </div>
                        @foreach($answers as $answer)
                            @if($answer->answer == "صاعدة")
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <h5>{{$answer->question}} : {{$answer->answer}}</h5>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <h5 style="color:green">نسبة الصعود = {{$answer->description}}%</h5>
                                </div>

                                
                            @else @if($answer->answer == "هابطة")
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <h5>{{$answer->question}} : {{$answer->answer}}</h5>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12">
                                    <h5 style="color:red">نسبة الهبوط = {{$answer->description}}%</h5>
                                </div>
                            @else  @if($answer->answer == "مستقرة")
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <h5>{{$answer->question}} : {{$answer->answer}}</h5>
                                </div>
                            @else
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    @if($answer->question == "انت مقدم على Trade خطير، فسر رغبتك في اتخاذ هذه الصفقة") 
                                    <h5> هذه الصفقة خطيرة، تم اخذها لسبب
                                        :
                                        <span>
                                            {{$answer->description}}
                                        </span>
                                    </h5>
                                    @else 
                                        <h5 >{{$answer->question}} :
                                            @if($answer->answer == "yes")
                                            <span dir="ltr"style="color:green">
                                                {{$answer->answer}}
                                            </span> 
                                            @else @if($answer->answer == "no")
                                                <span dir="ltr" style="color:red">
                                                    {{$answer->answer}}
                                                </span> 
                                            @else
                                            @endif
                                            @endif
                                        </h5>
                                    @endif
                                </div>
                            @endif
                            @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>