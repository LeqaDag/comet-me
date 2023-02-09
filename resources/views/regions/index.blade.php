@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'regions')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart" style="height:350px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Sub-Regions
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('regions.update')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createSubRegionModal">
                    Create New Sub-Region	
                </button>
                @include('regions.create-sub')

                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createRegionModal">
                    Create New Region	
                </button>
                @include('regions.create')
            </div>
            <table id="subRegionTable" class="table table-striped data-table-regions my-2">
                <thead>
                    <tr>
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <input type="hidden" name="txtSubRegionId" id="txtSubRegionId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">


    $(function () {

        var analytics = <?php echo $subregions; ?>

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Sub Regional Areas'
            };
            var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            chart.draw(data, options);
        }

        // DataTable
        var table = $('.data-table-regions').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('sub-region.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                { data: 'action' }
            ],
            
        });
        
        $('#status').change(function() {
            table.draw();
        });

        // Update record
        $('#subRegionTable').on('click','.updateSubRegion',function() {
            var id = $(this).data('id');

            $('#txtSubRegionId').val(id);

            // AJAX request
            $.ajax({
                url: 'getSubRegionData/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    if(response.success == 1) {

                        $('#english_name').val(response.english_name);
                        $('#arabic_name').val(response.arabic_name);

                        // get region by id
                        $.ajax({
                            url: 'getRegionData/' + response.region_id,
                            type: 'get',
                            dataType: 'json',
                            success: function(response) {

                                if(response.success == 1) {
                                    $('#selectedRegion').text(response.english_name);
                                    $.ajax({
                                        url: 'getAllSubRegion/',
                                        type: 'get',
                                        dataType: 'json',
                                        success: function(response) {

                                            if(response.success == 1) {
                                                response.regions.forEach(el => {
                                                    $(".updateRegionId").append(`<option value='${el.id}'> ${el.english_name}</option>`)
    
                                                });
                                            };
                                            
                                        }
                                    });
                                    
                                } else {

                                    alert("Invalid ID.");
                                }
                            }
                        });
                    }
                }
            });

        });
        

        // Delete record
        $('#subRegionTable').on('click', '.deleteSubRegion',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this sub region?',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                $.ajax({
                    url: "{{ route('deleteSubRegion') }}",
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
                                $('#subRegionTable').DataTable().draw();
                            });
                        } else {

                            alert("Invalid ID.");
                        }
                    }
                });
            });
        });
    });
</script>

@endsection