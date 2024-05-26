<?php

declare(strict_types=1);

namespace App\Modules\Event\Controller;

use App\Modules\Event\DTO\EventMessageData;
use App\Modules\Event\Request\EventRequest;
use App\Modules\Event\Service\EventPublisher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/event')]
final class EventController extends AbstractController
{
    public function __construct(private readonly EventPublisher $eventPublisher) {}

    /**
     * @throws UnknownProperties
     */
    #[Route(name: 'add_event', methods: ['POST'])]
    public function publishEvent(EventRequest $request): Response
    {
        $this->eventPublisher->publish(new EventMessageData($request->all()));

        return new JsonResponse(['message' => 'Event published'], Response::HTTP_OK);
    }
}
