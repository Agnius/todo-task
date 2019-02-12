<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use App\Events\LogActionEvent;
use App\Models\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogActionListener
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
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(LogActionEvent $event)
    {
        Log::create([
            'class' => $event->class,
            'action' => $event->action,
            'value' => $event->value,
        ]);
    }
}
