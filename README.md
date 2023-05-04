# object-builder
### Abstraction for object builders
#### Usage
```php

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

$buildersProvider2 = new BuilderProvider();
$buildersProvider2->register(new BuilderGroup());
```
