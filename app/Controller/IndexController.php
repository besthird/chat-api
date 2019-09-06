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
use Hyperf\Di\Annotation\Inject;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;

class IndexController extends Controller implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    /**
     * @Inject
     * @var ErrorMessageHandler
     */
    protected $errorMessageHandler;

    public function onClose(Server $server, int $fd, int $reactorId): void
    {
        // TODO: Implement onClose() method.
    }

    public function onMessage(Server $server, Frame $frame): void
    {
        $fd = $frame->fd;
        $data = json_decode($frame->data, true);

        $protocal = 'protocal.' . $data['protocal'] ?? '';
        if (! $this->container->has($protocal)) {
            $this->errorMessageHandler->handle($server, $fd, [
                'message' => 'The Protocal is invalid.',
            ]);
        }

        var_dump($data);
    }

    public function onOpen(Server $server, Request $request): void
    {
        $token = $this->request->input('token');

        $user = User::query()->where('token', $token)->first();
        if (empty($user)) {
            $this->errorMessageHandler->handle($server, $request->fd, [
                'message' => 'The Token is invalid.',
                'close' => true,
            ]);
            return;
        }
    }
}
