<?php

declare(strict_types=1);

namespace App\Providers;

use App\Kafka\Consumer;
use App\Kafka\Producer;
use Illuminate\Support\ServiceProvider;
use RdKafka\Conf;

class KafkaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->alias('KafkaConfig', Conf::class);
        $this->app->singleton(Conf::class, function () {
            $config =  new Conf();
            $config->set('metadata.broker.list', config('kafka.brokers'));
            $config->set('bootstrap.servers', config('kafka.brokers'));
            $config->set('sasl.mechanisms', config('kafka.sasl.mechanisms'));
            $config->set('security.protocol', config('kafka.securityProtocol'));
            $config->set('sasl.username', config('kafka.sasl.username'));
            $config->set('sasl.password', config('kafka.sasl.password'));
            $config->set('group.id', 'pnquxavk-laravel');
            return $config;
        });

        $this->app->singleton(Producer::class, Producer::class);
        $this->app->singleton(Consumer::class, Consumer::class);
    }


}
