<?php

declare(strict_types=1);

namespace App\Core\Listener;

use App\Core\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final readonly class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ValidationException) {
            $response = new JsonResponse(
                data: ['errors' => $exception->getErrors()],
                status: $exception->getStatusCode(),
            );
            $event->setResponse($response);
        }
    }
}
