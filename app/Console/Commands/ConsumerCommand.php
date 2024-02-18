<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Kafka\Consumer;
use Illuminate\Console\Command;
use Junges\Kafka\Config\Config;
use Junges\Kafka\Config\Sasl;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Consuming messages from kafka');
        app(Consumer::class)->consume('pnquxavk-default');
    }
}
