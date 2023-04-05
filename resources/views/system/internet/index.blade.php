
@extends('layouts/layoutMaster')

@section('title', 'all internet systems')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Internet Systems
</h4>

@include('system.internet.details')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <table id="internetAllSystemsTable" class="table table-striped data-table-internet-system my-2">
                <thead>
                    <tr>
                        <th class="text-center">System Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">System Type</th>
                        <th class="text-center">Start Year</th>
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

        var table = $('.data-table-internet-system').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('internet-system.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'system_name', name: 'system_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'name', name: 'name'},
                {data: 'start_year', name: 'start_year'},
                {data: 'action'}
            ]
        });
    });

    // View record details
    $('#internetAllSystemsTable').on('click', '.viewInternetSystem',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        url = url +'/'+ id;

        // AJAX request
        $.ajax({
            url: 'internet-system/' + id + '/showPage',
            type: 'get',
            dataType: 'json',
            success: function(response) {

                window.open(url); 
            }
        });
    });

</script>
@endsection