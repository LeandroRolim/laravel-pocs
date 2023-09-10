<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use OpenTelemetry\API\Trace\Span;

class EndTraceListener
{
    /**
     * Handle the event.
     */
    public function handle(\Illuminate\Foundation\Http\Events\RequestHandled $event): void
    {
        $span = Span::getCurrent();
        $span->updateName($event->request->path());
        $span->end();
    }
}
