@extends('layouts/layoutMaster')

@section('title', 'Data Collection')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Export </span>Households Format
</h4>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5>
                                Export Reports 
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <!-- <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearDataCollectionFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3"> 
                        <div class="card-body">
                        <fieldset class="form-group">
                            <h6 class="text-info">
                                <i class='fa-solid fa-user'></i>
                                Updating Households
                            </h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-household') }}">
                            @csrf
                            <div class="card-body">
                                <fieldset class="form-group">
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-download'></i>
                                        Export households.csv
                                    </button>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-5">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-info" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Household Updating"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3"> 
                        <div class="card-body">
                        <fieldset class="form-group">
                            <h6 class="text-info">
                                <i class='fa-solid fa-user'></i>
                                Updating AC Households
                            </h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-all') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-info" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "AC Survey"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3"> 
                        <div class="card-body">
                        <fieldset class="form-group">
                            <h6 class="text-info">
                                <i class='fa-solid fa-user'></i>
                                Updating Requested Households
                            </h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-requested-household') }}">
                            @csrf
                            <div class="card-body">
                                <fieldset class="form-group">
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-download'></i>
                                        Export requested_households.csv
                                    </button>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-5">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-requested') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-info" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Requested Households"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3"> 
                        <div class="card-body">
                        <fieldset class="form-group">
                            <h6 class="text-success">
                                <i class='fa-solid fa-home'></i>
                                Updating Communities/Compounds
                            </h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-all-community') }}">
                            @csrf
                            <div class="card-body">
                                <fieldset class="form-group">
                                    <button class="btn btn-success" type="submit">
                                        <i class='fa-solid fa-download'></i>
                                        Export communities.csv
                                    </button>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-5">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-communities') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-success" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Community / Compound"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3"> 
                        <div class="card-body">
                        <fieldset class="form-group">
                            <h6 class="">
                                <i class='fa-solid fa-file'></i>
                                Export Other
                            </h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-incident') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-danger" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Incidents"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-displacement') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-warning" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Displacement"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3"> 
                        <div class="card-body">
                        <fieldset class="form-group">
                            <h6 class="">
                               
                            </h6>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-agriculture') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-success" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Agriculture"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('data-collection.export-workshop') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <fieldset class="form-group">
                                        <button class="btn btn-success" type="submit">
                                            <i class='fa-solid fa-download'></i>
                                            Export Excel format "Workshops"
                                        </button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>  
        </div>
    </div> 
</div>

<div class="container">
    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif


    <h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:50px">
        <i class='fa-solid fa-home text-primary'></i><span class="text-muted fw-light"> Import </span>Community/Compound
    </h4>

    <!-- File Upload Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('data-collection.import-community')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                    @error('excel_file')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div> <br>
                <button type="submit" class="btn btn-success btn-block">
                    
                    <i class='fa-solid fa-upload'></i>
                    Proccess
                </button>
            </form>
        </div>
    </div>


    <h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:50px">
        <i class='fa-solid fa-user text-info'></i><span class="text-muted fw-light"> Import </span>Households Details
    </h4>


    <!-- File Upload Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('data-collection.import')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                    @error('excel_file')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div> <br>
                <button type="submit" class="btn btn-success btn-block">
                    
                    <i class='fa-solid fa-upload'></i>
                    Proccess
                </button>
            </form>
        </div>
    </div>

    <h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:50px">
        <i class='fa-solid fa-users text-success'></i><span class="text-muted fw-light"> Import </span>AC Households Details
    </h4>

    <!-- File Upload Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('data-collection.import-ac')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                    @error('excel_file')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div> <br>
                <button type="submit" class="btn btn-success btn-block">
                    
                    <i class='fa-solid fa-upload'></i>
                    Proccess
                </button>
            </form>
        </div>
    </div>

    <h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:50px">
        <i class='fa-solid fa-question text-warning'></i><span class="text-muted fw-light"> Import </span>Requested Households
    </h4>

    <!-- File Upload Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('data-collection.import-requested')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                    @error('excel_file')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div> <br>
                <button type="submit" class="btn btn-success btn-block">
                    
                    <i class='fa-solid fa-upload'></i>
                    Proccess
                </button>
            </form>
        </div>
    </div>

    <h4 class="py-3 breadcrumb-wrapper mb-4" style="margin-top:50px">
        <i class='fa-solid fa-circle text-danger'></i><span class="text-muted fw-light"> Import </span>Displaced Households
    </h4>

    <!-- File Upload Form -->
    <div class="card">
        <div class="card-body">
            <form action="{{route('data-collection.import-displaced')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="excel_file">Choose Excel File</label>
                    <input type="file" name="excel_file" class="form-control-file" id="excel_file"required>
                    @error('excel_file')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div> <br>
                <button type="submit" class="btn btn-success btn-block">
                    
                    <i class='fa-solid fa-upload'></i>
                    Proccess
                </button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        // Clear Filters for Export
        $('#clearDataCollectionFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
        });
    });
</script>
@endsection