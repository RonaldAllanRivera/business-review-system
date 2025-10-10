<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendWelcomeEmail;
use App\Events\UserActivityOccurred;
use App\Listeners\StoreUserActivity;

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
        // Return flat JSON resources without the default "data" wrapper
        JsonResource::withoutWrapping();

        // Event listeners
        Event::listen(Registered::class, SendWelcomeEmail::class);
        Event::listen(UserActivityOccurred::class, StoreUserActivity::class);
    }
}
