<?php

declare(strict_types=1);

namespace App\Modules\Event\Service;

use JsonException;
use App\Modules\Event\DTO\EventMessageData;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class EventMessageHandler
{
    /**
     * @throws JsonException
     */
    public function __invoke(EventMessageData $data): void
    {
        echo 'Начало обработки сообщения \n';
        sleep(1);
        echo 'Проверка ' . json_encode($data->all(), JSON_THROW_ON_ERROR) . '\n';
        echo 'Завершение обработки сообщения \n';
    }
}
