@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-users')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water System Holders
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
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createWaterUser">
                    Create New Water System Holder	
                </button>

                @include('users.water.create')
            </div>
            <table id="waterAllUsersTable" 
                class="table table-striped data-table-water-all-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Number of H2O</th>
                        <th class="text-center">H2O Status</th>
                        <th class="text-center">Number of Grid Large</th>
                        <th class="text-center">Number of Grid Small</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('users.water.details')

<script type="text/javascript">

    $(function () {

        // DataTable
        var table = $('.data-table-water-all-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-water.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'number_of_h20', name: 'number_of_h20'},
                {data: 'status', name: 'status'},
                {data: 'grid_integration_large', name: 'grid_integration_large'},
                {data: 'grid_integration_small', name: 'grid_integration_small' },
                {data: 'action'}
            ],
        });
 
        // Update record
        $('#waterAllUsersTable').on('click','.updateWaterUser',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'all-water/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self");
                }
            });
        });

        // View record details
        $('#waterAllUsersTable').on('click','.viewWaterUser',function() {
            var id = $(this).data('id');
            
            // AJAX request
            $.ajax({
                url: 'water-user/' + id,
                type: 'get', 
                dataType: 'json',
                success: function(response) {
                    $('#WaterUserModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);
                    $('#communityUser').html(response['community'].english_name);
                    $('#numberH2oUser').html(response['h2oUser'].number_of_h20);
                    $('#dateH2oUser').html(response['h2oUser'].h2o_request_date);
                    $('#yearH2oUser').html(response['h2oUser'].installation_year);
                    $('#statusH2oUser').html(response['h2oStatus'].status);
                    $('#numberBsfUser').html(response['h2oUser'].number_of_bsf);
                    $('#statusBsfUser').html(response['bsfStatus'].name); 

                    
                    if(response['gridUser'] != null) {
                        $('#dateGridUser').append(" ");
                        $('#dateGridUser').html(response['gridUser'].request_date);
                        $('#gridLargeNumber').append(" ");
                        $('#gridLargeNumber').html(response['gridUser'].grid_integration_large);
                        $('#gridLargeDateNumber').append(" ");
                        $('#gridLargeDateNumber').html(response['gridUser'].large_date);
                        $('#gridSmallNumber').append(" ");
                        $('#gridSmallNumber').html(response['gridUser'].grid_integration_small);
                        $('#gridSmallDateNumber').append(" ");
                        $('#gridSmallDateNumber').html(response['gridUser'].small_date);
                        $('#gridDelivery').append(" ");
                        $('#gridDelivery').html(response['gridUser'].is_delivery);
                        $('#gridPaid').append(" ");
                        $('#gridPaid').html(response['gridUser'].is_paid);
                        $('#gridComplete').append(" ");
                        $('#gridComplete').html(response['gridUser'].is_complete);
                    }
                }
            });

            $('#closeDetailsModel').on('click', function() {
                
                $('#waterAllUsersTable').DataTable().draw();
            });

        });


        // Delete record
        $('#waterAllUsersTable').on('click', '.deleteWaterUser',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this user?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterUser') }}",
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
                                    $('#waterAllUsersTable').DataTable().draw();
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