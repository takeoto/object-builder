# object-builder
### Abstraction for object builders
#### Usage
```php

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

$buildersProvider0 = new BuilderProvider();
$buildersProvider0->register(new SomeClassBuilder(), '{{ SomeClass::class }}');

$builder = $buildersProvider0->for('{{ SomeClass::class }}');

if ($builder->verify('{{ some data }}')->isOk()) {
    $object = $builder->build('{{ some data }}');
}

$buildersProvider1 = new BuilderProvider();
$buildersProvider1->attach($buildersProvider0);
$object = $buildersProvider1->for('{{ SomeClass::class }}')->build('{{ some data }}');


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
