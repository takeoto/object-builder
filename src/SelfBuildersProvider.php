<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder;

use Takeoto\ObjectBuilder\Contract\BuilderProviderInterface;
use Takeoto\ObjectBuilder\Contract\ObjectBuilderInterface;
use Takeoto\ObjectBuilder\Contract\SelfBuilderInterface;
use Takeoto\Rule\Contract\VerifierInterface;
use Takeoto\State\Contract\StateInterface;

class SelfBuildersProvider implements BuilderProviderInterface
{
    public function __construct(
        private VerifierInterface $verifier,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function for(string $class): ObjectBuilderInterface
    {
        if (!$this->has($class)) {
            throw new \RuntimeException(sprintf('Cannot build the "%s" instance.', $class));
        }

        if (!is_subclass_of($class, SelfBuilderInterface::class)) {
            throw new \Exception('Fuck the PhpStan and autocomplete in PhpStorm!');
        }

        return new CustomBuilder(
            fn(mixed $data): StateInterface => $this->verifier->verify($data, $class::getBuildClaims()),
            fn(mixed $data): object => $class::build($data),
        );
    }

    /**
     * @inheritDoc
     */
    public function has(string $class): bool
    {
        return is_subclass_of($class, SelfBuilderInterface::class);
    }
}