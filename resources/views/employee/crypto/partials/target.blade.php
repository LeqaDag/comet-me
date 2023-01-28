<div id="editTargetPointsModal{{$crypto->id}}" class="modal fade"  
    role="dialog" tabindex="-1"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title w-100">
                    تعديل نقاط الهدف
                </h4>
            </div>
            <?php
                $targetPoints = App\Models\EmployeeTargetPoint::where("employee_recommendation_id", $crypto->id)->get();
            ?>
            <div class="modal-body">
                
                <div class="row">
                    @foreach($targetPoints as $targetPoint)
                    <div class="col-6">
                        <input type="text" class="form-control" id="editTrgetPoint"
                        value="{{$targetPoint->target_point}}"
                        data-class="{{$targetPoint->id}}" style="margin-top:25px">
                    </div>
                    @endforeach
                </div>
                <br>
                <div class="form-group overflow-hidden" style="">
                    <div class="col-12">
                        <button class="btn btn-danger" data-dismiss="modal" aria-label="Close"> اغلاق
                            <i class="icon-close"></i>  
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>