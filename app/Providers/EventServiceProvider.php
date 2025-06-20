<?php

namespace App\Providers;
// app/Providers/EventServiceProvider.php
namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogSuccessfulLogin::class,  // Mendaftarkan listener untuk event Login
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
