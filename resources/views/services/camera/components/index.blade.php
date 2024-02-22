@extends('layouts/layoutMaster')

@section('title', 'camera component')

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


<h4 class="py-3 breadcrumb-wrapper mb-4">
All<span class="text-muted fw-light"> Cameras</span> 
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
            @if(Auth::guard('user')->user()->user_type_id != 1 ||
                Auth::guard('user')->user()->user_type_id != 6 ||
                Auth::guard('user')->user()->user_type_id != 10)
                <div>
                    <p class="card-text">
                        <div>
                            <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createCamera">
                                Create New One	
                            </button>
                        </div>
                        @include('services.camera.components.camera.create')
                    </p>
                </div>
            @endif
            <table id="cameraComponentTable" 
                class="table table-striped data-table-camera-component my-2">
                <thead>
                    <tr>
                        <th class="text-center">Model</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>
<h4 class="py-3 breadcrumb-wrapper mb-4">
All<span class="text-muted fw-light"> NVRs</span> 
</h4>

@if(session()->has('success_message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('success_message') }}
        </div>
    </div>
@endif 

<div class="container"> 
    <div class="card my-2">
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id != 1 ||
                Auth::guard('user')->user()->user_type_id != 6 ||
                Auth::guard('user')->user()->user_type_id != 10)
                <div>
                    <p class="card-text">
                        <div>
                            <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createNvr">
                                Create New One	
                            </button>
                        </div>
                        @include('services.camera.components.nvr.create')
                    </p>
                </div>
            @endif
            <table id="nvrComponentTable" 
                class="table table-striped data-table-nvr-component my-2">
                <thead>
                    <tr>
                        <th class="text-center">Model</th>
                        <th class="text-center">Brand</th>
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

        var table = $('.data-table-camera-component').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('camera-component.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'model', name: 'model'},
                {data: 'brand', name: 'brand'},
                {data: 'action' }
            ]
        }); 
                
        // View edit page
        $('#cameraComponentTable').on('click', '.updateCamera',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'camera-component/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url); 
                }
            });
        });

        // Delete record
        $('#cameraComponentTable').on('click', '.deleteCamera', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Camera?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCamera') }}",
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
                                    $('#cameraComponentTable').DataTable().draw();
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


        var table1 = $('.data-table-nvr-component').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('nvr-component.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'model', name: 'model'},
                {data: 'brand', name: 'brand'},
                {data: 'action' }
            ]
        }); 
                
        // View edit page
        $('#nvrComponentTable').on('click', '.updateNvr',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            var newUrl = url.replace('camera-component', 'nvr-component');
            newUrl = newUrl +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'nvr-component/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(newUrl); 
                }
            });
        });

        // Delete record
        $('#nvrComponentTable').on('click', '.deleteNvr', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this NVR?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteNvr') }}",
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
                                    $('#nvrComponentTable').DataTable().draw();
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
    });
</script>
@endsection