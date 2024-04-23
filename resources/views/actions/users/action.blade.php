<div class="container">
    <div class="card my-2">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Status</label>
                        <select name="action_status_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByStatus">
                            <option disabled selected>Choose one...</option>
                            @foreach($actionStatuses as $actionStatus)
                                <option value="{{$actionStatus->id}}">{{$actionStatus->status}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Priority</label>
                        <select name="action_priority_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByPriority">
                            <option disabled selected>Choose one...</option>
                            @foreach($actionPriorities as $actionPriority)
                                <option value="{{$actionPriority->id}}">{{$actionPriority->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Start Date</label>
                        <input type="date" name="date" class="form-control" id="filterByStartDate">
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By End Date</label>
                        <input type="date" name="due_date" class="form-control" id="filterByEndDate">
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButton">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success btn-sm" 
                    data-bs-toggle="modal" data-bs-target="#createUserActionItem">
                    <i class="bx bx-plus"></i>
                    Add New Action Item
                </button>
                @include('actions.users.create_task')
            </div> 
            <table id="actionItemUserTable" class="table table-striped data-table-action-items my-2">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>