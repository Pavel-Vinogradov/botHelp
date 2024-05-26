<?php

declare(strict_types=1);

namespace App\Modules\Event\Model;

use Generator;
use Random\RandomException;

final class EventGenerator
{
    public const ACCOUNT_COUNT = 1000;
    public const EVENT_COUNT = 10000;

    /**
     * @throws RandomException
     */
    public static function generateEvents(int $accountCount = self::ACCOUNT_COUNT, int $eventsCount = self::EVENT_COUNT): Generator
    {
        for ($i = 0; $i < $eventsCount; $i++) {
            $accountId = random_int(1, $accountCount);
            $eventId = $i + 1;

            yield [
                'eventId' => $eventId,
                'accountId' => $accountId,
            ];
        }
    }
}
