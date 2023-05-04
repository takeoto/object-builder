<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder;

use Takeoto\ObjectBuilder\Contract\ObjectBuilderInterface;
use Takeoto\State\Contract\StateInterface;

/**
 * @template-covariant T of object
 * @implements ObjectBuilderInterface<T>
 */
class CustomBuilder implements ObjectBuilderInterface
{
    /**
     * @param \Closure(mixed $data):StateInterface $verifier
     * @param \Closure(mixed $data):T $builder
     */
    public function __construct(private \Closure $verifier, private \Closure $builder)
    {
    }

    /**
     * @param mixed|null $data
     * @return T
     * @throws \Throwable
     */
    public function build(mixed $data = null): object
    {
        if ($this->verify($data)->isOk()) {
            throw new \InvalidArgumentException('The value for building the object isn\'t valid.');
        }

        $object = ($this->builder)($data);

        if (!is_object($object)) {
            throw new \RuntimeException(sprintf(
                'The result of building must be an object, "%s" given.',
                gettype($object),
            ));
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function verify(mixed $data = null): StateInterface
    {
        $state = ($this->verifier)($data);

        if ($state instanceof StateInterface) {
            throw new \RuntimeException(sprintf(
                'The result of building must be an instance of "%s", "%s" given.',
                StateInterface::class,
                gettype($state),
            ));
        }

        return $state;
    }
}