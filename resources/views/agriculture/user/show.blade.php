@extends('layouts/layoutMaster')

@section('title', 'Agriculture User Details')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Agriculture Users / </span> View Details
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Agriculture User Details</h5>
                <div>
                    <a href="{{ route('argiculture-user.edit', $agricultureUser->id) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                    <a href="{{ route('argiculture-user.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Household</label>
                            <p class="form-control-plaintext">{{ optional($agricultureUser->household)->english_name ?? '—' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Community</label>
                            <p class="form-control-plaintext">{{ optional($agricultureUser->community)->english_name ?? '—' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">{{ optional($agricultureUser->agricultureHolderStatus)->english_name ?? '—' }}</p>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Requested Date</label>
                                <p class="form-control-plaintext">{{ $agricultureUser->requested_date ? \Carbon\Carbon::parse($agricultureUser->requested_date)->format('Y-m-d') : '—' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Confirmation Date</label>
                                <p class="form-control-plaintext">{{ $agricultureUser->confirmation_date ? \Carbon\Carbon::parse($agricultureUser->confirmation_date)->format('Y-m-d') : '—' }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">System Cycle</label>
                            <p class="form-control-plaintext">{{ optional($agricultureUser->agricultureSystemCycle)->name ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type of Installation</label>
                            <p class="form-control-plaintext">{{ (isset($agricultureUser->agriculture_installation_type_id) && $agricultureUser->agriculture_installation_type_id) ? (App\Models\AgricultureInstallationType::find($agricultureUser->agriculture_installation_type_id)->english_name ?? '—') : '—' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Area of Installation</label>
                            <p class="form-control-plaintext">{{ $agricultureUser->area_of_installation ?? '—' }}</p>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Azolla Units</label>
                                <p class="form-control-plaintext">{{ $agricultureUser->azolla_unit ?? '—' }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Herd Size</label>
                                <p class="form-control-plaintext">{{ $agricultureUser->size_of_herds ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Additional Animals</label>
                            <p class="form-control-plaintext mb-0">
                                Goats: {{ $agricultureUser->size_of_goat ?? 0 }}<br>
                                Cows: {{ $agricultureUser->size_of_cow ?? 0 }}<br>
                                Camels: {{ $agricultureUser->size_of_camel ?? 0 }}<br>
                                Chickens: {{ $agricultureUser->size_of_chicken ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                
                

                {{-- Shared Herd Information --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Shared Herd Information</h6>
                    </div>
                    <div class="card-body">
                        @php
                            // Prefer Eloquent relation if available, otherwise use fallback from controller
                            $sharedRows = [];
                            if (isset($sharedHerds) && $sharedHerds->count() > 0) {
                                $sharedRows = $sharedHerds;
                                $useRaw = true;
                            } elseif ($agricultureUser->relationLoaded('agricultureSharedHolders') && $agricultureUser->agricultureSharedHolders->count() > 0) {
                                $sharedRows = $agricultureUser->agricultureSharedHolders;
                                $useRaw = false;
                            } elseif ($agricultureUser->agricultureSharedHolders && $agricultureUser->agricultureSharedHolders->count() > 0) {
                                $sharedRows = $agricultureUser->agricultureSharedHolders;
                                $useRaw = false;
                            } else {
                                $sharedRows = collect();
                                $useRaw = false;
                            }
                        @endphp

                        @if($sharedRows->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Household</th>
                                            <th>Sheep (size_of_herds)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sharedRows as $shared)
                                            <tr>
                                                @if(!empty($useRaw))
                                                    <td>{{ isset($shared->household_id) ? (App\Models\Household::find($shared->household_id)->english_name ?? '—') : '—' }}</td>
                                                    <td>{{ $shared->size_of_herds ?? 0 }}</td>
                                                @else
                                                    <td>{{ optional($shared->household)->english_name ?? '—' }}</td>
                                                    <td>{{ $shared->size_of_herds ?? 0 }}</td>
                                                    <td>{{ optional($shared->created_at)->format('Y-m-d H:i:s') ?? '—' }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No shared herd records for this holder.</p>
                        @endif
                    </div>
                </div>
                
                {{-- Donors Information --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Donors</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $holderDonors = collect();
                            if (isset($donorsList) && $donorsList instanceof \Illuminate\Support\Collection && $donorsList->count()>0) {
                                $holderDonors = $donorsList;
                            } elseif ($agricultureUser->relationLoaded('agricultureHolderDonors') && $agricultureUser->agricultureHolderDonors->count()>0) {
                                $holderDonors = $agricultureUser->agricultureHolderDonors;
                            } elseif ($agricultureUser->agricultureHolderDonors && $agricultureUser->agricultureHolderDonors->count()>0) {
                                $holderDonors = $agricultureUser->agricultureHolderDonors;
                            }
                        @endphp

                        @if($holderDonors->count() > 0)
                            <div class="list-group">
                                @foreach($holderDonors as $hd)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-medium">{{ optional($hd->donor)->donor_name ?? optional($hd)->donor_name ?? 'Unknown' }}</div>
                                            <small class="text-muted">{{ optional($hd->donor)->email ?? optional($hd)->email ?? '' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">Joined: {{ optional($hd->created_at)->format('Y-m-d') ?? '—' }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No donors are associated with this holder.</p>
                        @endif
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card p-2 border-0 bg-light">
                            <div class="card-body p-3">
                                <h6 class="mb-2 fw-bold">Notes</h6>
                                <p class="mb-0 text-muted">{{ $agricultureUser->notes ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div>

@endsection