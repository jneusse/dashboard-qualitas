<?php

namespace App\Listeners;

use App\Events\AlertInteraction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AlertInteractionListener
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
     * @param  AlertInteracction  $event
     * @return void
     */
    public function handle(AlertInteraction $event)
    {
        //
    }
}
