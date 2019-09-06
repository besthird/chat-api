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

namespace App\Amqp\Consumer;

use App\Chat\Node;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Builder\QueueBuilder;
use Hyperf\Amqp\Message\ConsumerMessage;
use Hyperf\Amqp\Result;
use Hyperf\WebSocketServer\Sender;

/**
 * @Consumer(exchange="chat", routingKey="send.message", name="SendMessageConsumer", nums=1)
 */
class SendMessageConsumer extends ConsumerMessage
{
    /**
     * @param $data = [
     *     'fd' => 1,
     *     'data' => [],
     * ]
     * @return string
     */
    public function consume($data): string
    {
        $fd = $data['fd'];
        $data = $data['data'];

        $sender = di()->get(Sender::class);

        $sender->push($fd, json_encode($data));

        return Result::ACK;
    }

    public function getQueue(): string
    {
        if (is_string($this->queue)) {
            return $this->queue;
        }

        $node = di()->get(Node::class);

        return $this->queue = 'send.message.' . $node->getId();
    }

    public function getQueueBuilder(): QueueBuilder
    {
        return (new QueueBuilder())->setQueue($this->getQueue())->setAutoDelete(true);
    }
}
