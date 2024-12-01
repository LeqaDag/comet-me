@extends('layouts/layoutMaster')

@section('title', 'refrigerator issues')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseRefrigeratorIssuesExport" aria-expanded="false" 
        aria-controls="collapseRefrigeratorIssuesExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p>  

<div class="collapse multi-collapse mb-4" id="collapseRefrigeratorIssuesExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                    Export Refrigerator Issues Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('refrigerator-issue.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select class="selectpicker form-control" 
                                            data-live-search="true" name="action_name">
                                            <option disabled selected>Choose Action...</option>
                                            @foreach($refrigeratorActions as $refrigeratorAction)
                                                <option value="{{$refrigeratorAction->id}}">{{$refrigeratorAction->english_name}}</option>
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
  <span class="text-muted fw-light">All </span> Refrigerator Issues 
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
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Action</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" id="filterByAction">
                                <option disabled selected>Choose one...</option>
                                @foreach($refrigeratorActions as $refrigeratorAction)
                                    <option value="{{$refrigeratorAction->id}}">{{$refrigeratorAction->english_name}}</option>
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

            <div class="card-header">

                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 2 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createIssueRefrigerator">
                            Create New Refrigerator Issue	
                        </button>
                        @include('users.refrigerator.maintenance.issue.create')
                    </div>
                @endif
            </div>

            <table id="issueRefrigeratorTable" class="table table-striped data-table-refrigerator-issue my-2">
                <thead>
                    <tr>
                        <th class="text-center">Issue English</th>
                        <th class="text-center">Issue Arabic</th>
                        <th class="text-center">Action</th>
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

    var table;

    function DataTableContent() {

        table = $('.data-table-refrigerator-issue').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('refrigerator-issue.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.action_filter = $('#filterByAction').val();
                    d.issue_type_filter = $('#filterByIssueType').val();
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'refrigerator_action', name: 'refrigerator_action'},
                {data: 'action'},
            ]
        });
    }

    $(function () {

        DataTableContent();

        $('#filterByAction').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByIssueType').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByInstallationDate').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-refrigerator-issue')) {
                $('.data-table-refrigerator-issue').DataTable().destroy();
            }
            DataTableContent();
        });
    });

    // Delete record
    $('#issueRefrigeratorTable').on('click', '.deleteRefrigeratorIssue',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Issue?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteRefrigeratorIssue') }}",
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
                                $('#issueRefrigeratorTable').DataTable().draw();
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
    $('#issueRefrigeratorTable').on('click', '.updateRefrigeratorIssue',function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/refrigerator-issue/get/' + id,
            method: 'GET',
            data: {id: id},
            success: function (data) {
 
                $('#issueId').val(data.id);
                $('#issueEnglishName').val(data.english_name);
                $('#issueArabicName').val(data.arabic_name);
                $('#issueNotes').val(data.notes);
                
                $('#updateRefrigeratorIssueModal').modal('show');
                
                var form = $('#updateIssueForm');

                form.attr('action', form.attr('action').replace('__ID__', id));

            },
            error: function (error) {
                console.log('Error fetching record details: ', error);
            }
        });
    });

    // View update
    $('#issueRefrigeratorTable').on('click', '.updateRefrigeratorIssue',function() {
        var id = $(this).data('id');

        var url = window.location.href; 
        
        url = url +'/'+ id +'/edit';
        window.open(url, "_self"); 
    });
</script>
@endsection