<?php

namespace App\Providers;

use App\Models\UserProfile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $profile = UserProfile::ambil();
                $view->with('global_profile', $profile);
            } catch (\Exception $e) {
                //
            }
        });
    }
}