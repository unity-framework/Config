# Unity/Config
An extensible configuration library for your PHP projects that uses dot notation and supports json, yml, ini, php (array file) and many more formats.

## How to install
To install you must have <a href="https://getcomposer.org/">composer</a> installed, then, just run:
    
    composer require unity/config

using a **terminal/prompt** in your project folder.

## How to use

Suppose you have a **configurations/database.php** file in your project folder containing the follow configurations:

```php
<?php

return [
    user => 'root',
    psw => '****',
    db => 'test',
    host => 'localhost'
];
```
and you want to access these configurations. First you must require the composer's **vendor/autoload.php** file, then setup the configuration source (in this case is **configurations** folder) and get an instance of the **Config** class trought the **ConfigFactory**:

```php
<?php

require "vendor/autoload.php"

ConfigFactory::setSource('configurations/');

$config = ConfigFactory::make();
```
Now, to access a configurations just:

```php
$config->get('database.user');
```
Outputs: `root`

Easy?

## How it works
This library provides a way to access your configurations using **dot notation**.

**Dot notation** is a way of representing properties splited by a dot, for example: `database.user`, where the first property represents the **root** property that will be used to access the configuration source and the remaining properties are the keys that give us the way to access a configuration value.

When you say:

```php
$config->get('database.user');
```

you're really saying:

> Config, get the user value contaning in the database.php file for me.