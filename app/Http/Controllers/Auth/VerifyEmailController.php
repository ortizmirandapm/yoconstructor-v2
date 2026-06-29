<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $tipo = $request->user()->tipo->value;

        $redirect = match ($tipo) {
            'empresa' => route('empresa.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('home'),
        };

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($redirect.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($redirect.'?verified=1');
    }
}
