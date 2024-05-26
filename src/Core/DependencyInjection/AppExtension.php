<?php

declare(strict_types=1);

namespace App\Core\DependencyInjection;

use App\Modules\Event\Service\EventPublisher;
use App\Modules\Event\Service\EventPublisherImpl;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class AppExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(EventPublisherImpl::class, EventPublisherImpl::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->addTag('messenger.message_handler');

        $container->setAlias(EventPublisher::class, EventPublisherImpl::class);
    }
}
