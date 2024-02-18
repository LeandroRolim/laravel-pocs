<?php

namespace App\Kafka;

use RuntimeException;

class Producer
{
    public function __construct(private \RdKafka\Producer $producer)
    {
    }


    public function send(string $msg,  string $topic)
    {
        $kafkaTopic = $this->producer->newTopic($topic);
        $kafkaTopic->produce(RD_KAFKA_PARTITION_UA, 0, $msg);
        $result = $this->producer->flush(10000);

        var_dump($result);
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new RuntimeException('Erro ao enviar mensagem');
        }
    }
}
