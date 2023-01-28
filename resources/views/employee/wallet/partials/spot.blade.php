<div id="spotToFutureModal{{$user_id}}" class="modal fade"  
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
                <form method="POST" action="{{url('wallet')}}">
                @csrf
                    <input type="text" name="user_id" 
                        value="{{$user_id}}" hidden>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>ما هو مقدار المال الذي تود تحويله</label>
                                <input type="text" name="remaining" class="form-control"
                                    id="spotTransfer">
                               
                            </fieldset>
                        </div>
                    </div>

                    <div class="form-group overflow-hidden" style="">
                        <div class="col-12">
                            <button  class="btn btn-primary btn-lg"> تحويل 
                                <i class="icon-plus4"></i>  
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>