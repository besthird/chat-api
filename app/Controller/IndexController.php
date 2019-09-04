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

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;

class IndexController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.
    }

    public function onMessage(Server $server, Frame $frame): void
    {
        // TODO: Implement onMessage() method.
    }

    public function onOpen(Server $server, Request $request): void
    {
        // TODO: Implement onOpen() method.
    }
}
