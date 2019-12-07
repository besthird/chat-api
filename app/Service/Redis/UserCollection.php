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

namespace App\Service\Redis;

use App\Service\Obj\UserObj;
use Xin\RedisCollection\StringCollection;

class UserCollection extends StringCollection
{
    /**
     * @var string
     */
    protected $prefix = 'user:token:';

    public function redis()
    {
        return di()->get(\Redis::class);
    }

    public function save(UserObj $obj)
    {
        $str = serialize($obj);

        return $this->set($obj->token, $str, null);
    }

    public function find(string $token): ?UserObj
    {
        $str = $this->get($token);

        if ($str) {
            return unserialize($str);
        }

        return null;
    }
}
