@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-public')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Water Public Facilities
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
                    Create New Water Public Facility	
                </button>

                @include('users.water.public.create')
            </div>
            <table id="waterPublicTable" 
                class="table table-striped data-table-water-public my-2">
                <thead>
                    <tr>
                        <th class="text-center">Public Structure</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Installation Year</th>
                        <th class="text-center">H2O Status</th>
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
        // DataTable
        var table = $('.data-table-water-public').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-public.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'installation_year', name: 'installation_year'},
                {data: 'status', name: 'status'},
                {data: 'action'}
            ],
        });

        // View record details
        $('#waterAllSharedUsersTable').on('click','.viewWaterUser',function() {
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
                    $('#statusH2oUser').html(response['h2oStatus'].status);
                    $('#numberBsfUser').html(response['h2oUser'].number_of_bsf);
                    $('#statusBsfUser').html(response['bsfStatus'].name);

                    $('#gridLargeNumber').html(response['gridUser'].grid_integration_large);
                    $('#gridLargeDateNumber').html(response['gridUser'].large_date);
                    $('#gridSmallNumber').html(response['gridUser'].grid_integration_small);
                    $('#gridSmallDateNumber').html(response['gridUser'].small_date);
                    $('#gridDelivery').html(response['gridUser'].is_delivery);
                    $('#gridPaid').html(response['gridUser'].is_paid);
                    $('#gridComplete').html(response['gridUser'].is_complete);
                }
            });
        });

        // Delete record
        $('#waterAllSharedUsersTable').on('click', '.deleteWaterUser',function() {
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
                                    $('#waterAllSharedUsersTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied){

                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });
    });
</script>
@endsection