<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminController extends Controller
{
    public function index()
    {
        return view('admins.index', [
            'admins' => User::where('role', 'admin')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admins.create', ['admin' => new User()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateAdmin($request);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'created_by' => $request->user()->name,
            'recipient_email' => $data['email'],
        ];

        $this->prepareOtpAction($request, 'admin.store', $payload);

        return redirect()->route('otp.show')->with('success', 'OTP generated. Verify to complete admin creation.');
    }

    public function edit(User $admin)
    {
        abort_unless($admin->role === 'admin', 404);

        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        abort_unless($admin->role === 'admin', 404);

        $data = $this->validateAdmin($request, $admin->id, true);

        $payload = [
            'id' => $admin->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => 'admin',
            'created_by' => $admin->created_by,
            'recipient_email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $this->prepareOtpAction($request, 'admin.update', $payload);

        return redirect()->route('otp.show')->with('success', 'OTP generated. Verify to complete admin update.');
    }

    public function destroy(Request $request, User $admin)
    {
        abort_unless($admin->role === 'admin', 404);

        $payload = [
            'id' => $admin->id,
            'recipient_email' => $admin->email,
        ];

        $this->prepareOtpAction($request, 'admin.destroy', $payload);

        return redirect()->route('otp.show')->with('success', 'OTP generated. Verify to complete admin deletion.');
    }

    private function validateAdmin(Request $request, ?int $ignoreId = null, bool $update = false): array
    {
        $passwordRules = $update ? ['nullable', 'string', 'min:8', 'confirmed'] : ['required', 'string', 'min:8', 'confirmed'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email' . ($ignoreId ? ',' . $ignoreId : '')],
            'password' => $passwordRules,
        ]);
    }

    private function prepareOtpAction(Request $request, string $action, array $payload): void
    {
        $otp = (string) random_int(100000, 999999);

        $otpRecord = OtpCode::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'payload' => $payload,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(10),
            'resend_count' => 0,
        ]);

        $request->session()->put('otp_action', [
            'otp_id' => $otpRecord->id,
        ]);

        $recipientEmail = $payload['recipient_email'] ?? $request->user()->email;

        try {
            Mail::to($recipientEmail)->send(new OtpMail($otp, $action, $recipientEmail));
        } catch (Throwable $throwable) {
            report($throwable);
            $request->session()->flash('error', 'OTP was created, but email delivery failed. Check SMTP credentials or mail provider settings.');
        }
    }
}