@extends('layouts/layoutMaster')

@section('title', 'On Hold households')

@include('layouts.all')

@section('content')

<div class="container mb-4 my-2">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-header">
                    <h5>On Hold Households by Community</h5>
                </div>
                <div class="panel-body" >
                    <div id="community_on_hold_households_chart" style="height:300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">All </span>On Hold Households
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
            <table id="onHoldHouseholdsTable" 
                class="table table-striped data-table-on-hold-households my-2">
                <thead>
                    <tr>
                        <th>English Name</th>
                        <th>Arabic Name</th>
                        <th>Community</th>
                        <th>Compound</th>
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

        var analytics = <?php echo $communityOnHoldHouseholdsData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'AC Survey Households by Community' 
            };

            var chart = new google.charts.Bar(document.getElementById('community_on_hold_households_chart'));
            chart.draw(data, options);
        }

        var table = $('.data-table-on-hold-households').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('hold-household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'compound', name: 'compound'},
                {data: 'action'}
            ]
        });

        // Delete record
        $('#onHoldHouseholdsTable').on('click', '.deleteOnHoldHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this on hold household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteOnHoldHousehold') }}",
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
                                    $('#acHouseholdsTable').DataTable().draw();
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