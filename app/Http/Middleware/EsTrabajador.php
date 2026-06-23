<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserTipo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EsTrabajador
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->tipo === UserTipo::Trabajador->value) {
            return $next($request);
        }

        abort(403);
    }
}
