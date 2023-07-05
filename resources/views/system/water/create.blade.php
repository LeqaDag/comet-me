@extends('layouts/layoutMaster')

@section('title', 'create water system')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }

    .dropdown-toggle{
        height: 40px;
        
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Water System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('water-system')}}" enctype="multipart/form-data" >
                @csrf

                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row"> 
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water System Type</label>
                            <select name="water_system_type_id" class="form-control"
                                    id="waterSystemTypeChange" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($waterSystemTypes as $waterSystemTypes)
                                    <option value="{{$waterSystemTypes->id}}">
                                        {{$waterSystemTypes->type}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communityWaterSystem"
                                required >
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" 
                            class="form-control" required>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Start Year</label>
                            <input type="number" name="year" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="1">
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>

    $(document).on('change', '#waterSystemTypeChange', function () {

        var waterType = $(this).val();

        if(waterType == 4) $('#communityWaterSystem').disabled = false;
    });
</script>