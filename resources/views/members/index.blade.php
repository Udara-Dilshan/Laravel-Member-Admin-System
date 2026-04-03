@extends('layouts.app')

@section('title', 'Members')

@section('content')
    <div class="page-card p-4 p-lg-5 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Members</h1>
                <p class="text-muted mb-0">Search by name, email, or phone and filter by gender.</p>
            </div>
            <a href="{{ route('members.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Member</a>
        </div>

        <form method="GET" action="{{ route('members.index') }}" class="row g-3 mt-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Name, email, or phone">
            </div>
            <div class="col-md-4">
                <label class="form-label">Gender Filter</label>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('members.index', ['search' => $search, 'gender' => 'all']) }}" class="btn {{ $gender === 'all' ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
                    <a href="{{ route('members.index', ['search' => $search, 'gender' => 'male']) }}" class="btn {{ $gender === 'male' ? 'btn-dark' : 'btn-outline-dark' }}">Male</a>
                    <a href="{{ route('members.index', ['search' => $search, 'gender' => 'female']) }}" class="btn {{ $gender === 'female' ? 'btn-dark' : 'btn-outline-dark' }}">Female</a>
                </div>
            </div>
            <div class="col-md-3 text-md-end">
                <button type="submit" class="btn btn-secondary w-100">Apply Search</button>
            </div>
        </form>
    </div>

    <div class="table-card bg-white p-3 p-lg-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Images</th>
                        <th>Created By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->phone ?? '-' }}</td>
                            <td><span class="badge text-bg-info text-uppercase">{{ $member->gender }}</span></td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($member->image1)
                                        <img src="{{ asset('storage/' . $member->image1) }}" class="image-thumb" alt="Image 1" data-image-preview="{{ asset('storage/' . $member->image1) }}">
                                    @endif
                                    @if($member->image2)
                                        <img src="{{ asset('storage/' . $member->image2) }}" class="image-thumb" alt="Image 2" data-image-preview="{{ asset('storage/' . $member->image2) }}">
                                    @endif
                                    @if(! $member->image1 && ! $member->image2)
                                        <span class="text-muted">No images</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $member->created_by ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('members.destroy', $member) }}" data-delete-name="{{ $member->name }}" data-bs-toggle="modal" data-bs-target="#deleteMemberModal">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $members->links() }}
        </div>
    </div>

    <div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMemberModalLabel">Delete Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete <strong id="deleteMemberName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteMemberForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const deleteMemberModal = document.getElementById('deleteMemberModal');
    deleteMemberModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-delete-url');
        const name = button.getAttribute('data-delete-name');
        document.getElementById('deleteMemberForm').action = url;
        document.getElementById('deleteMemberName').textContent = name;
    });
</script>
@endpush