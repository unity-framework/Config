# Unity/Config

An extensible configuration library for your PHP projects that uses dot notation and supports json, yml, ini, php (array file) and many more formats.

## Table of contents

 - [Installation](#installation)
 - [Usage](#usage)
 - [Contribute](#contribute)
 - [Credits](#credits)
 - [License](#license)

## Installation
To install you must have [composer](https://getcomposer.org/) installed, then run:
    
    composer require unity/config

using a **terminal/prompt** in your project folder.

## Usage

Suppose you have a **configurations/database.php** file in your project folder containing the following configurations:

```php
<?php

return [
    'user' => 'root',
    'psw' => '****',
    'db' => 'test',
    'host' => 'localhost'
];
```
and you want to access these configurations. This is all you need to do:

```php
<?php

require "vendor/autoload.php";

$config = (new ConfigBuilder)
            ->setSource('configurations/')
            ->build();
```

Now, to access a configuration just:

```php
$config->get('database.user');
```
This will return: `'root'`. See how easy it is!?

For more information about the how to use this library, see the [Documentation]().

## Contributing

To contribute, please, read the [contributing](https://github.com/unity-framework/Config/blob/master/contributing.md) file.

## Credits

 - [Eleandro Duzentos](https://e200.github.com/) and contributors.
 
## License
 
The Unity Framework is licensed under the MIT license. See [License](https://github.com/unity-framework/Config/blob/master/license.md) file for more information.