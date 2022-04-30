<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Layer\Collector;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ServiceLocator;
use function array_keys;
use function sprintf;

final class CollectorProvider implements ContainerInterface
{
    private ServiceLocator $collectorLocator;

    public function __construct(ServiceLocator $collectorLocator)
    {
        $this->collectorLocator = $collectorLocator;
    }

    public function get(string $id): CollectorInterface
    {
        $collector = $this->collectorLocator->get($id);

        if (!$collector instanceof CollectorInterface) {
            $message = sprintf(
                'Type "%s" is not valid collector (expected "%s", but is "%s").',
                $id,
                CollectorInterface::class,
                get_debug_type($collector)
            );

            throw new RuntimeException($message);
        }

        return $collector;
    }

    public function has(string $id): bool
    {
        return $this->collectorLocator->has($id);
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return string[]
     */
    public function getKnownCollectors(): array
    {
        return array_keys($this->collectorLocator->getProvidedServices());
    }
}