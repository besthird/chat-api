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

use App\Chat\Handler\ErrorMessageHandler;
use App\Model\User;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;

class IndexController extends Controller implements OnMessageInterface, OnOpenInterface, OnCloseInterface
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
        $token = $this->request->input('token');

        $user = User::query()->where('token', $token)->first();
        if (empty($user)) {
            di()->get(ErrorMessageHandler::class)->handle($server, $request->fd, [
                'message' => 'The Token is invalid.',
                'close' => true,
            ]);
            return;
        }
    }
}
