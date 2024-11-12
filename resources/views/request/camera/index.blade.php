@extends('layouts/layoutMaster')

@section('title', 'all requested camera')

@include('layouts.all')

@section('content')
<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseRequestedCameraExport" aria-expanded="false" 
        aria-controls="collapseRequestedCameraExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p>

<div class="collapse multi-collapse container mb-4" id="collapseRequestedCameraExport">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                    Export Requested Camera Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearCameraRequestedFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('camera-request.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community"
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
                                        <label class='col-md-12 control-label'>Status of request</label>
                                        <select name="request_status"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Status of request</option>
                                            @foreach($requestStatuses as $requestStatus)
                                            <option value="{{$requestStatus->id}}">
                                                {{$requestStatus->name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Requested Date</label>
                                        <input type="date" name="date" id="cameraRequestedDateFilter"
                                        class="form-control" title="Data from"> 
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
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Requested Camera
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
        <div class="card-header">
            <div style="margin-top:18px">
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createRequestedCamera">
                    Create New Requested camera
                </button>
                @include('request.camera.create')
            </div>
        </div>
        <div class="card-body">
            <table id="cameraRequestTable" class="table table-striped data-table-camera-request my-2">
                <thead>
                    <tr>
                        <th>Community</th>
                        <th>Request Date</th>
                        <th>Request Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('request.camera.show')

<script type="text/javascript">

    var table;
    function DataTableContent() {
            
        table = $('.data-table-camera-request').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('camera-request.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'community_name', name: 'community_name'},
                {data: 'date', name: 'date'},
                {data: 'name', name: 'name'},
                {data: 'action'}
            ]
        });
    };

    $(function () {

        DataTableContent();
        
        // View record details
        $('#cameraRequestTable').on('click', '.viewCameraRequest',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'camera-request/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) { 

                    $('#requestCameraModalTitle').html(" ");
                    $('#requestCameraModalTitle').html(response['community'].english_name);

                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);
                    $('#cameraRequestedDate').html(" ");
                    $('#cameraRequestedDate').html(response['requestedCamera'].date);
                    $('#cameraRequestedUser').html(" ");
                    if(response['user']) $('#cameraRequestedUser').html(response['user'].name);
                    $('#cameraRequestStatusCase').html(" ");
                    $('#cameraRequestStatusCase').html(response['cameraRequestStatus'].name);
                    $('#referredBy').html(" ");
                    $('#referredBy').html(response['requestedCamera'].referred_by);
                    $('#systemNotesUser').html(" ");
                    $('#systemNotesUser').html(response['requestedCamera'].notes);
                }
            });
        }); 

        // Delete record
        $('#cameraRequestTable').on('click', '.deleteCameraRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this requested camera?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCameraRequest') }}",
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
                                    $('#cameraRequestTable').DataTable().draw();
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

        // View record photos
        $('#cameraRequestTable').on('click', '.updateCameraRequest',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Clear Filters for Export
        $('#clearCameraRequestedFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#CameraRequestedController').val(' ');
        });
    });
</script>
@endsection