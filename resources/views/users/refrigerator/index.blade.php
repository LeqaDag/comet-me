@extends('layouts/layoutMaster')

@section('title', 'refrigerator holders')

@include('layouts.all')

@section('content')
<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseRefrigeratorHolderExport" aria-expanded="false" 
        aria-controls="collapseRefrigeratorHolderExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p>

<div class="collapse multi-collapse mb-4" id="collapseRefrigeratorHolderExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Export Refrigerator Holder Report
                            <i class='fa-solid fa-file-excel text-info'></i>
                        </h5>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('refrigerator.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="community"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Community</option>
                                            @foreach($communities as $community)
                                            <option value="{{$community->english_name}}">
                                                {{$community->arabic_name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="public" class="selectpicker form-control" data-live-search="true">
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
                                        <input type="date" name="date_from" 
                                        class="form-control" title="Data from"> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <input type="date" name="date_to" 
                                        class="form-control" title="Data to"> 
                                    </fieldset>
                                </div> <br> <br> <br>
                                <div class="col-xl-3 col-lg-3 col-md-3">
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
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||  
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 7 )
           <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createRefrigeratorHolder">
                    Create New Refrigerator Holder
                </button>
                @include('users.refrigerator.create')
            </div>
            @endif

            @if(Auth::guard('user')->user()->user_type_id == 1)
            <div>
                <form action="{{route('refrigerator.import')}}" method="POST" 
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-xl-5 col-lg-5 col-md-5">
                        <fieldset class="form-group">
                            <input name="file" type="file"
                                class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button class="btn btn-success" type="submit">Import File</button>
                    </div>
                </form>
            <div>
            @endif
            <table id="refrigeratorTable" class="table table-striped data-table-refrigerators my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy Holder</th>
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
                {data: 'holder'},
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
                    $('#phoneNumberUser').html(" ");
                    
                    if(response['household'] != null) {

                        $('#refrigeratorHolderModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['household'].english_name);
                        $('#phoneNumberUser').html(" ");
                        $('#phoneNumberUser').html(response['household'].phone_number);

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
                    $('#refrigeratorNote').html(" ");
                    $('#refrigeratorNote').html(response['refrigerator'].notes);
                    $('#refrigeratorReceiveNumber').html(" ");
                    if(response['refrigeratorHolderNumber']) {

                        $('#refrigeratorReceiveNumber').html(response['refrigeratorHolderNumber'][0].receive_number);
                    }
                }
            });
        });
    });
</script>
@endsection