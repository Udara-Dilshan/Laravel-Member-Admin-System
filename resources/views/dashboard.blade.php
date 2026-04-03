@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-card p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">Dashboard</h1>
                <p class="text-muted mb-0">
                    Monitor members, manage admin accounts with OTP approval, and navigate the app from one clean control panel.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="badge text-bg-primary fs-6 px-3 py-2 me-2">Logged in as {{ auth()->user()->name }}</span>
                <span class="badge text-bg-dark fs-6 px-3 py-2">{{ auth()->user()->role }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase text-muted small fw-semibold">Total Members</div>
                        <div class="display-5 fw-bold mb-0">{{ $memberCount }}</div>
                    </div>
                    <div class="display-4 text-primary opacity-25"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase text-muted small fw-semibold">Total Admins</div>
                        <div class="display-5 fw-bold mb-0">{{ $adminCount }}</div>
                    </div>
                    <div class="display-4 text-danger opacity-25"><i class="bi bi-person-badge-fill"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="stat-card bg-white p-4 h-100">
                <h5 class="fw-bold mb-3">Quick Navigation</h5>
                <a href="{{ route('members.index') }}" class="btn btn-primary btn-lg w-100 mb-2">Members</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admins.index') }}" class="btn btn-outline-dark btn-lg w-100">Admins</a>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-white p-4 h-100">
                <h5 class="fw-bold mb-3">Security Summary</h5>
                <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                    <span class="badge text-bg-success">Auth Protected</span>
                    <span class="badge text-bg-warning">Role Aware</span>
                    <span class="badge text-bg-info">OTP Secured</span>
                </div>
                <p class="text-muted mb-0">
                    Admin CRUD actions are verified by OTP before completion in both development and production modes.
                </p>
            </div>
        </div>
    </div>
@endsection