@extends('layouts/layoutMaster')

@section('title', 'edit work plan')

@include('layouts.all')

<style>
    label, input{ 
    display: block;
}
label {
    margin-top: 20px;
}
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$actionItem->task}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('work-plan.update', $actionItem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Task</label>
                                <input type="text" name="task" 
                                class="form-control" value="{{$actionItem->task}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Status</label>
                                <select name="action_status_id" data-live-search="true"
                                    class="selectpicker form-control"required>
                                    <option disabled selected>{{$actionItem->ActionStatus->status}}</option>
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
                                <select name="action_priority_id" data-live-search="true" 
                                class="selectpicker form-control" required>
                                    <option disabled selected>{{$actionItem->ActionPriority->name}}</option>
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
                                <label class='col-md-12 control-label'>Date</label>
                                <input name="date" type="date" class="form-control"
                                    value="{{$actionItem->date}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Due Date</label>
                                <input name="due_date" type="date" class="form-control"
                                    value="{{$actionItem->due_date}}">
                            </fieldset>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                    <h5>Assigned to others</h5>
                    </div>
                    @if(count($others) > 0)

                        <table id="othersTable" 
                            class="table table-striped data-table-fbs-equipments my-2">
                            
                            <tbody>
                                @foreach($others as $other)
                                <tr id="otherRow">
                                    <td class="text-center">
                                        {{$other->name}} 
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteOtherUserFromAdmin" id="deleteOtherUserFromAdmin" 
                                            data-id="{{$other->id}}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Add More "Assigned to others"</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="more_other[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">
                                                {{$user->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @else 
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Add "Assigned to others"</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="new_other[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">
                                                {{$user->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3">
                                   {{$actionItem->notes}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>

                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    // delete user
    $('#othersTable').on('click', '.deleteOtherUserFromAdmin',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this user?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteOtherUserFromAdmin') }}",
                    type: 'get',
                    data: {id: id},
                    success: function(response) {
                        if(response.success == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: response.msg,
                                showDenyButton: false,
                                showCancelButton: false,
                                confirmButtonText: 'Okay!'
                            }).then((result) => {
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

</script>
@endsection