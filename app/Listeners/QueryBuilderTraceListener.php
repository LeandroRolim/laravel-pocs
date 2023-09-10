<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\Span;

class QueryBuilderTraceListener
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
    public function handle(QueryExecuted $queryExecuted): void
    {
        Globals::tracerProvider()
            ->getTracer('query-executed')
            ->spanBuilder($queryExecuted->sql)
            ->setStartTimestamp((now()->getTimestampMs() - $queryExecuted->time) * 1000000)
            ->startSpan()
            ->setAttributes([
                'db.statement' => $queryExecuted->sql,
                'db.connection' => $queryExecuted->connectionName,
                'db.binds' => json_encode($queryExecuted->bindings),
            ])
            ->end();
    }
}
