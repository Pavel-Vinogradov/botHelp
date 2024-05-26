<?php

declare(strict_types=1);

namespace App\Modules\Event\DTO;

use Tizix\DataTransferObject\DataTransferObject;

final class EventMessageData extends DataTransferObject
{
    public int $accountId;
    public int $eventId;
}
