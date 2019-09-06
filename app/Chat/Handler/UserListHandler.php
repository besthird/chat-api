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

namespace App\Chat\Handler;

use App\Amqp\Producer\SendMessageProducer;
use App\Chat\HandlerInterface;
use App\Model\User;
use Swoole\WebSocket\Server;

class UserListHandler implements HandlerInterface
{
    /**
     * @param Server $server
     * @param int $fd
     * @param $data = [
     *     'protocal' => 'user.list'
     * ]
     */
    public function handle(Server $server, int $fd, $data)
    {
        // 查询所有在线的用户
        amqp_produce(new SendMessageProducer());
    }
}
