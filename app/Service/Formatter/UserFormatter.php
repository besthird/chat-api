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
namespace App\Service\Formatter;

use App\Model\User;
use Hyperf\Utils\Traits\StaticInstance;

class UserFormatter
{
    use StaticInstance;

    public function base(User $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'is_online' => $model->is_online,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }
}
