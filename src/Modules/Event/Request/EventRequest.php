<?php

declare(strict_types=1);

namespace App\Modules\Event\Request;

use App\Core\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

final class EventRequest extends BaseRequest
{
    #[NotBlank]
    #[Type('int')]
    #[Positive]
    // PHPStan: @phpstan-ignore-next-line
    public readonly int $accountId;
    #[NotBlank]
    #[Type('int')]
    #[Positive]
    // PHPStan: @phpstan-ignore-next-line
    public readonly int $eventId;
}
