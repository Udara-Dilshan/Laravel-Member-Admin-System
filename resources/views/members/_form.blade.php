@php
    $isEdit = $member->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('members.update', $member) : route('members.store') }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $member->email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Optional">
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                <option value="">Select gender</option>
                <option value="male" @selected(old('gender', $member->gender) === 'male')>Male</option>
                <option value="female" @selected(old('gender', $member->gender) === 'female')>Female</option>
            </select>
            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Image 1</label>
            <input type="file" name="image1" class="form-control @error('image1') is-invalid @enderror" accept="image/*" onchange="previewUpload(this, 'image1Preview')">
            @error('image1')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-3 preview-box">
                @if($member->image1)
                    <img id="image1Preview" src="{{ asset('storage/' . $member->image1) }}" alt="Image 1" data-image-preview="{{ asset('storage/' . $member->image1) }}">
                @else
                    <img id="image1Preview" src="https://placehold.co/640x360?text=Image+1" alt="Image 1 preview">
                @endif
            </div>
            @if($isEdit && $member->image1)
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="remove_image1" id="remove_image1">
                    <label class="form-check-label" for="remove_image1">Remove current image</label>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label">Image 2</label>
            <input type="file" name="image2" class="form-control @error('image2') is-invalid @enderror" accept="image/*" onchange="previewUpload(this, 'image2Preview')">
            @error('image2')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-3 preview-box">
                @if($member->image2)
                    <img id="image2Preview" src="{{ asset('storage/' . $member->image2) }}" alt="Image 2" data-image-preview="{{ asset('storage/' . $member->image2) }}">
                @else
                    <img id="image2Preview" src="https://placehold.co/640x360?text=Image+2" alt="Image 2 preview">
                @endif
            </div>
            @if($isEdit && $member->image2)
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="remove_image2" id="remove_image2">
                    <label class="form-check-label" for="remove_image2">Remove current image</label>
                </div>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Member' : 'Save Member' }}</button>
        <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
    function previewUpload(input, targetId) {
        const target = document.getElementById(targetId);
        if (! input.files || ! input.files[0]) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            target.src = event.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>
@endpush