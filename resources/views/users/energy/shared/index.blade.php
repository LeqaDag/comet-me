@extends('layouts/layoutMaster')

@section('title', 'shared energy users')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Household Meters 
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
                    data-bs-toggle="modal" data-bs-target="#createHouseholdMeter">
                    Create New Household Meter	
                </button>
                @include('users.energy.shared.create')
            </div>
            <table id="allHouseholdMeterTable" class="table table-striped data-table-energy-shared my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy User</th>
                        <th class="text-center">Household</th>
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
                {data: 'user_name', name: 'user_name'},
                {data: 'household_name', name: 'household_name'},
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