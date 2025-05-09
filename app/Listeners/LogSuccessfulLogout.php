<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Logout $event)
    {
        LoginHistory::where('user_id', $event->user->id)
            ->whereNull('logout_time')
            ->latest()
            ->first()
            ?->update(['logout_time' => now()]);
    }
}
