@extends('layouts/layoutMaster')

@section('title', 'all young holders')

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
  <span class="text-muted fw-light">All </span> Young Holders
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
                    data-bs-toggle="modal" data-bs-target="#linkYoungHolder">
                    Link the Young Holder to the Main User
                </button>
                @include('young.create')
            </div>
        </div>
        <div class="card-body">
            <table id="youngHolderTable" class="table table-striped data-table-young-holder my-2">
                <thead>
                    <tr>
                        <th>Young Holder</th>
                        <th>Main Energy User</th>
                        <th>Community</th>
                        <th>Fake Meter Number</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    var table;
    function DataTableContent() {
            
        table = $('.data-table-young-holder').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('young-holder.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'young_holder', name: 'young_holder'},
                {data: 'main_user', name: 'main_user'},
                {data: 'community', name: 'community'},
                {data: 'fake_meter_number', name: 'fake_meter_number'},
                {data: 'action'}
            ]
        });
    };

    $(function () {

        DataTableContent();


        // Delete record
        $('#youngHolderTable').on('click', '.deleteYoungHolderHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this young holder?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteyoungHolder') }}",
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
                                    $('#youngHolderTable').DataTable().draw();
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


        // Clear Filters for Export
        $('#clearyoungHolderedFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#youngHolderedDateFilter').val(' ');
        });
    });
</script>
@endsection