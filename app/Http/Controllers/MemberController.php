<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query()->latest();
        $search = trim((string) $request->get('search', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $gender = $request->get('gender', 'all');
        if (in_array($gender, ['male', 'female'], true)) {
            $query->where('gender', $gender);
        }

        return view('members.index', [
            'members' => $query->paginate(10)->withQueryString(),
            'search' => $search,
            'gender' => $gender,
        ]);
    }

    public function create()
    {
        return view('members.create', ['member' => new Member()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateMember($request);

        $data['created_by'] = $request->user()->name;
        $data['image1'] = $this->uploadImage($request, 'image1');
        $data['image2'] = $this->uploadImage($request, 'image2');

        Member::create($data);

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $this->validateMember($request, $member->id);

        $data['image1'] = $member->image1;
        $data['image2'] = $member->image2;

        if ($request->boolean('remove_image1')) {
            $this->deleteImage($member->image1);
            $data['image1'] = null;
        }

        if ($request->boolean('remove_image2')) {
            $this->deleteImage($member->image2);
            $data['image2'] = null;
        }

        if ($request->hasFile('image1')) {
            $this->deleteImage($member->image1);
            $data['image1'] = $this->uploadImage($request, 'image1');
        }

        if ($request->hasFile('image2')) {
            $this->deleteImage($member->image2);
            $data['image2'] = $this->uploadImage($request, 'image2');
        }

        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $this->deleteImage($member->image1);
        $this->deleteImage($member->image2);
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }

    private function validateMember(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email' . ($ignoreId ? ',' . $ignoreId : '')],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['required', 'in:male,female'],
            'image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function uploadImage(Request $request, string $field): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $request->file($field)->store('members', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}