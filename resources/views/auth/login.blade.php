@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Welcome back</h2>
        <p class="text-muted mb-0">Sign in to continue to the dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="admin@example.com" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Enter your password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
    </form>

    <div class="alert alert-info mt-4 mb-0">
        Default seed login: <strong>admin@example.com</strong> / <strong>password</strong>
    </div>
@endsection