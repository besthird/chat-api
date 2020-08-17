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
namespace App\Nsq\Consumer;

use App\Chat\Constants;
use App\Chat\Node;
use App\Service\Redis\UserCollection;
use Hyperf\Nsq\AbstractConsumer;
use Hyperf\Nsq\Annotation\Consumer;
use Hyperf\Nsq\Message;
use Hyperf\Nsq\Result;
use Hyperf\Utils\Codec\Json;
use Hyperf\WebSocketServer\Sender;
use Psr\Container\ContainerInterface;

/**
 * @Consumer(topic=Constants::SEND_MESSAGE, name="SendMessageConsumer", nums=1)
 */
class SendMessageConsumer extends AbstractConsumer
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $node = $container->get(Node::class);

        $this->channel = $node->getChannel();
    }

    public function consume(Message $payload): ?string
    {
        $data = Json::decode($payload->getBody());

        $token = $data['token'];
        $data = $data['data'];

        $sender = $this->container->get(Sender::class);

        $obj = $this->container->get(UserCollection::class)->find($token);
        $node = $this->container->get(Node::class)->getId();
        if ($obj && $obj->node === $node) {
            $sender->push($obj->fd, json_encode($data));
        }

        return Result::ACK;
    }
}
