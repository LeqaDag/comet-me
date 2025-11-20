@extends('layouts/layoutMaster')

@section('title', 'Create Agriculture System')

@include('layouts.all')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bx bx-plus me-2"></i>Create New Agriculture System
        </h4>
        <a href="{{ route('agriculture-system.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i>Back to List
        </a>
    </div>

    <!-- Success Message -->
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <!-- Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bx bx-leaf me-2"></i>Agriculture System Information
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('agriculture-system.store') }}" id="createSystemForm">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">
                            System Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter system name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label" for="azolla_type_id">
                            Azolla Type <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('azolla_type_id') is-invalid @enderror" 
                                id="azolla_type_id" 
                                name="azolla_type_id" 
                                required>
                            <option value="">Select Azolla Type</option>
                            @foreach($azollaTypes as $type)
                                <option value="{{ $type->id }}" 
                                        {{ old('azolla_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('azolla_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="installation_year">Installation Year</label>
                        <input type="number" 
                               class="form-control @error('installation_year') is-invalid @enderror" 
                               id="installation_year" 
                               name="installation_year" 
                               value="{{ old('installation_year') }}" 
                               min="1900" 
                               max="{{ date('Y') + 10 }}"
                               placeholder="e.g., {{ date('Y') }}">
                        @error('installation_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label" for="agriculture_system_cycle_id">System Cycle</label>
                        <select class="form-select @error('agriculture_system_cycle_id') is-invalid @enderror" 
                                id="agriculture_system_cycle_id" 
                                name="agriculture_system_cycle_id">
                            <option value="">Select System Cycle (Optional)</option>
                            @foreach($agricultureSystemCycles as $cycle)
                                <option value="{{ $cycle->id }}" 
                                        {{ old('agriculture_system_cycle_id') == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('agriculture_system_cycle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Describe the agriculture system...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('agriculture-system.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i>Create System
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection