# object-builder
### Abstraction for object builders
#### Usage

```php
use Takeoto\ObjectBuilder\Contract\BuilderRegisterInterface;
use Takeoto\ObjectBuilder\BuilderProvider;
use Takeoto\ObjectBuilder\Contract\ConfigurableBuilderProviderInterface;
use Takeoto\ObjectBuilder\Contract\ObjectBuilderInterface;
use Takeoto\ObjectBuilder\SelfBuildersProvider;
use Takeoto\Rule\Contract\ClaimInterface;
use Takeoto\Rule\Contract\RuleBuilderInterface;
use Takeoto\Rule\Utility\Claim;
use Takeoto\Rule\Verifier;
use Takeoto\State\Contract\StateInterface;
use Takeoto\State\State;
use Takeoto\ObjectBuilder\Contract\SelfBuilderInterface;
use Takeoto\Rule\Builder\RuleBuilder;

# --- Creating the `SomeClass` builder

/**
 * @implements ObjectBuilderInterface<SomeClass>
 */
class SomeClassBuilder implements ObjectBuilderInterface
{
    /**
     * @param mixed|null $data
     * @return SomeClass
     * @throws \Throwable
     */
    public function build(mixed $data = null): object
    {
        # creating object ...
        return new SomeClass();
    }

    /**
     * @inheritDoc
     */
    public function verify(mixed $data = null): StateInterface
    {
        # verifying data for creating object ...
        return new State($errors);
    }
}

# --- Basic usage with the builders provider
$buildersProvider = new BuilderProvider();
# Register builder in the builder provider
$buildersProvider->register(new SomeClassBuilder(), SomeClass::class);
# $buildersProvider->has(SomeClass::class); # true
# Getting the builder from the builder provider
$builder = $buildersProvider->for(SomeClass::class);

# Verifying data for building 
if ($builder->verify('some data')->isOk()) {
    # throw an exception or something
}

# Building object
$object = $builder->build('some data');

# --- The extendable builders provider

$buildersProvider = new BuilderProvider();
$buildersProvider->attach($buildersProvider0);
$buildersProvider->attach($buildersProvider1);
$buildersProvider->attach($buildersProvider3);

$object = $buildersProvider->for(SomeClass::class)->build('some data');

# --- The builders register

class BuildersGroup implements BuilderRegisterInterface
{
    public function register(ConfigurableBuilderProviderInterface $provider): void
    {
        $provider->register(new SomeClassBuilder(), SomeClass::class);
        $provider->register(new SomeFirstClassBuilder(), SomeFirstClass::class);
        $provider->register(new SomeSecondClassBuilder(), SomeSecondClass::class);
    }
}

$buildersProvider = new BuilderProvider();
$buildersProvider->register(new BuilderGroup());

# --- Self builder object

class SomeClass implements SelfBuilderInterface
{
    # some class logic ...

    /**
     * @template T of object
     * @param array{someArg0: int}|null $data
     * @return T
     * @throws \Throwable
     */
    public static function build(mixed $data = null): static
    {
        return new self($data['someArg0']);
    }

    /**
     * @inheritDoc
     */
    public static function getBuildClaims(): ClaimInterface
    {
        return Claim::array([
            'someArg0' => Claim::int(), 
        ]);
    }
}

$buildersProvider = new SelfBuildersProvider(new Verifier(new RuleBuilder()));

if ($buildersProvider->has(SomeClass::class)) {
    $object = $buildersProvider->for(SomeClass::class)->build('some value');
}

```
