<?php

declare(strict_types=1);

namespace Takeoto\ObjectBuilder\Contract;

interface BuilderRegisterInterface
{
    public function register(ConfigurableBuilderProviderInterface $provider): void;
}