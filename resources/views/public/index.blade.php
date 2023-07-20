@extends('layouts/layoutMaster')

@section('title', 'Public Structures')

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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Public Structures
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
            <form method="POST" enctype='multipart/form-data' 
                action="{{ route('public.export') }}">
                @csrf
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="community"
                                class="selectpicker form-control" data-live-search="true">
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
                            <select name="donor"
                                class="selectpicker form-control" data-live-search="true">
                                <option disabled selected>Search Donor</option>
                                @foreach($donors as $donor)
                                <option value="{{$donor->id}}">
                                    {{$donor->donor_name}}
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||  
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 5 ||
                Auth::guard('user')->user()->user_type_id == 6)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createPublicStructure">
                        Add Public Structure
                    </button>
                    @include('public.create')
                </div>
            @endif
            <table id="publicStructureTable" class="table table-striped data-table-public-structure my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">

    $(function () {

        var table = $('.data-table-public-structure').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('public.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            dom: 'Blfrtip',
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#publicStructureTable').on('click', '.viewMgIncident', function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'mg-incident/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) {
                    $('#mgIncidentModalTitle').html('');
                    $('#mgIncidentModalTitle').html(response['energySystem'].name);
                    $('#mgSystem').html('');
                    $('#mgSystem').html(response['energySystem'].name);
                    $('#communityName').html('');
                    $('#communityName').html(response['community'].english_name);
                    $('#incidentDate').html('');
                    $('#incidentDate').html(response['mgIncident'].date);
                    $('#mgIncidentStatus').html('')
                    $('#mgIncidentStatus').html(response['mgStatus'].name);
                    $('#incidentYear').html('');
                    $('#incidentYear').html(response['mgIncident'].year);
                    $('#incidentType').html('');
                    $('#incidentType').html(response['incident'].english_name);
                    $('#incidentNotes').html('');
                    $('#incidentNotes').html(response['mgIncident'].notes);
                }
            });
        });

        // View record photos
        $('#publicStructureTable').on('click', '.updateMgIncident',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#publicStructureTable').on('click', '.deleteMgIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteMgIncident') }}",
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
                                    $('#mgIncidentsTable').DataTable().draw();
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
    });
</script>
@endsection