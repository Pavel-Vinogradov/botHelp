<?php

declare(strict_types=1);

namespace App\Console;

use JsonException;
use Random\RandomException;
use Psr\Log\LoggerInterface;
use App\Modules\Event\Model\EventGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

#[AsCommand(name: 'app:event')]
final class ConsoleEventsCommand extends Command
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    /**
     * @throws JsonException
     * @throws RandomException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting');
        $startTime = microtime(true);

        foreach (EventGenerator::generateEvents() as $event) {
            try {
                $response = $this->client->request('POST', 'http://localhost:4200/api/event', [
                    'json' => $event,
                    'timeout' => 30,
                ]);
                $statusCode = $response->getStatusCode();
                if (Response::HTTP_OK === $statusCode) {
                    $output->writeln(json_encode($response->toArray(), JSON_THROW_ON_ERROR));
                } else {
                    $this->logger->error('Failed to send event', ['status_code' => $statusCode]);

                    return Command::FAILURE;
                }
            } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
                $this->logger->error('HTTP request failed', ['exception' => $e]);

                return Command::FAILURE;
            }
        }
        $this->outputExecutionTime($startTime, microtime(true), $output);

        return Command::SUCCESS;
    }

    private function outputExecutionTime(float $startTime, float $endTime, OutputInterface $output): void
    {
        $executionTime = $endTime - $startTime;
        $output->writeln(sprintf('Execution time: %.2f seconds', $executionTime));
    }
}
