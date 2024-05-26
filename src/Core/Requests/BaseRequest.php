<?php

declare(strict_types=1);

namespace App\Core\Requests;

use ReflectionClass;
use ArrayHelpers\Arr;
use ReflectionException;
use App\Core\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    private const FORMAT_JSON = 'json';
    private const FORMAT_FORM_DATA = 'form-data';
    private const FORMAT_FORM_URLENCODED = 'form';

    /**
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function __construct(
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack,
    ) {
        $this->populate();
        $this->validate();
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Get all the input and files for the request.
     */
    public function all(mixed $keys = null): array
    {
        $request = $this->getRequest();
        $data = $this->parseRequestBody($request);

        if (null === $keys) {
            return $data;
        }

        $keys = is_array($keys) ? $keys : func_get_args();
        $results = [];

        foreach ($keys as $key) {
            Arr::set($results, $key, Arr::get($data, $key));
        }

        return $results;
    }

    /**
     * @throws ReflectionException|ValidationException
     */
    protected function populate(): void
    {
        $request = $this->getRequest();
        if (!$request->getContent()) {
            throw new ValidationException(
                [
                    'type' => 'validation',
                    'message' => 'Request body is empty',
                ]
            );
        }
        if (!self::isValidFormat($request)) {
            throw new ValidationException([
                'type' => 'validation',
                'message' => 'expected application/json, multipart/form-data, or application/x-www-form-urlencoded on header Content-Type request']);
        }

        $reflection = new ReflectionClass($this);

        $data = $this->parseRequestBody($request);

        foreach ($data as $property => $value) {
            $attribute = self::camelCase($property);
            if (property_exists($this, $attribute)) {
                $reflectionProperty = $reflection->getProperty($attribute);
                settype($value, $reflectionProperty->getType()?->getName());
                $reflectionProperty->setValue($this, $value);
            }
        }
    }

    /**
     * @throws ValidationException
     */
    protected function validate(): void
    {
        $violations = $this->validator->validate($this);
        if (1 > count($violations)) {
            return;
        }

        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'type' => 'validation',
                'property' => self::camelCase($violation->getPropertyPath()),
                'value' => $violation->getInvalidValue(),
                'message' => $violation->getMessage(),
            ];
        }

        throw new ValidationException($errors);
    }

    private function parseRequestBody(Request $request): array
    {
        return match ($request->getContentTypeFormat()) {
            self::FORMAT_JSON => $request->toArray(),
            self::FORMAT_FORM_DATA, self::FORMAT_FORM_URLENCODED => $request->request->all(),
            default => [],
        };
    }

    private static function isValidFormat(Request $request): bool
    {
        $format = $request->getContentTypeFormat();

        return in_array($format, self::getFormatsAvailable(), true);
    }

    private static function getFormatsAvailable(): array
    {
        return [self::FORMAT_JSON, self::FORMAT_FORM_DATA, self::FORMAT_FORM_URLENCODED];
    }

    private static function camelCase(string $attribute): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $attribute))));
    }
}
