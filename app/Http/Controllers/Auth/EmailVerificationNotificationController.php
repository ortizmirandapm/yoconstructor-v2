<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $tipo = $request->user()->tipo->value;

            $redirect = match ($tipo) {
                'empresa' => route('empresa.dashboard'),
                'admin' => route('admin.dashboard'),
                default => route('home'),
            };

            return redirect()->intended($redirect);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
