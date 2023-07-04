@extends('layouts/layoutMaster')

@section('title', 'refrigerator holders')

@include('layouts.all')

@section('content')


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Refrigerator Holders
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.refrigerator.details')

<div class="container">
    <div class="card my-2">
    <div class="card-header">
            <form method="POST" enctype='multipart/form-data' 
                action="{{ route('refrigerator.export') }}">
                @csrf
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="community"
                                class="form-control">
                                <option disabled selected>Search Community</option>
                                @foreach($communities as $community)
                                <option value="{{$community->english_name}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="public" class="form-control">
                                <option disabled selected>Search Public Structure</option>
                                @foreach($publicCategories as $publicCategory)
                                <option value="{{$publicCategory->id}}">
                                    {{$publicCategory->name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <input type="date" name="date" 
                            class="form-control" title="Data from"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <button class="btn btn-info" type="submit">
                            <i class='fa-solid fa-file-excel'></i>
                            Export Excel
                        </button>
                    </div>
                </div> 
            </form>
        </div>
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createRefrigeratorHolder">
                    Create New Refrigerator Holder
                </button>
                @include('users.refrigerator.create')
            </div>
            <table id="refrigeratorTable" class="table table-striped data-table-refrigerators my-2">
                <thead>
                    <tr>
                        <th class="text-center">Household</th>
                        <th class="text-center">Public Structure</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Refrigerator Type</th>
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

        var table = $('.data-table-refrigerators').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('refrigerator-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'excel',
                }
            ],
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'year', name: 'year'},
                {data: 'refrigerator_type_id', name: 'refrigerator_type_id'},
                {data: 'action'}
            ]
        });

        // Delete record
        $('#refrigeratorTable').on('click', '.deleteRefrigeratorHolder', function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this refrigerator holder?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteRefrigeratorHolder') }}",
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
                                    $('#refrigeratorTable').DataTable().draw();
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

        // View update
        $('#refrigeratorTable').on('click', '.updateRefrigeratorHolder',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // View record details
        $('#refrigeratorTable').on('click', '.viewRefrigeratorHolder',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'refrigerator-user/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) {

                    $('#refrigeratorHolderModalTitle').html(" ");
                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);

                    if(response['household'] != null) {

                        $('#refrigeratorHolderModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['household'].english_name);

                    } else if(response['public'] != null) {

                        $('#refrigeratorHolderModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['public'].english_name);
                    }

                    $('#refrigeratorDate').html(" ");
                    $('#refrigeratorDate').html(response['refrigerator'].date);
                    $('#refrigeratorYear').html(" ");
                    $('#refrigeratorYear').html(response['refrigerator'].year);
                    $('#status').html(" ");
                    $('#status').html(response['refrigerator'].status);
                    $('#refrigeratorType').html(" ");
                    $('#refrigeratorType').html(response['refrigerator'].refrigerator_type_id);
                    $('#refrigeratorIsPaid').html(" ");
                    $('#refrigeratorIsPaid').html(response['refrigerator'].is_paid);
                    $('#refrigeratorPayment').html(" ");
                    $('#refrigeratorPayment').html(response['refrigerator'].payment);
                    $('#refrigeratorReceiveNumber').html(" ");
                    $('#refrigeratorReceiveNumber').html(response['refrigerator'].receive_number);
                    $('#refrigeratorNote').html(" ");
                    $('#refrigeratorNote').html(response['refrigerator'].notes);
                }
            });
        });
    });
</script>
@endsection