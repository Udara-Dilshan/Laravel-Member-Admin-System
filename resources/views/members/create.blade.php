@extends('layouts.app')

@section('title', 'Add Member')

@section('content')
    <div class="form-card bg-white p-4 p-lg-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">Add Member</h1>
                <p class="text-muted mb-0">Create a new member profile with images and contact details.</p>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-soft">Back to Members</a>
        </div>

        @include('members._form', ['member' => $member])
    </div>
@endsection