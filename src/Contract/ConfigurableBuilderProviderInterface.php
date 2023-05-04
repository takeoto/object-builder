<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder\Contract;

interface ConfigurableBuilderProviderInterface extends BuilderProviderInterface
{
    /**
     * @template T of object
     * @param ObjectBuilderInterface<T>|BuilderRegisterInterface $builder
     * @param class-string<T>|null $class
     * @return void
     */
    public function register(ObjectBuilderInterface|BuilderRegisterInterface $builder, string $class = null): void;
}