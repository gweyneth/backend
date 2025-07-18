<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Perusahaan;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $perusahaan = Perusahaan::first(); 
            $view->with('perusahaan', $perusahaan);
        });
        Paginator::useBootstrap();
    }
}
