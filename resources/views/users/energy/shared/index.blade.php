@extends('layouts/layoutMaster')

@section('title', 'shared energy users')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Export Filter</h5>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('household-meter.export') }}">
                    @csrf
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Community</label>
                                    <select name="community_id"
                                        class="selectpicker form-control" data-live-search="true">
                                        <option disabled selected>Search Community</option>
                                        @foreach($communities as $community)
                                            <option value="{{$community->id}}">
                                                {{$community->english_name}}
                                            </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>New/MISC/Grid extension</label>
                                    <select name="misc" id="selectedWaterSystemType" 
                                        class="form-control" required>
                                        <option disabled selected>Choose one...</option>
                                        @foreach($installationTypes as $installationType)
                                            <option value="{{$installationType->id}}">
                                                {{$installationType->type}}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Installation date from</label>
                                    <input type="date" class="form-control" name="date_from">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Installation date to</label>
                                    <input type="date" class="form-control" name="date_to">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <label class='col-md-12 control-label'>Download Excel</label>
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
    </div> 
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Shared Users
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createHouseholdMeter">
                        Create New Shared User
                    </button>
                    @include('users.energy.shared.create')
                </div>
            @endif
            <table id="allHouseholdMeterTable" class="table table-striped data-table-energy-shared my-2">
                <thead>
                    <tr>
                        <th class="text-center">Shared User (Household)</th>
                        <th class="text-center">Meter Holder</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        
        var table = $('.data-table-energy-shared').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('household-meter.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'user_name', name: 'user_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#allHouseholdMeterTable').on('click', '.deleteAllHouseholdMeterUser',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this household meter?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteHouseholdMeter') }}",
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
                                $('#allHouseholdMeterTable').DataTable().draw();
                            });
                        } else {

                            alert("Invalid ID.");
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