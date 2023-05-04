<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder\Contract;

use Takeoto\Rule\Contract\RuleInterface;
use Takeoto\State\Contract\StateInterface;

/**
 * @template-covariant T of object
 */
interface ObjectBuilderInterface extends RuleInterface
{
    /**
     * @param mixed|null $data
     * @return T
     * @throws \Throwable
     */
    public function build(mixed $data = null): object;

    /**
     * @inheritDoc
     */
    public function verify(mixed $data = null): StateInterface;
}