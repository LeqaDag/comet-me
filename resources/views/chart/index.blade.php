
@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Analytics')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Communities </span> Percentages
</h4>

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6">
            <fieldset class="form-group">
                <label class='col-md-12 control-label'>Service</label>
                <select name="service_id" id="selectedService" 
                    class="form-control" required>
                    <option disabled selected>Choose one...</option>
                    @foreach($services as $service)
                    <option value="{{$service->id}}">
                        {{$service->service_name}}
                    </option>
                    @endforeach
                </select>
            </fieldset>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <fieldset class="form-group">
                <label class='col-md-12 control-label'>Region</label>
                <select name="region_id" id="selectedRegion" 
                    class="form-control" disabled required>
                    <option disabled selected>Choose one...</option>
                    <option value=0>All regions</option>
                    @foreach($regions as $region)
                    <option value="{{$region->id}}">
                        {{$region->english_name}}
                    </option>
                    @endforeach
                </select>
            </fieldset>
        </div>
    </div>
</div>

<div class="container mb-4" id="percentageDiv" style="visiblity:hidden; display:none">
    <div class="card-body">
        <div class="row"> 
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="waterNumber">%</h4>
                        <span class="d-block mb-4 text-nowrap">Water</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-water me-1 bx-lg text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="row align-items-end">
                    <div class="col-6">
                        <h4 class=" text-primary mb-2 pt-4 pb-1" id="internetNumber">%</h4>
                        <span class="d-block mb-4 text-nowrap">Internet</span>
                    </div>
                    <div class="col-6">
                        <i class="bx bx-wifi me-1 bx-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        $(document).on('change', '#selectedService', function () {
            service_id = $(this).val(); 
            $('#selectedRegion').prop('disabled', false);
                    
            $(document).on('change', '#selectedRegion', function () {
                region_id = $(this).val();

                $.ajax({
                    url: "chart/service/" + service_id + "/" + region_id,
                    method: 'GET',
                    success: function(data) {
                        $("#percentageDiv").css("visibility", "visible");
                        $("#percentageDiv").css('display', 'block');
                        served = data.servedHouseholds;
                        water = data.totalH2oUsers;
                        internet = data.InternetUsers;
                     
                        water = ((water/served) *100).toFixed(2);
                        internet = ((internet/served) *100).toFixed(2);

                        $("#waterNumber").html(water + " %");
                        $("#internetNumber").html(internet + " %");
                    }
                });
            });
        });
    });
</script>
@endsection