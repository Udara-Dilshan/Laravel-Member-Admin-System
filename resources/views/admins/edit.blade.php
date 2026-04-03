@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')
    <div class="form-card bg-white p-4 p-lg-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">Edit Admin</h1>
                <p class="text-muted mb-0">Update admin details and re-verify the change with OTP.</p>
            </div>
            <a href="{{ route('admins.index') }}" class="btn btn-soft">Back to Admins</a>
        </div>

        @include('admins._form', ['admin' => $admin])
    </div>
@endsection