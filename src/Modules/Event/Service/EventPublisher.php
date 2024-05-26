<?php

declare(strict_types=1);

namespace App\Modules\Event\Service;

use App\Modules\Event\DTO\EventMessageData;

interface EventPublisher
{
    public function publish(EventMessageData $eventMessageData): void;
}
