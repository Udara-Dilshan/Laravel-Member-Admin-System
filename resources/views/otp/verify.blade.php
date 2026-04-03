@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="form-card bg-white p-4 p-lg-5">
                <div class="text-center mb-4">
                    <div class="display-5 text-primary mb-2"><i class="bi bi-shield-check"></i></div>
                    <h1 class="h3 fw-bold">OTP Verification</h1>
                    <p class="text-muted mb-0">Enter the 6-digit code to complete the pending admin action.</p>
                </div>

                <div class="mb-4 p-3 rounded-4 bg-light border">
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <div>
                            <div class="text-muted small text-uppercase">Action</div>
                            <div class="fw-semibold text-capitalize">{{ str_replace('.', ' ', $otp->action) }}</div>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase">Expires</div>
                            <div class="fw-semibold">{{ $otp->expires_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                    @if($recipientEmail)
                        <div class="alert alert-info mt-3 mb-0">
                            OTP sent to: <strong>{{ $recipientEmail }}</strong>
                        </div>
                    @else
                        <div class="alert alert-info mt-3 mb-0">
                            OTP has been sent to the configured email address.
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('otp.verify') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">OTP Code</label>
                        <input type="text" name="otp" maxlength="6" class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" placeholder="Enter 6 digits" required>
                        @error('otp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Verify & Continue</button>
                        <button type="submit" formaction="{{ route('otp.resend') }}" formmethod="POST" class="btn btn-outline-secondary btn-lg">Resend OTP</button>
                        <button type="submit" formaction="{{ route('otp.cancel') }}" formmethod="POST" class="btn btn-outline-danger btn-lg">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection