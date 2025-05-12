<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\UserNotificationComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', UserNotificationComposer::class);
    }

    public function register() {}
}
