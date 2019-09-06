<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * @Producer(exchange="chat", routingKey="send.message")
 */
class SendMessageProducer extends ProducerMessage
{
    public function __construct(string $token, array $data = [])
    {
        $this->payload = [
            'token' => $token,
            'data' => $data,
        ];
    }
}
