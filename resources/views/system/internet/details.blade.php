<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="internetSystemViewModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="internetSystemModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            System Name: 
                            <span class="spanDetails" id="internetSystemName">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            System Type: 
                            <span class="spanDetails" id="internetSystemType">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div id="routerDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                Routers:
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemRouters" class="table table-info">
                                <thead>
                                    <tr>
                                        <th >Model</th>
                                        <th >Brand</th>
                                        <th >Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
             
                
                <div id="switchDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                Switches:
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemSwitches" class="internetSystemSwitches table table-warning">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
                
                <div id="controllerDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                Controllers: 
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemControllers"class="table table-primary">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
                
                <div id="apMeshDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                Ap Meshes: 
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemApMesh"class="table table-primary">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
                
                <div id="apLitesDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                AP Lites: 
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemApLites"class="table table-primary">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
               
                <div id="ptpDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                PTP: 
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemPtp"class="table table-primary">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <hr>
                </div>
                
                <div id="uispDiv" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <h6>
                                UISP: 
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12" >
                            <table id="internetSystemUisp"class="table table-primary">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Brand</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>