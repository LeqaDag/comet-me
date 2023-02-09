
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Electricity Public Structures
</h4>

<div class="container mb-4">
    <div class="card my-2">
        <div class="card-body">
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createMeterUser">
                    Create New Public Structure	
                </button>
                @include('users.energy.public.create')
            </div>
            <table id="energyPublicStructuresTable" 
                class="table table-striped data-table-energy-public-structures my-2">
                <thead>
                    <tr>
                        <th class="text-center">Public Structure</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
                        <th class="text-center">Energy System</th>
                        <th class="text-center">Energy System Type</th>
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
        var table = $('.data-table-energy-public-structures').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });
    });
</script>