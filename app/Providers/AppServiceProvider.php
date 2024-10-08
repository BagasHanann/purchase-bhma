<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\RequestItems;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $pendingRequestsCount = RequestItems::where('status', 'Pending')->count();
            $view->with('pendingRequestsCount', $pendingRequestsCount);
        });
    }
}
