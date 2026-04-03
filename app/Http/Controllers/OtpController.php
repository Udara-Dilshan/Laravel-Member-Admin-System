<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OtpController extends Controller
{
    public function show(Request $request)
    {
        $otp = $this->currentOtp($request);

        if (! $otp) {
            return redirect()->route('dashboard')->with('error', 'No pending OTP action was found.');
        }

        return view('otp.verify', [
            'otp' => $otp,
            'recipientEmail' => $otp->payload['recipient_email'] ?? null,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $otp = $this->currentOtp($request);

        if (! $otp) {
            return redirect()->route('dashboard')->with('error', 'No pending OTP action was found.');
        }

        if ($otp->isUsed() || $otp->isExpired()) {
            return back()->withErrors(['otp' => 'This OTP has expired. Please resend a new code.']);
        }

        if (! Hash::check($request->input('otp'), $otp->otp_hash)) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        $resultMessage = $this->executePendingAction($otp);

        return redirect()->route('admins.index')->with('success', $resultMessage);
    }

    public function resend(Request $request)
    {
        $otp = $this->currentOtp($request);

        if (! $otp) {
            return redirect()->route('dashboard')->with('error', 'No pending OTP action was found.');
        }

        $newOtp = (string) random_int(100000, 999999);

        $otp->update([
            'otp_hash' => Hash::make($newOtp),
            'expires_at' => now()->addMinutes(10),
            'resend_count' => $otp->resend_count + 1,
            'used_at' => null,
        ]);

        $recipientEmail = $otp->payload['recipient_email'] ?? $request->user()->email;

        try {
            Mail::to($recipientEmail)->send(new OtpMail($newOtp, $otp->action, $recipientEmail));
        } catch (Throwable $throwable) {
            report($throwable);

            return back()->withErrors(['otp' => 'OTP resend failed. Check SMTP credentials or mail provider settings.']);
        }

        return back()->with('success', 'A new OTP has been generated.');
    }

    public function cancel(Request $request)
    {
        $otp = $this->currentOtp($request);

        if ($otp) {
            $otp->delete();
        }

        $request->session()->forget('otp_action');

        return redirect()->route('admins.index')->with('success', 'OTP action cancelled.');
    }

    private function currentOtp(Request $request): ?OtpCode
    {
        $otpId = $request->session()->get('otp_action.otp_id');

        if (! $otpId) {
            return null;
        }

        return OtpCode::find($otpId);
    }

    private function executePendingAction(OtpCode $otp): string
    {
        $payload = $otp->payload ?? [];
        $message = match ($otp->action) {
            'admin.store' => $this->createAdmin($payload),
            'admin.update' => $this->updateAdmin($payload),
            'admin.destroy' => $this->deleteAdmin($payload),
            default => 'Unknown OTP action.',
        };

        $otp->update(['used_at' => now()]);

        return $message;
    }

    private function createAdmin(array $payload): string
    {
        User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'role' => 'admin',
            'created_by' => $payload['created_by'] ?? auth()->user()->name,
        ]);

        $this->clearOtpSession();

        return 'Admin created successfully.';
    }

    private function updateAdmin(array $payload): string
    {
        $admin = User::where('role', 'admin')->findOrFail($payload['id']);

        $admin->name = $payload['name'];
        $admin->email = $payload['email'];
        $admin->role = 'admin';

        if (! empty($payload['password'])) {
            $admin->password = $payload['password'];
        }

        $admin->created_by = $payload['created_by'] ?? $admin->created_by;
        $admin->save();

        $this->clearOtpSession();

        return 'Admin updated successfully.';
    }

    private function deleteAdmin(array $payload): string
    {
        $admin = User::where('role', 'admin')->findOrFail($payload['id']);

        if ($admin->id === auth()->id()) {
            $this->clearOtpSession();

            return 'You cannot delete the currently logged-in admin account.';
        }

        $admin->delete();
        $this->clearOtpSession();

        return 'Admin deleted successfully.';
    }

    private function clearOtpSession(): void
    {
        session()->forget('otp_action');
    }
}