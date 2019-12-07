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

use Swoole\WebSocket\Server;

interface HandlerInterface
{
    /**
     * @param $data
     */
    public function handle(Server $server, int $fd, $data);
}
