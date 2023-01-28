<div id="moreCurrenciesModal" class="modal fade"  
    role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title w-100">
                    اضافة عملة جديدة
                </h4>
            </div>
            <div class="modal-body">
                <form method="POST"  encrypt="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-6 control-label'>اسم العملة</label>
                                <input type="text" name="name" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-6 control-label'>رمز العملة</label>
                                <input type="text" name="symbol" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-6 control-label'>شعار العملة</label>
                                <input type="file" name="logo_url" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="form-group overflow-hidden" style="">
                        <div class="col-12">
                            <a id="addMoreCurrencies" class="btn btn-primary btn-lg">  انشاء
                                <i class="icon-plus4"></i>  
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>