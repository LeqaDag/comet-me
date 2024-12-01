@extends('layouts/layoutMaster')

@section('title', 'edit internet issue')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label {
        margin-top: 20px;
    } 
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> 
        {{$internetIssue->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('internet-issue.update', $internetIssue->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (English)</label>
                            <input type="text" name="english_name" class="form-control" 
                                value="{{$internetIssue->english_name}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action (Arabic)</label>
                            <input type="text" name="arabic_name" class="form-control"
                                value="{{$internetIssue->arabic_name}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Action Category</label>
                            <select name="action_category_id" data-live-search="true"
                                class="selectpicker form-control" id="internetActionCategoryEdit">
                                @if($internetIssue->internet_action_id)
                                    <option value="{{$internetIssue->InternetAction->ActionCategory->id}}">
                                        {{$internetIssue->InternetAction->ActionCategory->english_name}}
                                    </option>
                                @endif 
                                @foreach($internetCategories as $actionCategory)
                                    <option value="{{$actionCategory->id}}">
                                        {{$actionCategory->english_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>internet Action</label>
                            <select name="internet_action_id" data-live-search="true"
                                class="selectpicker form-control" id="internetActionSelectedEdit">
                                @if($internetIssue->internet_action_id)
                                    <option value="{{$internetIssue->InternetAction->id}}">
                                        {{$internetIssue->InternetAction->english_name}}
                                    </option>
                                @endif 
                                @foreach($internetActions as $internetAction)
                                    <option value="{{$internetAction->id}}">
                                        {{$internetAction->english_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$internetIssue->notes}}
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
    $(document).ready(function() {

        $(document).on('change', '#internetActionCategoryEdit', function () {

            category_id = $(this).val();
            $.ajax({
                url: "/internet-issue/get_by_action_category/" +  category_id,
                method: 'GET',
                success: function(data) {

                    var select = $('#internetActionSelectedEdit');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    });
</script>
@endsection