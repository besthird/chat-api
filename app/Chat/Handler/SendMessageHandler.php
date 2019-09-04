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
use Swoole\Server;
use Swoole\WebSocket\Frame;

class SendMessageHandler implements HandlerInterface
{
    public function handle(Server $server, Frame $frame)
    {
        // TODO: Implement handle() method.
    }
}
