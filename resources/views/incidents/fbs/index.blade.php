@extends('layouts/layoutMaster')

@section('title', 'fbs incidents')

@include('layouts.all')

@section('content')

@include('system.energy.fbs_incidents_details')
<div class="container mb-4">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body" >
                        <div id="incidentsFbsChart" style="height:400px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> FBS Incidents
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('incidents.fbs.show')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createFbsIncident">
                    Add FBS Incident
                </button>
                @include('incidents.fbs.create')
            </div>
            <table id="fbsIncidentsTable" class="table table-striped data-table-fbs-incidents my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy User</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Incident</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Status</th>
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

        var analytics = <?php echo $incidentsFbsData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        var number = <?php echo $fbsIncidentsNumber;?>;

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var options  ={
                title:'Status of FBS Incidents (total '+ number +')',
                is3D:true,
            };

            var chart = new google.visualization.PieChart(
            document.getElementById('incidentsFbsChart'));
            chart.draw(
                data, options
            );

            google.visualization.events.addListener(chart,'select',function() {
                
                var row = chart.getSelection()[0].row;
                var selected_data = data.getValue(row,0);
                
                $.ajax({
                    url: "{{ route('incidentFbsDetails') }}",
                    type: 'get',
                    data: {
                        selected_data: selected_data
                    },
                    success: function(response) {
                        $('#incidentsFbsDetailsModal').modal('toggle');
                        $('#incidentsFbsDetailsTitle').html(selected_data);
                        $('#contentIncidentsFbsTable').find('tbody').html('');
                        response.forEach(refill_table);
                        function refill_table(item, index){
                            $('#contentIncidentsFbsTable').find('tbody').append('<tr><td>'+item.household+'</td><td>'+item.community+'</td><td>'+item.equipment+'</td><td>'+item.incident+'</td><td>'+item.date+'</td></tr>');
                        }
                    }
                });
            });
        }
    });

    $(function () {

        var table = $('.data-table-fbs-incidents').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('fbs-incident.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'excel',
                }
            ],
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'incident', name: 'incident'},
                {data: 'date', name: 'date'},
                {data: 'fbs_status', name: 'fbs_status'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#fbsIncidentsTable').on('click', '.viewFbsIncident', function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'fbs-incident/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) {
                    $('#fbsIncidentModalTitle').html('');
                    $('#fbsIncidentModalTitle').html(response['energyUser'].english_name);
                    $('#fbsUser').html('');
                    $('#fbsUser').html(response['energyUser'].english_name);
                    $('#communityName').html('');
                    $('#communityName').html(response['community'].english_name);
                    $('#incidentDate').html('');
                    $('#incidentDate').html(response['fbsIncident'].date);
                    $('#fbsIncidentStatus').html('')
                    $('#fbsIncidentStatus').html(response['fbsStatus'].name);
                    $('#incidentYear').html('');
                    $('#incidentYear').html(response['fbsIncident'].year);
                    $('#incidentType').html('');
                    $('#incidentType').html(response['incident'].english_name);
                    $('#incidentEquipment').html('');
                    $('#incidentEquipment').html(response['fbsIncident'].equipment);
                    $('#incidentNotes').html('');
                    $('#incidentNotes').html(response['fbsIncident'].notes);
                }
            });
        });

        // Delete record
        $('#fbsIncidentsTable').on('click', '.deleteFbsIncident',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Incident?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteFbsIncident') }}",
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
                                    $('#fbsIncidentsTable').DataTable().draw();
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