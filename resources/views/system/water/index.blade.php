@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-system')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>System By Type</h5>
                </div>
                <div class="card-body">
                    <div id="waterSystemTypeChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div>


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water Systems
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
            @if(Auth::guard('user')->user()->user_type_id == 1 || 
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 5 ||
                Auth::guard('user')->user()->user_type_id == 11)
                <div>
                    <a type="button" class="btn btn-success" 
                        href="{{url('water-system', 'create')}}">
                        Create New Water System	
                    </a>
                </div>
            @endif
            <table id="systemWaterTable" class="table table-striped data-table-water-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Options</th>
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

        var table = $('.data-table-water-system').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-system.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'type', name: 'type'},
                {data: 'description', name: 'description'},
                {data: 'year', name: 'year'},
                {data: 'action'},
            ]
        });
    });

    $(function () {

        var analytics = <?php echo $waterSystemTypeData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);

            var chart = new google.charts.Bar(document.getElementById('waterSystemTypeChart'));
            chart.draw(
                data
            );
        } 
    });

    // Delete record
    $('#systemWaterTable').on('click', '.deleteWaterSystem',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this system?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteWaterSystem') }}",
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
                                $('#systemWaterTable').DataTable().draw();
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

    // // View record update page
    // $('#systemWaterTable').on('click', '.updateWaterSystem',function() {

    //     var id = $(this).data('id');
    //     var url = window.location.href; 
    //     url = url +'/'+ id +'/edit';
    //     // AJAX request
    //     $.ajax({
    //         url: 'water-system/' + id + '/editpage',
    //         type: 'get',
    //         dataType: 'json',
    //         success: function(response) {
    //             window.open(url, "_self"); 
    //         }
    //     });
    // });

</script>
@endsection