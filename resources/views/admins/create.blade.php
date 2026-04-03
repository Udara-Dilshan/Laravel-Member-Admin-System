@extends('layouts.app')

@section('title', 'Add Admin')

@section('content')
    <div class="form-card bg-white p-4 p-lg-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">Add Admin</h1>
                <p class="text-muted mb-0">Create a new admin account and verify the action with OTP.</p>
            </div>
            <a href="{{ route('admins.index') }}" class="btn btn-soft">Back to Admins</a>
        </div>

        @include('admins._form', ['admin' => $admin])
    </div>
@endsection