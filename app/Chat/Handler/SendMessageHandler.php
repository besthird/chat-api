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
use App\Service\Dao\UserDao;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\WebSocketServer\Sender;
use Swoole\WebSocket\Server;

class SendMessageHandler implements HandlerInterface
{
    /**
     * @Inject
     * @var UserDao
     */
    protected $dao;

    /**
     * @Inject
     * @var UserService
     */
    protected $service;

    /**
     * @Inject
     * @var Sender
     */
    protected $sender;

    /**
     * @Inject
     * @var ErrorMessageHandler
     */
    protected $errorHandler;

    /**
     * @param Server $server
     * @param int $fd
     * @param $data = [
     *     'protocal' => 'send.message',
     *     'data' => [
     *         'id' => 1, // 目标ID
     *         'message' => 'Hello World.'
     *     ]
     * ]
     */
    public function handle(Server $server, int $fd, $data)
    {
        $id = $data['data']['id'] ?? 0;
        $message = $data['data']['message'] ?? null;

        if ($id && ! is_null($message)) {
            $user = $this->dao->first($id);
            if (empty($user)) {
                $this->errorHandler->handle($server, $fd, [
                    'message' => '目标用户不存在',
                ]);
                return;
            }

            amqp_produce(new SendMessageProducer($user->token, $data));
        }
    }
}
