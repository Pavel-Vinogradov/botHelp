<?php

declare(strict_types=1);

namespace App\Modules\Event\Service;

use App\Modules\Event\DTO\EventMessageData;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventPublisherImpl implements EventPublisher
{
    public function __construct(private MessageBusInterface $bus) {}

    public function publish(EventMessageData $eventMessageData): void
    {
        $this->bus->dispatch($eventMessageData);
    }
}
