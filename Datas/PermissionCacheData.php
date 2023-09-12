<?php

declare(strict_types=1);

namespace Modules\User\Datas;

use DateInterval;
use Spatie\LaravelData\Data;

/**
 * Undocumented class.
 */
final class PermissionCacheData extends Data
{
    public DateInterval $expiration_time;
     // => \DateInterval::createFromDateString('24 hours'),
    public string $key;
     // => 'spatie.permission.cache',
    public string $store; // => 'default',
}
