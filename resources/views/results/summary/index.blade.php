@extends('layouts/layoutMaster')

@section('title', 'water summary')

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

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseWaterSummaryVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseWaterSummaryVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseWaterSummaryExport" aria-expanded="false" 
        aria-controls="collapseWaterSummaryExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseWaterSummaryVisualData collapseWaterSummaryExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseWaterSummaryVisualData">
    <div class="container mb-4">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <fieldset class="form-group">
                    <label class='col-md-12 control-label'>Year</label>
                    <select name="water_type" id="selectedYear" 
                        class="form-control" required>
                        <option disabled selected>Choose one...</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                    </select>
                </fieldset>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
                <fieldset class="form-group">
                    <label class='col-md-12 control-label'>Month</label>
                    <select name="status" id="selectedMonth" 
                    class="form-control" disabled>
                        <option disabled selected>Choose one...</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="container mb-4" id="cfuWaterResult" style="visiblity:hidden; display:none">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-header">
                            <h5 id="cfuWaterResultTitle"></h5>
                        </div>
                        <div class="panel-body">
                            <div id="cfuWaterResultBody" style="height:400px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="collapse multi-collapse mb-4" id="collapseWaterSummaryExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Export Water Quality Summary Report
                            <i class='fa-solid fa-file-excel text-info'></i>
                        </h5>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('water-summary.export') }}">
                        @csrf
                        <div class="card-body"> 
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
  <span class="text-muted fw-light">Summary </span> of regular monitoring program
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
            <table id="waterResultSummaryTable" 
                class="table table-striped data-table-water-summary my-2">
                <thead>
                    <tr> 
                        <th class="text-center">Community</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Total Samples</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr class="summaryRow">
                            <td class="text-center">
                                {{$result->community_name}}
                            </td>
                            <td class="text-center">
                                {{$result->year}}
                            </td>
                            <td class="text-center">
                                {{$result->samples}}
                            </td>
                            <td class="text-center">
                             
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">

    var year = 0, month = 0;

    $(document).on('change', '#selectedYear', function () {

        year = $(this).val();
        
        $("#selectedMonth").prop('disabled', false);
        
        $(document).on('change', '#selectedMonth', function () {

            month = $(this).val();

            $.ajax({
                url: "{{ route('chartWaterResult') }}",
                type: 'get',
                data: {
                    year: year,
                    month: month
                },
                success: function(data) {
        
                    $("#cfuWaterResult").css("visibility", "visible");
                    $("#cfuWaterResult").css('display', 'block');
                    //$("#cfuWaterResultTitle").html("Grid Integration System");
                    var analytics = data;

                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable(analytics);
                        var options  ={
                            title:'Biological Test Result',
                            is3D:true,
                        };

                        var chart = new google.visualization.PieChart(
                            document.getElementById('cfuWaterResultBody'));
                        chart.draw(
                            data, options
                        );
                    }
                }
            });
        });
    });
</script>
@endsection