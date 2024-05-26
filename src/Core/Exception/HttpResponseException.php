<?php

declare(strict_types=1);

namespace App\Core\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class HttpResponseException extends RuntimeException
{
    protected Response $response;

    /**
     * Create a new HTTP response exception instance.
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get the underlying response instance.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
