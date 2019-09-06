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

use App\Chat\HandlerInterface;
use Swoole\WebSocket\Server;

class ErrorMessageHandler implements HandlerInterface
{
    /**
     * @param Server $server
     * @param int $fd
     * @param $data = [
     *     'message' => '错误信息',
     *     'close' => false, // 是否前置关闭客户端
     * ]
     */
    public function handle(Server $server, int $fd, $data)
    {
        $server->push($fd, json_encode($data));
        if ($data['close'] ?? false) {
            $server->close($fd);
        }
    }
}
