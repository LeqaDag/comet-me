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
</style>

<div id="createWorkPlan" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add New Action Item
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' 
                     id="workPlanForm">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Task</label>
                                <input class="form-control" name="task" required>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Owner</label>
                                <select class="selectpicker form-control" data-live-search="true" 
                                    name="user_id" required data-parsley-required="true"
                                    id="user_id">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select> 
                            </fieldset>
                            <div id="user_id_error" style="color: red;"></div>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Status</label>
                                <select name="action_status_id" id="action_status_id"
                                    class="selectpicker form-control" data-live-search="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($actionStatuses as $actionStatus)
                                    <option value="{{$actionStatus->id}}">{{$actionStatus->status}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="action_status_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Priority</label>
                                <select name="action_priority_id" id="action_priority_id"
                                    class="selectpicker form-control" data-live-search="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($actionPriorities as $actionPriority)
                                    <option value="{{$actionPriority->id}}">{{$actionPriority->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="action_priority_id_error" style="color: red;"></div>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Start Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>End Date</label>
                                <input type="date" name="due_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Assigned to others</label>
                                <select class="selectpicker form-control" data-live-search="true" 
                                    name="other_ids[]" id="assignedOtherUsers" multiple>
                                </select> 
                            </fieldset>
                        </div>  
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
       
        $(document).on('change', '#user_id', function () {

            id = $('#user_id').val();
            
            $.ajax({
                url: "work-plan/other/" +  id,
                method: 'GET',
                success: function(data) {

                    var select = $('#assignedOtherUsers');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });

        $('#workPlanForm').on('submit', function (event) {
       
            var ownerValue = $('#user_id').val();
            var statusValue = $('#action_status_id').val();
            var priorityValue = $('#action_priority_id').val();

            if (ownerValue == null) {

                $('#user_id_error').html('Please select an owner!'); 
                return false;
            } 
            if (statusValue == null) {

                $('#action_status_id_error').html('Please select a status!'); 
                return false;
            }
            if (priorityValue == null) {

                $('#action_priority_id_error').html('Please select a Priority!'); 
                return false;
            }
                
            $(this).addClass('was-validated');  
            $('#user_id_error').empty();
            $('#action_status_id_error').empty();
            $('#action_priority_id_error').empty();
            $.ajax({ 
                url: "work-plan",
                method: 'POST',
                success: function(data) {
                }
            });
        });
    }); 
</script>
