<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Ticket;
use App\Observers\TicketObserver;

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
        /**
         * ✅ Morph Map for Activity Logs
         * This ensures "Ticket" stored in subject_type
         * correctly resolves to App\Models\Ticket
         */
        Relation::morphMap([
            'Ticket' => Ticket::class,
        ]);

        /**
         * ✅ Register Ticket Observer
         * Automatically logs create, update, delete actions
         */
        Ticket::observe(TicketObserver::class);
    }
}
