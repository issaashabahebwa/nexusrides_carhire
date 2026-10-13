@extends('layouts.admin')
@section('title', 'Edit Vehicle')

@section('content')

<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Edit Vehicle</h1>
        <p class="adm-page-sub">{{ $vehicle->name }} · {{ $vehicle->plate_number }}</p>
    </div>
    <a href="{{ route('admin.vehicles.index') }}" class="adm-btn adm-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Vehicles
    </a>
</div>

<div class="adm-panel">
    <div class="adm-panel-head">
        <div style="display:flex;align-items:center;gap:1rem;">
            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}"
                 style="width:56px;height:40px;object-fit:cover;border-radius:8px;flex-shrink:0;">
            <div>
                <h3 class="adm-panel-title">{{ $vehicle->name }}</h3>
                <p class="adm-panel-sub">{{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
            </div>
        </div>
        <span class="adm-status adm-status-{{ match($vehicle->status) {
            'available'   => 'success',
            'hired'       => 'info',
            'maintenance' => 'warning',
            default       => 'secondary'
        } }}">{{ ucfirst($vehicle->status) }}</span>
    </div>
    <div class="adm-panel-body">

        @if($errors->any())
        <div class="adm-alert adm-alert-danger" style="margin-bottom:1.25rem;">
            <span class="adm-alert-icon">✕</span>
            <div>
                @foreach($errors->all() as $e)
                <div>• {{ $e }}</div>
                @endforeach
            </div>
            <button class="adm-alert-close" onclick="this.closest('.adm-alert').remove()">✕</button>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.vehicles._form', ['vehicle' => $vehicle])
            <div class="adm-form-actions">
                <a href="{{ route('admin.vehicles.index') }}" class="adm-btn adm-btn-outline">Cancel</a>
                <button type="submit" class="adm-btn adm-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Update Vehicle
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
