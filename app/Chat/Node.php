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
namespace App\Chat;

use Hyperf\Nsq\Nsqd\Api;
use Hyperf\Nsq\Nsqd\Channel;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Codec\Json;

class Node
{
    /**
     * @var string
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getChannel(): string
    {
        return Constants::SEND_MESSAGE . '.' . $this->getId();
    }

    public function heartbeat()
    {
        $redis = di()->get(Redis::class);
        $redis->set($this->getChannel(), '1', 3600);
    }

    public function clear()
    {
        $channelApi = di()->get(Channel::class);
        $client = di()->get(Api::class);
        $redis = di()->get(Redis::class);
        $conotents = $client->stats('json', Constants::SEND_MESSAGE)->getBody()->getContents();
        $res = Json::decode($conotents);
        foreach ($res['topics'] ?? [] as $topic) {
            if (($topic['topic_name'] ?? null) !== Constants::SEND_MESSAGE) {
                continue;
            }

            $channels = $topic['channels'] ?? [];
            foreach ($channels as $channel) {
                if ($name = $channel['channel_name'] ?? null) {
                    if (! $redis->exists($name)) {
                        $channelApi->delete(Constants::SEND_MESSAGE, $name);
                    }
                }
            }
        }
    }
}
