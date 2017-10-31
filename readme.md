# Unity/Config

An extensible configuration manager for PHP projects.

**Get started managing your configurations.**

- [Documentation](https://github.com/unity-framework/Config/blob/master/docs/documentation.md)
- [Examples](https://github.com/unity-framework/Config/blob/master/docs/examples.md)

## Features

- Array access
- Dot notation access
- Configurations cache
- Auto driver detection
- Runtime modification

## Supported drivers

- [INI](#ini)
- [PHP](#php)
- [XML](#xml)
- [YAML](#yaml)
- [jSON](#json)

## Installation

    composer require unity/config

## Usage

You have the follow configuration file: **configs/db.php** in your project folder containing the bellow configurations:

```php
<?php

return [
    'user' => 'root',
    'psw'  => 'toor',
    'db'   => 'example',
    'host' => 'localhost'
];
```

and you want to manage these configurations, thats what you need to do:

```php
<?php

require "vendor/autoload.php";

$config = (new ConfigManager())
            ->setSource('configs')
            ->build();
```

Now, to access a configuration you can use the `$config->get()`, e.g.:

```php
echo $config->get('db.user');
```

Or in a more simple way, using array access:

```php
echo $config['db']['user'];
```

Both methods will have the same output:

```php
root
```

Ask your self, is it easy???

## Contributing

We will be really thankful if you make a fork, make your changes and send a pull request!

## Credits

- [Eleandro Duzentos](https://github.com/e200/) and [contributors](#).

## License

The Unity/Config is licensed under the MIT license. See [license](https://github.com/unity-framework/Config/blob/master/license.md) file for more information.
