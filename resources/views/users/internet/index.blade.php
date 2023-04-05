@extends('layouts/layoutMaster')

@section('title', 'all internet users')

@include('layouts.all')

@section('content')



<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Internet Contract Holders
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
            <table id="internetAllUsersTable" class="table table-striped data-table-internet-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Date</th>
                        <th class="text-center"># of Contracts</th>
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

        var table = $('.data-table-internet-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('internet-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'start_date', name: 'start_date'},
                {data: 'number_of_contract', name: 'number_of_contract'},
                {data: 'action'}
            ]
        });
    });
</script>
@endsection