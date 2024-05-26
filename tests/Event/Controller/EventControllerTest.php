<?php

declare(strict_types=1);

namespace App\Tests\Event\Controller;

use App\Modules\Event\DTO\EventMessageData;
use App\Modules\Event\Service\EventPublisher;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\CoversNothing;

/**
 * @internal
 */
#[CoversNothing]
final class EventControllerTest extends WebTestCase
{
    /**
     * @throws Exception
     * @throws \JsonException
     */
    public function testPublishEventSuccess(): void
    {
        $client = self::createClient();

        // Mock EventPublisher
        $eventPublisherMock = $this->createMock(EventPublisher::class);
        $eventPublisherMock->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(EventMessageData::class));

        self::getContainer()->set(EventPublisher::class, $eventPublisherMock);

        $client->request(
            Request::METHOD_POST,
            '/api/event',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'accountId' => '12345',
                'eventId' => '67890',
                'otherData' => 'value'
            ], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('Event published', $client->getResponse()->getContent());
    }

    /**
     * @throws Exception
     * @throws \JsonException
     */
    public function testPublishEventValidationError(): void
    {
        $client = self::createClient();

        // No need to mock EventPublisher for validation error test

        $client->request(
            Request::METHOD_POST,
            '/api/event',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['invalidField' => 'value'], JSON_THROW_ON_ERROR)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('validation', $client->getResponse()->getContent());
        $this->assertStringContainsString('accountId', $client->getResponse()->getContent());
        $this->assertStringContainsString('eventId', $client->getResponse()->getContent());
    }
}
