<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Amqp\Consumer;

use App\Chat\Node;
use App\Service\Redis\UserCollection;
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
     *     'token' => 'limx',
     *     'data' => [],
     * ]
     */
    public function consume($data): string
    {
        $token = $data['token'];
        $data = $data['data'];

        $sender = di()->get(Sender::class);

        $obj = di()->get(UserCollection::class)->find($token);
        $node = di()->get(Node::class)->getId();
        if ($obj && $obj->node === $node) {
            $sender->push($obj->fd, json_encode($data));
        }

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
