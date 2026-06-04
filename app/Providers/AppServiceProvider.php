<?php

namespace App\Providers;

use App\Models\Oferta;
use App\Observers\OfertaObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Oferta::observe(OfertaObserver::class);
    }
}