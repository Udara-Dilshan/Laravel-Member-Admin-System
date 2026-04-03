@extends('layouts.app')

@section('title', 'Admins')

@section('content')
    <div class="page-card p-4 p-lg-5 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Admins</h1>
                <p class="text-muted mb-0">Manage admin accounts with OTP-protected actions.</p>
            </div>
            <a href="{{ route('admins.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Admin</a>
        </div>
    </div>

    <div class="table-card bg-white p-3 p-lg-4">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->created_by ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admins.edit', $admin) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @if(auth()->id() !== $admin->id)
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admins.destroy', $admin) }}" data-delete-name="{{ $admin->name }}" data-bs-toggle="modal" data-bs-target="#deleteAdminModal">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">No admins found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $admins->links() }}
        </div>
    </div>

    <div class="modal fade" id="deleteAdminModal" tabindex="-1" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAdminModalLabel">Delete Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete <strong id="deleteAdminName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteAdminForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Proceed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const deleteAdminModal = document.getElementById('deleteAdminModal');
    deleteAdminModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-delete-url');
        const name = button.getAttribute('data-delete-name');
        document.getElementById('deleteAdminForm').action = url;
        document.getElementById('deleteAdminName').textContent = name;
    });
</script>
@endpush