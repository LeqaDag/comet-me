<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>  

<div id="createNvr" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New NVR
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{url('nvr-component')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <table class="table table-bordered" id="addNvrButton">
                                <tr>
                                    <th>NVR Model</th>
                                    <th>NVR Brand</th>
                                    <th>Options</th>
                                </tr>
                                <tr> 
                                    <td>
                                        <input type="text" name="nvr_models[0][subject]" 
                                            placeholder="Model" class="target_point form-control" 
                                            data-id="0" required/>
                                        </td>
                                    <td>
                                        <input type="text" name="nvr_brands[0][subject]" 
                                        placeholder="Brand" class="target_point form-control" 
                                        data-id="0" required/>
                                    </td>
                                    <td>
                                        <button type="button" name="add" id="addRemoveNewNvrButton" 
                                        class="btn btn-outline-primary">
                                            Add More
                                        </button>
                                    </td>
                                </tr>
                            </table>
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

    var j = 0;
    $(document).on('click', '#addRemoveNewNvrButton', function () {
        ++j;
        $("#addNvrButton").append('<tr>' +
            '<td><input required class="form-control" data-id="' + j + '" name="nvr_models[' + j + '][subject]" placeholder="Model"></td>' +
            '<td><input required class="form-control" data-id="' + j + '" name="nvr_brands[' + j + '][subject]" placeholder="Brand"></td>' +
            '<td><button type="button" class="btn btn-outline-danger removeNvr">Delete</button></td>' +
            '</tr>'
        );
    });

    $(document).on('click', '.removeNvr', function () {
        $(this).parents('tr').remove();
    });

});

</script>