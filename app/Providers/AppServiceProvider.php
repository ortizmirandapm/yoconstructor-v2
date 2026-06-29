<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Oferta;
use App\Observers\OfertaObserver;
use App\View\Composers\NavbarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Oferta::observe(OfertaObserver::class);

        View::composer(
            ['layouts.public-navbar', 'layouts.navigation'],
            NavbarComposer::class,
        );
    }
}
