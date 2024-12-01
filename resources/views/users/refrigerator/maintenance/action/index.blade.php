@extends('layouts/layoutMaster')

@section('title', 'refrigerator actions')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseRefrigeratorActionsExport" aria-expanded="false" 
        aria-controls="collapseRefrigeratorActionsExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 
 
<div class="collapse multi-collapse mb-4" id="collapseRefrigeratorActionsExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Refrigerator Actions Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearRefrigeratorActionFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div> 
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('refrigerator-action.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select class="selectpicker form-control" 
                                            data-live-search="true" name="action_category">
                                            <option disabled selected>Choose category...</option>
                                            @foreach($actionCategories as $actionCategory)
                                                <option value="{{$actionCategory->id}}">{{$actionCategory->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <button class="btn btn-info" type="submit">
                                            <i class='fa-solid fa-file-excel'></i>
                                            Export Excel
                                        </button>
                                    </fieldset>
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
  <span class="text-muted fw-light">All </span> Refrigerator Actions 
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
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Category</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="filterByCategory">
                            <option disabled selected>Choose one...</option>
                            @foreach($actionCategories as $actionCategory)
                                <option value="{{$actionCategory->id}}">{{$actionCategory->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButton">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="card-header">

                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 2)
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createActionRefrigerator">
                            Create New Refrigerator Action	
                        </button>
                        @include('users.refrigerator.maintenance.action.create')
                    </div>
                @endif
            </div>

            <table id="actionRefrigeratorTable" class="table table-striped data-table-refrigerator-action my-2">
                <thead>
                    <tr>
                        <th>Action English</th>
                        <th>Action Arabic</th>
                        <th>Category</th>
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

        table = $('.data-table-refrigerator-action').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('refrigerator-action.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.category_filter = $('#filterByCategory').val();
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'category', name: 'category'},
                {data: 'action'},
            ]
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByCategory').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByInstallationDate').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-refrigerator-action')) {
                $('.data-table-refrigerator-action').DataTable().destroy();
            }
            DataTableContent(); 
        });
    });

    // Delete record
    $('#actionRefrigeratorTable').on('click', '.deleteRefrigeratorAction',function() {
        var id = $(this).data('id');

        Swal.fire({ 
            icon: 'warning',
            title: 'Are you sure you want to delete this Action?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteRefrigeratorMainAction') }}",
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
                                $('#actionRefrigeratorTable').DataTable().draw();
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
    $('#clearRefrigeratorActionFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // View update
    $('#actionRefrigeratorTable').on('click', '.updateRefrigeratorAction',function() {
        var id = $(this).data('id');

        var url = window.location.href; 
        
        url = url +'/'+ id +'/edit';
        window.open(url, "_self"); 
    });

</script>
@endsection