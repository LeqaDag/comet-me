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

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Export Filter</h5>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('public-structure.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="region"
                                        class="selectpicker form-control" data-live-search="true">
                                        <option disabled selected>Search Region</option>
                                        @foreach($regions as $region)
                                        <option value="{{$region->id}}">
                                            {{$region->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
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
                                    <select name="public" class="selectpicker form-control" 
                                        data-live-search="true">
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

@include('public.show')

<div class="container">
    <div class="card my-2">
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
                url: "{{ route('public-structure.index') }}",
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
        $('#publicStructureTable').on('click', '.viewPublicStructure', function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'public-structure/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) {

                    $('#publicStructureModalTitle').html('');
                    $('#publicStructureModalTitle').html(response['publicStructure'].english_name);
                    $('#englishNamePublic').html('');
                    $('#englishNamePublic').html(response['publicStructure'].english_name);
                    $('#arabicNamePublic').html('');
                    $('#arabicNamePublic').html(response['publicStructure'].english_name);
                    $('#communityName').html('');
                    $('#communityName').html(response['community'].english_name);
                    $('#publicNotes').html('');
                    $('#publicNotes').html(response['publicStructure'].notes);
                }
            });
        });

        // View edit page
        $('#publicStructureTable').on('click', '.updatePublicStructure',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#publicStructureTable').on('click', '.deletePublicStructure',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this public?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deletePublicStructure') }}",
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
                                    $('#publicStructureTable').DataTable().draw();
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