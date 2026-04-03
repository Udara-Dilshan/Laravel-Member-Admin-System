@php
    $isEdit = $admin->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('admins.update', $admin) : route('admins.store') }}">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
            <div class="form-text">{{ $isEdit ? 'Leave blank to keep the existing password.' : 'Use at least 8 characters.' }}</div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
        </div>
    </div>

    <div class="alert alert-warning mt-4 mb-0">
        Admin actions are protected by OTP verification before they are completed.
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Admin' : 'Save Admin' }}</button>
        <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>