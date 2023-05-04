<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder\Contract;

interface ExtendableBuilderProviderInterface extends BuilderProviderInterface
{
    public function attach(BuilderProviderInterface $provider): void;
}