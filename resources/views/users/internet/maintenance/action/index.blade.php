@extends('layouts/layoutMaster')

@section('title', 'internet actions')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseInternetActionsExport" aria-expanded="false" 
        aria-controls="collapseInternetActionsExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseInternetActionsExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Internet Actions Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearInternetActionFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('internet-action.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="internet_issue_id" class="selectpicker form-control"
                                            data-live-search="true">
                                            <option disabled selected>Search Issue</option>
                                            @foreach($internetIssues as $internetIssue)
                                                <option value="{{$internetIssue->id}}">
                                                    {{$internetIssue->english_name}}
                                                </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="internet_issue_type_id" class="selectpicker form-control"
                                            data-live-search="true">
                                            <option disabled selected>Search Issue Type</option>
                                            @foreach($internetIssueTypes as $internetIssueType)
                                                <option value="{{$internetIssueType->id}}">
                                                    {{$internetIssueType->type}}
                                                </option>
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
  <span class="text-muted fw-light">All </span> Internet Issues 
</h4>
 
@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.internet.maintenance.action.edit')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">

                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 10 ||
                    Auth::guard('user')->user()->user_type_id == 6 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createActionInternet">
                            Create New Internet Action	
                        </button>
                        @include('users.internet.maintenance.action.create')
                    </div>
                @endif
            </div>

            <table id="actionInternetTable" class="table table-striped data-table-internet-action my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Issue</th>
                        <th class="text-center">Type</th>
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

        var table = $('.data-table-internet-action').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('internet-action.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'issue', name: 'issue'},
                {data: 'type', name: 'type'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#actionInternetTable').on('click', '.deleteInternetAction',function() {
        var id = $(this).data('id');

        Swal.fire({ 
            icon: 'warning',
            title: 'Are you sure you want to delete this Action?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteInternetMainAction') }}",
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
                                $('#actionInternetTable').DataTable().draw();
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
    $('#clearInternetActionFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // View update
    $('#actionInternetTable').on('click', '.updateInternetAction',function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/internet-action/get/' + id,
            method: 'GET',
            data: {id: id},
            success: function (data) {
                
                $('#actionId').val(data.id);
                $('#actionEnglishName').val(data.english_name);
                $('#actionArabicName').val(data.arabic_name);
                $('#actionNotes').val(data.notes);
                
                $('#updateInternetActionModal').modal('show');
                
                var form = $('#updateActionForm');

                form.attr('action', form.attr('action').replace('__ID__', id));

            },
            error: function (error) {
                console.log('Error fetching record details: ', error);
            }
        });
    });

</script>
@endsection