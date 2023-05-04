<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder;

use Takeoto\ObjectBuilder\Contract\BuilderProviderInterface;
use Takeoto\ObjectBuilder\Contract\ObjectBuilderInterface;
use Takeoto\ObjectBuilder\Contract\SelfBuilderInterface;
use Takeoto\ObjectBuilder\Rule\Contract\RuleStateInterface;
use Takeoto\ObjectBuilder\Utility\Ensure;
use Takeoto\ObjectBuilder\Validator\ValidatorInterface;

class SelfBuildersProvider implements BuilderProviderInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function for(string $class): ObjectBuilderInterface
    {
        Ensure::true($this->has($class), sprintf('Cannot build the "%s" instance.', $class));

        if (!is_subclass_of($class, SelfBuilderInterface::class)) {
            throw new \Exception('Fuck the PhpStan and autocomplete in PhpStorm!');
        }

        return new CustomBuilder(
            fn(mixed $data): RuleStateInterface => $this->validator->validate($data, $class::getBuildClaims()),
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