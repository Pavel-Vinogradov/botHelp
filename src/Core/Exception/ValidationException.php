<?php

declare(strict_types=1);

namespace App\Core\Exception;

use JsonException;
use AllowDynamicProperties;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[AllowDynamicProperties]
final class ValidationException extends HttpException
{
    private array $errors;

    /**
     * @throws JsonException
     */
    public function __construct(array $errors, int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        $this->errors = $errors;
        parent::__construct($statusCode, message: json_encode(['errors' => $errors], JSON_THROW_ON_ERROR));
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
