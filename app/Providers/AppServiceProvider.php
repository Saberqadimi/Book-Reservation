<?php

namespace App\Providers;

use App\Events\BookReturned;
use App\Listeners\NotifyNextUser;
use App\Models\BookCopy;
use App\Policies\BookCopyPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        BookCopy::class => BookCopyPolicy::class,
    ];
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

        Gate::policy(BookCopy::class, BookCopyPolicy::class);
    }
}
