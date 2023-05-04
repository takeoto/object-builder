<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder\Contract;

use Takeoto\Rule\Contract\ClaimInterface;

interface SelfBuilderInterface
{
    /**
     * Builds the instance of current class.
     *
     * @template T of object
     * @param mixed|null $data
     * @return T
     */
    public static function build(mixed $data = null): static;

    /**
     * Gets build claims.
     *
     * @return ClaimInterface
     */
    public static function getBuildClaims(): ClaimInterface;
}
