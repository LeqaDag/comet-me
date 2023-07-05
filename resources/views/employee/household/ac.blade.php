@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'AC Survey households')

@include('layouts.all')

@section('content')

<div class="container mb-4 my-2">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-header">
                    <h5>AC Survey Households by Community</h5>
                </div>
                <div class="panel-body" >
                    <div id="community_ac_households_chart" style="height:300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">All </span>AC Survey Households
</h4>


@include('employee.household.sub_household')

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
            <div>
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div>
                    <a type="button" class="btn btn-success" 
                        href="{{url('ac-household', 'create')}}" >
                        Create New AC-Survey Household
                    </a>
                </div>
            @endif
            </div>
            <table id="acHouseholdsTable" 
                class="table table-striped data-table-ac-households my-2">
                <thead>
                    <tr>
                        <th>English Name</th>
                        <th>Arabic Name</th>
                        <th>Community</th>
                        <th>Region</th>
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
    $(function () {

        var analytics = <?php echo $communityAcHouseholdsData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'AC Survey Households by Community' 
            };

            var chart = new google.charts.Bar(document.getElementById('community_ac_households_chart'));
            chart.draw(data, options);
        }

        var table = $('.data-table-ac-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ac-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'region_name', name: 'region_name'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#acHouseholdsTable').on('click', '.updateAcHousehold',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, '_self'); 
                }
            });
        });
    
    });
</script>
@endsection