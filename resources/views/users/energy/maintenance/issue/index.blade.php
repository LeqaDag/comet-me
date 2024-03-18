@extends('layouts/layoutMaster')

@section('title', 'energy issues')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergyIssuesExport" aria-expanded="false" 
        aria-controls="collapseEnergyIssuesExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseEnergyIssuesExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Energy Issues Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-issue.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
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
  <span class="text-muted fw-light">All </span> Energy Issues 
</h4>
 
@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.energy.maintenance.issue.edit')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">

                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 7 ||
                    Auth::guard('user')->user()->user_type_id == 4 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createIssueEnergy">
                            Create New Energy Issue	
                        </button>
                        @include('users.energy.maintenance.issue.create')
                    </div>
                @endif
            </div>

            <table id="issueEnergyTable" class="table table-striped data-table-energy-issue my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
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

        var table = $('.data-table-energy-issue').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('energy-issue.index')}}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#issueEnergyTable').on('click', '.deleteEnergyIssue',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Issue?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyIssue') }}",
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
                                $('#issueEnergyTable').DataTable().draw();
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
    $('#issueEnergyTable').on('click', '.updateEnergyIssue',function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/energy-issue/get/' + id,
            method: 'GET',
            data: {id: id},
            success: function (data) {
 
                $('#issueId').val(data.id);
                $('#issueEnglishName').val(data.english_name);
                $('#issueArabicName').val(data.arabic_name);
                $('#issueNotes').val(data.notes);
                
                $('#updateEnergyIssueModal').modal('show');
                
                var form = $('#updateIssueForm');

                form.attr('action', form.attr('action').replace('__ID__', id));

            },
            error: function (error) {
                console.log('Error fetching record details: ', error);
            }
        });
    });
</script>
@endsection