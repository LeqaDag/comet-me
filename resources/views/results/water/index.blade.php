@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water results')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Water Quality Results
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
                action="{{ route('quality-result.export') }}">
                @csrf
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <select name="community" class="selectpicker form-control" 
                                data-live-search="true">
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
                            <select name="household" class="selectpicker form-control" 
                                data-live-search="true">
                                <option disabled selected>Search Household</option>
                                @foreach($households as $household)
                                <option value="{{$household->english_name}}">
                                    {{$household->english_name}}
                                </option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <input type="date" name="from_date" 
                            class="form-control" title="Data from"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <input type="date" name="to_date" 
                            class="form-control" title="Data to"> 
                        </fieldset>
                    </div>
                </div> <br>
                <div class="row">
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
            <div class="row">
                @if(Auth::guard('user')->user()->user_type_id == 1 || 
                    Auth::guard('user')->user()->user_type_id == 5 ||
                    Auth::guard('user')->user()->user_type_id == 9 )
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createWaterResult">
                                Add Water Result
                            </button>
                            @include('results.water.create')
                        </fieldset>
                    </div> 
                    <div class="col-xl-9 col-lg-9 col-md-9">
                        <form action="{{route('quality-result.import')}}" method="POST" 
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
                    </div>
                @endif
            </div>
            <table id="waterResultTable" 
                class="table table-striped data-table-water-result my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Holder</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Public Name</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('results.water.details')

<script type="text/javascript">

    $(function () {

        // DataTable
        var table = $('.data-table-water-result').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('quality-result.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household', name: 'household'},
                {data: 'community_name', name: 'community_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'date', name: 'date'},
                {data: 'action'}
            ],
        });

        // View record edit page
        $('#waterResultTable').on('click', '.updateWaterResult',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'quality-result/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#waterResultTable').on('click', '.viewWaterResult', function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'quality-result/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#WaterUserModalTitle').html(" ");
                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);

                    if(response['household'] != null) {

                        $('#WaterUserModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['household'].english_name);

                    } else if(response['public'] != null) {

                        $('#WaterUserModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['public'].english_name);
                    }

                    $('#dateH2oResult').html(" ");
                    $('#dateH2oResult').html(response['result'].date);

                    $('#yearH2oResult').html(" ");
                    $('#yearH2oResult').html(response['result'].year);

                    $('#cfuResult').html(" ");
                    $('#cfuResult').html(response['result'].cfu);
                    if(response['result'].cfu >= 11) $('#cfuResult').css('color', 'red');
                    else if(response['result'].cfu >= 0 && response['result'].cfu <=10 ) $('#cfuResult').css('color', 'green');

                    $('#fciResult').html(" ");
                    $('#fciResult').html(response['result'].fci);
                    if(response['result'].fci <= 0.15 ) $('#fciResult').css('color', 'red');
                    else if(response['result'].fci >= 0.16 && response['result'].fci <=0.3 ) $('#fciResult').css('color', 'green');

                    $('#ecResult').html(" ");
                    $('#ecResult').html(response['result'].ec);

                    $('#phResult').html(" ");
                    $('#phResult').html(response['result'].ph);

                    $('#notesResult').html(" ");
                    $('#notesResult').html(response['result'].notes);
                }
            });
        });

        // Delete record
        $('#waterResultTable').on('click', '.deleteWaterResult',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this record?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteQualityResult') }}",
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
                                    $('#waterResultTable').DataTable().draw();
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

        // View summary depending on the selected year
        $('#selectedYear').on('change', function() {
            year = $(this).val();

            // AJAX request
            $.ajax({
                url: 'quality-result/summary/' + year,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                }
            });
            
        });
    });
</script>
@endsection