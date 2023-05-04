<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder;

use Takeoto\ObjectBuilder\Contract\BuilderProviderInterface;
use Takeoto\ObjectBuilder\Contract\BuilderRegisterInterface;
use Takeoto\ObjectBuilder\Contract\ConfigurableBuilderProviderInterface;
use Takeoto\ObjectBuilder\Contract\ExtendableBuilderProviderInterface;
use Takeoto\ObjectBuilder\Contract\ObjectBuilderInterface;

class BuilderProvider implements ConfigurableBuilderProviderInterface, ExtendableBuilderProviderInterface
{
    /**
     * @phpstan-ignore-next-line - the generic doesn't work correctly at properties
     * @var ObjectBuilderInterface[]
     */
    private array $builders = [];

    /**
     * @var BuilderProviderInterface[]
     */
    private array $providers = [];

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return ObjectBuilderInterface<T>
     * @throws \Throwable
     */
    public function for(string $class): ObjectBuilderInterface
    {
        return $this->findBuilder($class) ?? throw new \LogicException(sprintf(
            'The builder fo building "%s" object does not exists.',
            $class,
        ));
    }

    /**
     * @param class-string $class
     * @return bool
     * @throws \Throwable
     */
    public function has(string $class): bool
    {
        return $this->findBuilder($class) !== null;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return ObjectBuilderInterface<T>|null
     * @throws \Throwable
     */
    private function findBuilder(string $class): ?ObjectBuilderInterface
    {
        if (isset($this->builders[$class])) {
            return $this->builders[$class];
        }

        foreach ($this->providers as $provider) {
            if ($provider->has($class)) {
                return $this->builders[$class] = $provider->for($class);
            }
        }

        return null;
    }

    /**
     * @template T of object
     * @param ObjectBuilderInterface<T>|BuilderRegisterInterface $builder
     * @param class-string<T>|null $class
     * @return void
     */
    public function register(ObjectBuilderInterface|BuilderRegisterInterface $builder, string $class = null): void
    {
        if ($builder instanceof BuilderRegisterInterface) {
            $builder->register($this);

            return;
        }

        if (null === $class) {
            throw new \InvalidArgumentException('A $class must be provided along with the builder');
        }

        $this->builders[$class] = $builder;
    }

    public function attach(BuilderProviderInterface $provider): void
    {
        $this->providers[$provider::class] = $provider;
    }
}