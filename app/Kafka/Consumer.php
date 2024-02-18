<?php

namespace App\Kafka;

use RdKafka\KafkaConsumer;

class Consumer
{
    public function __construct(private KafkaConsumer $consumer)
    {
    }

    public function consume(string $topic)
    {
        $this->consumer->subscribe([$topic]);
        while (true) {
            $message = $this->consumer->consume(120 * 1000);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    // Process the consumed message
                    echo $message->payload . PHP_EOL;
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    // End of partition, no more messages
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    // No message within the given timeout
                    break;
                default:
                    // Handle other errors
                    echo $message->errstr() . PHP_EOL;
                    break;
            }
        }
    }
}
