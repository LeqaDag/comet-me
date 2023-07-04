@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-public')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Shared H2O Public Facilities
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
                Auth::guard('user')->user()->user_type_id == 5 ||
                Auth::guard('user')->user()->user_type_id == 11)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createWaterSharedPublic">
                        Create New Shared H2O Public Facility	
                    </button>

                    @include('users.water.public.create_share')
                </div>
            @endif
            <table id="waterPublicTable" 
                class="table table-striped data-table-water-public my-2">
                <thead>
                    <tr>
                        <th>Sub Public Structure</th>
                        <th>H2O Public Structure</th>
                        <th>Community</th>
                        @if(Auth::guard('user')->user()->user_type_id == 1 ||
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11)
                            <th>Options</th>
                        @else
                            <th></th>
                        @endif
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
                {data: 'shared', name: 'shared'},
                {data: 'english_name', name: 'english_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'action'}
            ],
        });

        // Delete record
        $('#waterPublicTable').on('click', '.deleteSharedPublic',function() {
            var id = $(this).data('id');

            Swal.fire({ 
                icon: 'warning',
                title: 'Are you sure you want to delete this shared public?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteSharedPublic') }}",
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
                                    $('#waterPublicTable').DataTable().draw();
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