@extends('layouts.admin')
@section('title', 'Add Vehicle')

@section('content')

<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Add New Vehicle</h1>
        <p class="adm-page-sub">Fill in the details below to add a vehicle to the fleet</p>
    </div>
    <a href="{{ route('admin.vehicles.index') }}" class="adm-btn adm-btn-outline">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Vehicles
    </a>
</div>

<div class="adm-panel">
    <div class="adm-panel-head">
        <h3 class="adm-panel-title">Vehicle Details</h3>
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

        <form method="POST" action="{{ route('admin.vehicles.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.vehicles._form')
            <div class="adm-form-actions">
                <a href="{{ route('admin.vehicles.index') }}" class="adm-btn adm-btn-outline">Cancel</a>
                <button type="submit" class="adm-btn adm-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Vehicle
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
