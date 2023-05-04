<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder\Contract;

interface BuilderProviderInterface
{
    /**
     * @template T of object
     * @param class-string<T> $class
     * @return ObjectBuilderInterface<T>
     * @throws \Throwable
     */
    public function for(string $class): ObjectBuilderInterface;

    /**
     * @param class-string $class
     * @return bool
     */
    public function has(string $class): bool;
}