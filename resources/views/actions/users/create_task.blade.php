<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
    .dropdown-toggle {
        height: 40px;
        
    }

    .control-label {
        font-size:14px;
    }
</style>

<div id="createUserActionItem" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add New Action Item
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{url('action-item')}}" id="actionItemForm">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Task</label>
                                <input class="form-control" id="task" name="task" required>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Status</label>
                                <select name="action_status_id" class="selectpicker form-control" 
                                    data-live-search="true" id="action_status_id" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($actionStatuses as $actionStatus)
                                    <option value="{{$actionStatus->id}}">
                                        {{$actionStatus->status}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Priority</label>
                                <select name="action_priority_id" class="selectpicker form-control" 
                                    data-live-search="true" id="action_priority_id" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($actionPriorities as $actionPriority)
                                    <option value="{{$actionPriority->id}}">
                                        {{$actionPriority->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Start Date</label>
                                <input type="date" name="date" id="startDate" class="form-control">
                            </fieldset>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>End Date</label>
                                <input type="date" name="due_date" id="endDate" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" id="notes" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit"  id="actionItemButton" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {

        $('#actionItemButton').click(function () {

            var task = $("#task").val();
            var statusId = $("#action_status_id").val();
            var priorityId = $("#action_priority_id").val();
            var startDate = $("#startDate").val();
            var endDate = $("#endDate").val();
            var notes = $("#notes").val();

            $.ajax({
                type: 'POST',
                url: 'action-item/create', 
                data: {
                    task: task,
                    statusId: statusId,
                    priorityId: priorityId,
                    startDate: startDate,
                    endDate: endDate,
                    notes: notes,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'New action item added successfully!',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Okay!'
                    }).then((result) => {
                       
                    });
                },
                error: function (error) {
                    // Handle error, if needed
                    console.error(error);
                }
            });
        });
    });
</script>