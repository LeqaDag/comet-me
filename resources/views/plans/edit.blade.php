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

@endsection