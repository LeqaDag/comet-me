<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        
                        <div class="col-md-4">
                            <h6><strong>Name:</strong></h6>
                            <p>{{ $system->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><strong>Azolla Type:</strong></h6>
                            <p>{{ $system->azollaType ? $system->azollaType->name : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>System Cycle:</strong></h6>
                            <p>{{ $system->agricultureSystemCycle ? $system->agricultureSystemCycle->name : 'Not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><strong>Installation Year:</strong></h6>
                            <p>{{ $system->installation_year ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Status:</strong></h6>
                            <p>
                                @if($system->is_archived)
                                    <span class="badge bg-danger">Archived</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6><strong>Description:</strong></h6>
                            <p>{{ $system->description ?? 'No description provided' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>