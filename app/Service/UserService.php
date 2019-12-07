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

namespace App\Service;

use App\Service\Obj\UserObj;
use App\Service\Redis\UserCollection;
use Hyperf\Di\Annotation\Inject;

class UserService extends Service
{
    public $users = [];

    /**
     * @Inject
     * @var UserCollection
     */
    protected $col;

    public function save(UserObj $obj)
    {
        $this->users[$obj->fd] = $obj;

        $this->col->save($obj);
    }

    public function find(int $fd): ?UserObj
    {
        return $this->users[$fd] ?? null;
    }

    public function delete(UserObj $obj)
    {
        unset($this->users[$obj->fd]);

        $this->col->delete($obj->token);
    }
}
