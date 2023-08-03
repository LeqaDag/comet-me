
@extends('layouts/layoutMaster')

@section('title', 'all internet systems')

@include('layouts.all')

@section('content')

<style>
    .img-fluid:hover {
        transform: scale(1.5); 
    }
</style>

<div class="py-3 container">
    <h4 class="py-3 breadcrumb-wrapper">
        <span class="text-muted fw-light">Network </span> Diagram
    </h4>
    <img src="/assets/images/diagram.png" class="img-fluid" alt="Responsive image">
</div>
<br>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Internet Systems
</h4>
 
@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('system.internet.details')
 
<div class="container">
    <div class="card my-2">
        <div class="card-body"> 
            @if(Auth::guard('user')->user()->user_type_id == 1 || 
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 6 ||
                Auth::guard('user')->user()->user_type_id == 10)
                <div> 
                    <a type="button" class="btn btn-success" 
                        href="{{url('internet-system', 'create')}}">
                        Create New Internet System	
                    </a>
                    <a type="button" class="btn btn-success" 
                        href="{{url('internet-component', 'create')}}">
                        Create New Internet Components	
                    </a>
                </div>
            @endif
            <table id="internetAllSystemsTable" class="table table-striped data-table-internet-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">System Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">System Type</th>
                        <th class="text-center">Start Year</th>
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

        var table = $('.data-table-internet-system').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('internet-system.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'system_name', name: 'system_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'name', name: 'name'},
                {data: 'start_year', name: 'start_year'},
                {data: 'action'}
            ]
        });
    });

    // View record edit page
    $('#internetAllSystemsTable').on('click', '.updateInternetSystem',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'internet-system/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "_self"); 
            }
        });
    });

    // View record details
    $('#internetAllSystemsTable').on('click', '.viewInternetSystem',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id;

        // AJAX request
        $.ajax({
            url: 'internet-system/' + id + '/showPage',
            type: 'get',
            dataType: 'json',
            success: function(response) {

                window.open(url, "_self"); 
            }
        });
    });

    // Delete record
    $('#internetAllSystemsTable').on('click', '.deleteInternetSystem',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this system?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetSystem') }}",
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
                                $('#internetAllSystemsTable').DataTable().draw();
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