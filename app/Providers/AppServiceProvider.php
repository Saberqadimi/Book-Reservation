<?php

namespace App\Providers;

use App\Events\BookReturned;
use App\Listeners\NotifyNextUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

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
    public function boot(): void
    {
        Event::listen(
            BookReturned::class,
            NotifyNextUser::class,
        );
    }
}
