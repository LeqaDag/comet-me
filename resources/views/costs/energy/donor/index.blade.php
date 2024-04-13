@extends('layouts/layoutMaster')

@section('title', 'donor-cost')

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

<p>
    <!-- <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergySystemVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseEnergySystemVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a> -->
    <!-- <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergyDonorCostExport" aria-expanded="false" 
        aria-controls="collapseEnergyDonorCostExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> -->
    <!-- <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseEnergyDonorVisualData collapseEnergyDonorCostExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button> -->
</p> 

<!-- <div class="collapse multi-collapse mb-4" id="collapseEnergyDonorVisualData">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card"> 
                    <div class="card-header">
                        <h5>System By Type</h5>
                    </div>
                    <div class="card-body">
                        <div id="energySystemTypeChart"></div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div> -->

<div class="collapse multi-collapse mb-4" id="collapseEnergyDonorCostExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Energy Donor Cost Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearEnergyDonorFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-cost.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Donor</label>
                                        <select name="donor_id"
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
                                        <label class='col-md-12 control-label'>Installation Year</label>
                                        <select name="year_from" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Filter by Year</option>
                                            @php
                                                $startYear = 2023; // C
                                                $currentYear = date("Y");
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class='col-md-12 control-label'>Download Excel</label>
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
  <span class="text-muted fw-light">All </span> Energy Donor Costs
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif
@include('costs.energy.donor.show')
<div class="container">
    <div class="card my-2">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Donor</label>
                        <select name="donor_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByDonor">
                            <option disabled selected>Choose one...</option>
                            @foreach($donors as $donor)
                                <option value="{{$donor->id}}">{{$donor->donor_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Year</label>
                        <select name="year" class="selectpicker form-control" 
                            data-live-search="true" id="filterByYear">
                            <option disabled selected>Filter by Year</option>
                            @php
                                $startYear = 2023; // C
                                $currentYear = date("Y");
                            @endphp
                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
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
            @if(Auth::guard('user')->user()->user_type_id == 1 )
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createEnergyDonorCost">
                        Create New "Energy Donor" Cost	
                    </button>
                    @include('costs.energy.donor.create')
                </div>
            @endif
            <table id="costDonorTable" class="table table-striped data-table-donor-cost my-2">
                <thead>
                    <tr>
                        <th>Donor</th>
                        <th>Year</th>
                        <th>Fund <i class="menu-icon tf-icons bx bx-shekel"></i></th>
                        <th># of Families</th>
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
        table = $('.data-table-donor-cost').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('donor-cost.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.donor_filter = $('#filterByDonor').val();
                    d.year_filter = $('#filterByYear').val();
                }
            },
            columns: [
                {data: 'donor', name: 'donor'},
                {data: 'year', name: 'year'},
                {data: 'fund', name: 'fund'},
                {data: 'household', name: 'household'},
                {data: 'action'},
            ]
        });
    }

    $(function () {

        DataTableContent();

        $('#filterByYear').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByDonor').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-donor-cost')) {
                $('.data-table-donor-cost').DataTable().destroy();
            }
            DataTableContent();
        });
    });
        
    // Clear Filters for Export
    $('#clearEnergyDonorFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    // View record update page
    $('#costDonorTable').on('click', '.updateEnergyDonorCost',function() {

        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id +'/edit';
        // AJAX request
        $.ajax({
            url: 'donor-cost/' + id + '/editpage',
            type: 'get',
            dataType: 'json',
            success: function(response) {
                window.open(url, "_self"); 
            }
        });
    }); 

    // View record details
    $('#costDonorTable').on('click', '.viewEnergyDonorCost',function() {
        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'donor-cost/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {

                $('#energyDonorCostModalTitle').html('');
                
                $('#donorName').html('');
                $('#donorName').html(response['donor'].donor_name);

                $('#year').html('');
                $('#year').html(response['energyDonorCost'].year);
                $('#estimatedFund').html('');
                $('#estimatedFund').html(response['energyDonorCost'].fund.toLocaleString());
                $('#estimatedHousehold').html('');
                $('#estimatedHousehold').html(response['energyDonorCost'].household);
                $('#commitmentFund').html('');
                $('#commitmentFund').html(response['energyDonorCost'].commitment_fund.toLocaleString());
                $('#commitmentHousehold').html('');
                $('#commitmentHousehold').html(response['energyDonorCost'].commitment_household);
                $('#remainingFund').html('');
                var remainingFund = response['energyDonorCost'].remaining_fund;

                if(remainingFund == 0) {

                    $('#remainingFund').css('color', 'black');
                    $('#remainingFund').html(remainingFund.toLocaleString());
                } else if(remainingFund > 0) {

                    $('#remainingFund').css('color', 'green');
                    $('#remainingFund').html(remainingFund.toLocaleString());
                } else {

                    $('#remainingFund').css('color', 'red');
                    remainingFund = remainingFund *-1;
                    $('#remainingFund').html(remainingFund.toLocaleString());
                }
                
                $('#remainingHousehold').html('');
                var remainingHousehold = response['energyDonorCost'].remaining_household;

                if(remainingHousehold == 0) {

                    $('#remainingHousehold').css('color', 'black');
                    $('#remainingHousehold').html(remainingHousehold.toLocaleString());
                    $("#remainingHouseholdIcon").removeClass('bx bx-user-plus bx-sm me-3');
                    $("#remainingHouseholdIcon").removeClass('bx bx-user-minus bx-sm me-3');
                    $("#remainingHouseholdIcon").addClass('bx bx-user-check bx-sm me-3');
                } else if(remainingHousehold > 0) {

                    $('#remainingHousehold').css('color', 'green');
                    $('#remainingHousehold').html(remainingHousehold.toLocaleString());
                    
                    $("#remainingHouseholdIcon").removeClass('bx bx-user-minus bx-sm me-3');
                    $("#remainingHouseholdIcon").removeClass('bx bx-user-check bx-sm me-3');
                    $("#remainingHouseholdIcon").addClass('bx bx-user-plus bx-sm me-3');
                } else {

                    $('#remainingHousehold').css('color', 'red');
                    remainingHousehold = remainingHousehold *-1;
                    $('#remainingHousehold').html(remainingHousehold.toLocaleString());
                    
                    $("#remainingHouseholdIcon").removeClass('bx bx-user-plus bx-sm me-3');
                    $("#remainingHouseholdIcon").removeClass('bx bx-user-check bx-sm me-3');
                    $("#remainingHouseholdIcon").addClass('bx bx-user-minus bx-sm me-3');
                }
            }
        });
    });


    // Delete record
    $('#costDonorTable').on('click', '.deleteEnergyDonorCost', function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this energy donor cost?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyDonorCost') }}",
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
                                $('#costDonorTable').DataTable().draw();
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

</script>
@endsection