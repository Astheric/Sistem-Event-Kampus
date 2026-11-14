<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Payment;
use App\Policies\EventPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
    }
}
