The **ConfigBuilder** class is responsible of the configuration and building process of a [Config](https://unity-framework.github.com/Config/docs/Config/index.md) class instance.

Instead of setup your configurations directly in a **Config** instance, we decide to delegate this process to other class following the *[Single Responsibility Principle (SRP)](https://en.wikipedia.org/wiki/Single_responsibility_principle)*.

## How to configure

With this class you can setup a source or add various sources and select a [Driver](https://unity-framework.github.com/Config/docs/Drivers/index.md) for your configurations.

### Setting a source

To setup a source use the `setSource($source)` method:

```php

<?php

$source = 'https://example.com/config.json';

(new ConfigBuilder())
    ->setSource($source);
```

### Adding various sources

To add various sources use the `addSource($source)` method:

```php

<?php

$source1 = 'https://example.com/db_config.json';
$source2 = 'https://example.com/cache_config.json';

(new ConfigBuilder())
    ->addSource($source1)
    ->addSource($source2);
```