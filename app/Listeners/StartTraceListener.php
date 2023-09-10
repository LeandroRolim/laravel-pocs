<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Application;
use Illuminate\Queue\InteractsWithQueue;
use OpenTelemetry\API\Globals;

class StartTraceListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Application $event): void
    {
        $span = Globals::tracerProvider()
            ->getTracer(config('app.name'))
            ->spanBuilder('boot')
            ->startSpan();
        $span->activate();
    }
}
