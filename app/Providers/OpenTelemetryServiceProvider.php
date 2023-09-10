<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Logs\LoggerProviderInterface;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Contrib\Grpc\GrpcTransportFactory;
use OpenTelemetry\Contrib\Otlp\LogsExporter;
use OpenTelemetry\Contrib\Otlp\MetricExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Common\Export\Stream\StreamTransportFactory;
use OpenTelemetry\SDK\Logs\LoggerProvider;
use OpenTelemetry\SDK\Logs\Processor\SimpleLogRecordProcessor;
use OpenTelemetry\SDK\Metrics\MeterProvider;
use OpenTelemetry\SDK\Metrics\MetricReader\ExportingReader;
use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
use OpenTelemetry\SDK\Sdk;
use OpenTelemetry\SDK\Trace\Sampler\AlwaysOnSampler;
use OpenTelemetry\SDK\Trace\Sampler\ParentBased;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SemConv\ResourceAttributes;

class OpenTelemetryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            TracerInterface::class,
            fn() => Globals::tracerProvider()->getTracer(config('app.name')));
        $this->app->bind(
            LoggerProviderInterface::class,
            fn() => Globals::loggerProvider()
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $resource = ResourceInfo::create(Attributes::create([
            ResourceAttributes::SERVICE_NAMESPACE => 'pocs',
            ResourceAttributes::SERVICE_NAME => 'laravel',
            ResourceAttributes::SERVICE_VERSION => '0.1',
            ResourceAttributes::DEPLOYMENT_ENVIRONMENT => 'development',
        ]));

        $spanExporter = new SpanExporter(
            (new OtlpHttpTransportFactory())
                ->create('http://otel-collector:4318/v1/traces', 'application/json')
        );
        $tracerProvider = TracerProvider::builder()
            ->addSpanProcessor(
                new SimpleSpanProcessor($spanExporter)
            )
            ->setResource($resource)
            ->setSampler(new AlwaysOnSampler())
            ->build();


        $reader = new ExportingReader(
            new MetricExporter(
                (new StreamTransportFactory())->create('php://stdout', 'application/json')
            )
        );

        $meterProvider = MeterProvider::builder()
            ->setResource($resource)
            ->addReader($reader)
            ->build();


        Sdk::builder()
            ->setTracerProvider($tracerProvider)
            ->setMeterProvider($meterProvider)
            ->setPropagator(TraceContextPropagator::getInstance())
            ->setAutoShutdown(true)
            ->buildAndRegisterGlobal();

    }
}
