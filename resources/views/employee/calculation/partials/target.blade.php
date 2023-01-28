<div id="targetModal{{$recommendation->id}}" class="modal fade"  
    role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{url('employee-calculation')}}">
                @csrf
                    <input type="text" name="employee_recommendation_id" 
                        value="{{$recommendation->id}}" hidden>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> اختر نقطة الهدف</label>
                                <?php 
                                    $targetPoints =  App\Models\EmployeeTargetPoint::where('employee_recommendation_id', $recommendation->id)
                                    ->get();

                                    $entryPoint =  App\Models\EmployeeEntryPoint::where('employee_recommendation_id', $recommendation->id)
                                    ->first();
                                ?>
                                @if($entryPoint)
                                    <input type="text" name="entry_point" value="{{$entryPoint->entry_point}}" hidden>
                                @endif
                                @if (count($targetPoints))
                                    <select name="target_point"  class="form-control"> 
                                        <option selected disabled>اختر</option>
                                        @foreach ($targetPoints as $targetPoint)
                                        <option value="{{$targetPoint->target_point}}">{{$targetPoint->target_point}}</option>
                                        @endforeach
                                    </select> 
                                @endif
                            </fieldset>
                        </div>
                    </div>

                    <div class="form-group overflow-hidden" style="">
                        <div class="col-12">
                            <button  class="btn btn-primary btn-lg"> تأكيد الربح
                                <i class="icon-plus4"></i>  
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>