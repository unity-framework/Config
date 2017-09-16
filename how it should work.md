##### With a file

```php
$config = (new ConfigBuilder)
                ->setSource('database.php')
                ->build();
                
/**
/* Get "user" value from "database.php" file.
 */
$config->get('user');
```

##### With an array of files

```php
$config = (new ConfigBuilder)
                ->setSource(['configs.php', 'configs.json'])
                ->build();
                
/**
/* Get "user" value from "configs.php|configs.json" file
 */
$config->get('user');
```

##### With a folder

```php
$config = (new ConfigBuilder)
                ->setSource('configs')
                ->build();
                
/**
/* Get "user" value from "database.*"
/* file located in "configs" folder.
 */
$config->get('database.user');
```

##### With an array of folders

```php
$config = (new ConfigBuilder)
                ->setSource(['configs', 'configurations'])
                ->build();
                
/**
/* Get "user" value from "database.*" file located in "configs|configurations" folder
 */
$config->get('database.user');
```

##### With explicit driver

```php
$config = (new ConfigBuilder)
                ->setSource(['configs', 'settings'])
                ->setDriver('ini')
                ->build();
                
/**
/* Get "user" value from "database.*" file located in "configs|configurations" folder
 */
$config->get('database.user');
```

##### With explicit extension

```php
$config = (new ConfigBuilder)
                ->setSource('settings.json')
                ->setDriver('json')
                ->build();
                
/**
/* Get "cache" value from "settings.json" file
 */
$config->get('cache');
```