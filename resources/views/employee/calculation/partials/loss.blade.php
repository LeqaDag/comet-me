<div id="lossModal{{$recommendation->id}}" class="modal fade"  
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
                <form method="POST" enctype='multipart/form-data'
                action="{{url('employee-calculation', $recommendation->id)}}">
                @csrf
                @method('PUT')
                    <input type="text" name="employee_recommendation_id" 
                        value="{{$recommendation->id}}" hidden>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> نقطة الدخول</label>
                                <?php 
                                    $entryPoint =  App\Models\EmployeeEntryPoint::where('employee_recommendation_id', $recommendation->id)
                                    ->first();
                                ?>
                                @if($entryPoint)
                                    <input type="text"  value="{{$entryPoint->entry_point}}"class="form-control" disabled>
                                    <input type="text" name="entry_point" class="form-control"
                                    value="{{$entryPoint->entry_point}}" hidden>
                                @endif
                              
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'> نقطة الخروج</label>
                                
                                <input type="text"class="form-control"value="{{$recommendation->exit_point}}" disabled>
                                <input type="text" name="exit_point" class="form-control"
                                value="{{$recommendation->exit_point}}" hidden>
                            </fieldset>
                        </div>
                    </div>
                    <div class="form-group overflow-hidden" style="">
                        <div class="col-12">
                            <button  class="btn btn-primary btn-lg"> تأكيد المخسر
                                <i class="icon-plus4"></i>  
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>