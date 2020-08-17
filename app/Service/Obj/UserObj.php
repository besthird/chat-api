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
namespace App\Service\Obj;

class UserObj
{
    public $token;

    public $fd;

    public $node;

    public function __construct($token, $fd, $node)
    {
        $this->token = $token;
        $this->fd = $fd;
        $this->node = $node;
    }
}
