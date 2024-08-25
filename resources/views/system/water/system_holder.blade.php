<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style> 
<div id="waterSystemHolderModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="systemModalTitle"></span> - Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xl-12">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('water-system.export') }}">
                            @csrf
                            <div class="card-body"> 
                                <div class="row" id="exportingDiv" style="visibility:hidden; display: none">
                                    <input type="text" id="waterSystemId" name="water_system_id" hidden>
                                    <div class="col-xl-3 col-lg-3 col-md-3">
                                        <label class='col-md-12 control-label'>Download Excel file!</label>
                                        <button class="btn btn-info" type="submit">
                                            <i class='fa-solid fa-file-excel'></i>
                                            Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="">
                        <ul class="timeline timeline-dashed mt-4">
                            <li class="timeline-item timeline-item-primary mb-4">
                                <span class="timeline-indicator timeline-indicator-primary">
                                    <i class="bx bx-water"></i>
                                </span>
                                <div class="row">
                                    <table id="systemHoldersTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Community</th>
                                                <th># of Holders</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        </ul>
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