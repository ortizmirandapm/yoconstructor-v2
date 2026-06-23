<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Oferta;
use App\Observers\OfertaObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Oferta::observe(OfertaObserver::class);

        View::composer(
            ['layouts.public-navbar', 'layouts.navigation'],
            \App\View\Composers\NavbarComposer::class,
        );
    }
}
