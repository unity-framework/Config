# Unity/Config

A decoupled and extensible configurations manager for PHP projects. Fast, progressive and easy to use.

## Table of contents

- [Installation](#installation)
- [Usage](#usage)
  - [Setting a source](#setting-a-source)
  - [Setting an extension](#setting-an-extension)
  - [Setting a driver](#setting-a-driver)
  - [Allowing modifications](#allowing-modifications)
  - [Using the cache](#using-the-cache)
  - [Managing configs](#managing-configs)
    - [Getting](#getting)
    - [Setting](#setting)
    - [Unsetting](#getting)
    - [Counting](#counting)
- [Drivers](#drivers)
  - [PHP](#php)
  - [INI](#ini)
  - [XML](#xml)
  - [YAML](#yaml)
  - [jSON](#json)


## Installation

    composer require unity/config

## Usage

Use the **ConfigManager** class to setup our configs manager.

The **ConfigManager** provides a `build()` method that returns a **Config** instance.

We will use the **Config** instance to manage our configs.

Before we get a **Config** instance we must set at least one config source, or we'll get an **InvalidSourceException**.

The **ConfigManager** class provides the following methods:

- setSource($source)
- setExt($extension)
- setDriver($alias)
- allowModifications($enabled)
- setupCache($cachePath, $expTime)

### Setting a source

We can set a source using the `ConfigManager::setSource()` method.

A source can be either a path to a config file or folder.

```php
$config = (new ConfigManager())
  ->setSource('./configs/db.ini')
  ->build();
```

### Setting an extension

Use the `ConfigManager::setExt()` method to set the source extension.

Useful when dealing with config files that hasn't an extension.

Once is set, the **ConfigManager** will skip the auto driver detection and use the driver that supports the provided extension.

```php
$config = (new ConfigManager())
  ->setSource('./configs/db')
  ->setExt('json')
  ->build();
```

### Setting a driver

Use the `ConfigManager::setDriver()` method to set the source driver.

Useful when dealing with config files that hasn't an extension.

Once is set, the **ConfigManager** will skip the auto driver detection and use the driver that owns the provided alias.

```php
$config = (new ConfigManager())
  ->setSource('./configs/db')
  ->setDriver('yml')
  ->build();
```

### Allowing modifications

We can also change configs at runtime, these changes are not reflected to their source.

To use this feature we must enable it using the `ConfigManager::allowModifications()` method.

Changes to configs when this feature is disabled will result in a **RuntimeModificationException**.

```php
$config = (new ConfigManager())
  ->setSource('./configs/db.ini')
  ->allowModifications(true)
  ->build();
```

### Using the cache

Every time we request configs, these configs needs to be parsed first, some config files are very large and parse them on every request can take a lot of time, sometimes these config files take a lot of time to be changed, so, there's no need to parse these files over and over again.

To solve this problem, we provide a simple cache system that, if enabled, caches every parsed configs on the first request, and returns the cached configs on subsequent requests.

This cache system also provides a **source changes tracker** that informs the cache for updates in the source, once a source is changed (updated, modified) the cached content is also updated.

Use the `ConfigManager::setupCache()` method to enable and setup the cache.

To enable the cache just pass the path to the folder where cached files will be stored.

```php
$config = (new ConfigManager())
  ->setSource('./configs/db')
  ->setupCache('./cache_storage')
  ->build();
```

By default the cache system stores cached files forever.

To provide an expiration time we need to pass a second argument.

```php
$expTime = '6 months';

$config = (new ConfigManager())
  ->setSource('./configs/db')
  ->setupCache('./cache', $expTime)
  ->build();
```

Once we already know how to setup the **ConfigManager**, its time to start managing our configs.

### Managing configs

The **Config** class instance provides 2 ways to manage configs, using **dot notation** or **array access**.

Consider the following folder structure:

    root
    |- index.php
    \- core
     |- configs
     | |- db.php
     | |- cache.php
       \- email.php

where each config file returns an array containing configs data, lets set the source:

```php
$config = (new ConfigManager())
  ->setSource('./core/configs')
  ->allowModifications(true)
  ->build();
```

#### Getting

We can get our database **name** on **db.php**:

```php
// Using dot notation
$config->get('db.name');

// Using array access
$config['db']['name'];
```

We can also get all configs using the `Config::getAll()` method.

#### Setting

Since we have [allowed modifications](#allowing-modifications) we can set our database **name** at runtime:

```php
// Using dot notation
$config->set('db.name', 'users');

// Using array access
$config['db']['name'] = 'users';
```

#### Unsetting

Unsettting a config at runtime:

```php
// Using dot notation
$config->unset('db.name');

// Using array access
unset($config['db']['name']);
```

#### Counting

Count the number of configs using the default PHP `count()` function:

```php
count($config);
```

Note that this function will return the number of configs couting from the top most config array to the mosts inners.