<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
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

        return view('auth.verify-email');
    }
}
