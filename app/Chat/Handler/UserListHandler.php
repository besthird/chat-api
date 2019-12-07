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

namespace App\Chat\Handler;

use App\Chat\HandlerInterface;
use App\Model\User;
use App\Service\Dao\UserDao;
use App\Service\Formatter\UserFormatter;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Swoole\WebSocket\Server;

class UserListHandler implements HandlerInterface
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
     * @param $data = [
     *     'protocal' => 'user.list'
     * ]
     */
    public function handle(Server $server, int $fd, $data)
    {
        // 查询所有在线的用户
        $users = $this->dao->findOnline();
        $mine = $this->service->find($fd);

        $result = [];
        foreach ($users as $user) {
            $item = UserFormatter::instance()->base($user);
            if ($mine->token == $user->token) {
                $item['own'] = true;
            }

            $result[] = $item;
        }

        $data['list'] = $result;
        $server->push($fd, json_encode($data));
    }
}
