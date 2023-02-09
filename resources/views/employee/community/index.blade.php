@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'communities')

@include('layouts.all')

@section('content')

<div class="container">
    <div class="row g-4 mb-4">
        <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Energy Service</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityInitial">
                                        <i class='bx bx-message'></i>
                                    </a>
                                </span>
                                @include('employee.community.service.initial')
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Initial Communities</span>
                                    <span class="text-muted">{{$communityInitial}}</span>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-primary" style="width: {{$communityInitial}}%" 
                                        role="progressbar" aria-valuenow="{{$communityInitial}}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{$communityRecords}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-initial rounded-circle bg-label-warning">
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityAC">
                                        <i class='bx bx-message-alt-detail'></i>
                                    </a>
                                </span>
                                @include('employee.community.service.ac_survey')
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>AC Communitites</span>
                                    <span class="text-muted">
                                        @if($communityAC)
                                            {{$communityAC}}
                                        @endif
                                    </span>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" style="width: {{$communityAC}}%" 
                                        role="progressbar" aria-valuenow="{{$communityAC}}" aria-valuemin="0" 
                                        aria-valuemax="{{$communityRecords}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communitySurveyed">
                                        <i class='bx bx-bulb'></i>
                                    </a>
                                </span>
                                @include('employee.community.service.surveyed')
                                </span>
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Active Communities</span>
                                    <span class="text-muted">
                                        {{$communitySurvyed}}
                                    </span>
                                </div>
                                <?php
                                    $diff = ($communitySurvyed / $communityRecords ) * 100;
                                ?>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-success" 
                                        style="width: {{$diff}}%" 
                                        role="progressbar" 
                                        aria-valuenow="{{$diff}}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{$communityRecords}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xl-4 col-xxl-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Water Service</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    @if ($communityWater)
                                        {{$communityWater}}
                                    @endif
                                </h4> <small>Communities</small>
                            </div>
                            
                                @if ($communityWater)
                                <?php
                                    $min = $communityRecords - $communityWater;
                                ?>

                                    @if($min < $communityRecords/2)
                                        <small class="text-success">{{$min}}
                                    @else 
                                        <small class="text-danger">{{$min}}
                                    @endif
                                    
                                @endif
                            </small>
                            <small>Remaining</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityWater">
                                <i class="bx bx-water bx-sm"></i>
                            </a>
                            @include('employee.community.service.water')
                        </span>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Internet Service</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    @if ($communityInternet)
                                        {{$communityInternet}}
                                    @endif
                                </h4>  
                                <small>Communities</small>
                            </div>
                            @if ($communityInternet)
                            <?php
                                $min = $communityRecords - $communityInternet;
                            ?>  
                                @if($min < $communityRecords/2)
                                    <small class="text-success">{{$min}}
                                @else 
                                    <small class="text-danger">{{$min}}
                                @endif
                            @endif
                            </small>
                            <small>Remaining</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">

                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityInternet">
                                <i class="bx bx-wifi bx-sm"></i>
                            </a>

                            @include('employee.community.service.internet')
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_regional_community" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_sub_regional_community" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> communities
</h4>

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createCommunity">
                    Create New Community	
                </button>
                @include('employee.community.create')

                <a href="{{url('community', 'create')}}" 
					class="btn btn-primary">Calculate Number of Households
                </a>
            </div>
            <table id="communityTable" class="table table-striped data-table-communities my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center"># of Households</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Sub Region</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <input type="hidden" name="txtCommunityId" id="txtCommunityId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var analytics = <?php echo $regionsData; ?>;
        var analyticsSubRegions = <?php echo $subRegionsData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Communities by Region' 
            };

            var data1 = google.visualization.arrayToDataTable(analyticsSubRegions);
            var options1 = {
                title : 'Communities by Sub-Region' 
            };


            var chart = new google.visualization.PieChart(document.getElementById('pie_chart_regional_community'));
            chart.draw(data, options);

            var chart1 = new google.visualization.PieChart(document.getElementById('pie_chart_sub_regional_community'));
            chart1.draw(data1, options1);

        }

        var table = $('.data-table-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('community.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'number_of_people', name: 'number_of_people'},
                {data: 'name', name: 'name'},
                {data: 'subname', name: 'subname'},
                {data: 'status_name', name: 'status_name'},
                {data: 'action'}
            ]
        });
        
    });
</script>


<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <p class="card-text">
                <div>
                </div>
            </p>
        </div>
        <div class="table-responsive">
            @if (count($communities))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">English Name</th>
                            <th class="text-center">Arabic Name</th>
                            <th class="text-center"># of Households</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Sub Region</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($communities as $community)
                    @if($community->is_archived == 0)
                        <tr> 
                            <td class="text-center">
                                <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityDetails{{$community->id}}">
                                    <i class="fas fa-eye" style="color:blue;"></i>
                                </a>
                            </td>
                            @include('employee.community.details')
                            <td class="text-center">
                                {{ $community->english_name }}
                            </td>
                            <td class="text-center">
                                {{ $community->arabic_name }}
                            </td>
                            <td class="text-center">
                                {{ $community->number_of_people }}
                            </td>
                            <td class="text-center">
                                {{ $community->Region->english_name }} 
                            </td>
                            <td class="text-center">
                                @if($community->SubRegion)
                                {{ $community->SubRegion->english_name }} 
                                @endif
                            </td>
                            <td class="text-center">
                                <a data-bs-target="#communityMap{{$community->id}}"
                                   type="button" data-bs-toggle="modal" title="View Map">
                                    <i class="fas fa-map" style="color:orange;"></i>
                                </a>
                                @include('employee.community.map')
                                <a data-bs-target="#communityImage{{$community->id}}"
                                   type="button" data-bs-toggle="modal" title="Add Images">
                                    <i class="fas fa-image" style="color:blue;"></i>
                                </a>
                                @include('employee.community.image')
                                <a href="">
                                    <i class="fas fa-edit" style="color:green;"></i>
                                </a>
                                <a href="{{ url('community/destory', $community->id) }}"
                                    title="delete">
                                    <i class="fas fa-trash-alt delete-item"
                                    style="color:red;"></i>
                                    {{ method_field('delete') }} 
                                </a>
                            </td>
                        </tr>
                    @endif
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {!! $communities->links('pagination::bootstrap-4') !!}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
