<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 

<div id="createActionInternet" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Internet Action
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="internetActionForm"
                    action="{{url('internet-action')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" class="form-control"
                                    required> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                    required> 
                            </fieldset>
                        </div> 
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Issue</label>
                                <select name="internet_issue_id" id="internetIssueValue"
                                    class="selectpicker form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($internetIssues as $internetIssue)
                                    <option value="{{$internetIssue->id}}">
                                        {{$internetIssue->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="internet_issue_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

        $('#internetActionForm').on('submit', function (event) {

            var issueValue = $('#internetIssueValue').val();

            if (issueValue == null) {

                $('#internet_issue_id_error').html('Please select an issue!'); 
                return false;
            } else if (issueValue != null){

                $('#internet_issue_id_error').empty();
            }

            $(this).addClass('was-validated'); 
            $('#internet_issue_id_error').empty();

            this.submit();
        });
    });
</script>