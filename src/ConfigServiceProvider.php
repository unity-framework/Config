<?php

namespace Unity\Component\Config;

use Unity\Contracts\Container\IContainer;
use Unity\Component\Config\Drivers\IniDriver;
use Unity\Component\Config\Drivers\PhpDriver;
use Unity\Component\Config\Drivers\XmlDriver;
use Unity\Component\Config\Sources\SourceFile;
use Unity\Component\Config\Drivers\JsonDriver;
use Unity\Component\Config\Drivers\YamlDriver;
use Unity\Contracts\Container\IServiceProvider;
use Unity\Component\Config\Sources\SourceFolder;
use Unity\Component\Config\Factories\DriverFactory;
use Unity\Component\Config\Factories\SourceFactory;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Component\Config\Sources\SourceCache;
use Unity\Component\Config\Sources\SourceFilesMatcher;
use Unity\Contracts\Config\Sources\ISourceFilesMatcher;

/**
 * Class ConfigServiceProvider.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class ConfigServiceProvider implements IServiceProvider
{
    public function register(IContainer $container)
    {
        $container->set('sourceFactory', SourceFactory::class)
            ->bind(IDriverFactory::class, function ($container) {
                return $container->get('driverFactory');
            })
            ->bind(IContainer::class, function ($container) {
                return $container;
            });

        $container->set('loader', Loader::class)
            ->bind(ISourceFactory::class, function ($container) {
                return $container->get('sourceFactory');
            });

        $container->set('config', Config::class);

        $container->set('driverFactory', DriverFactory::class);

        $container->set('sourceFile', SourceFile::class);

        $container->set('sourceFilesMatcher', SourceFilesMatcher::class)
            ->bind(IDriverFactory::class, function ($container) {
                return $container->get('driverFactory');
            })
            ->bind(ISourceFactory::class, function ($container) {
                return $container->get('sourceFactory');
            });

        $container->set('sourceFolder', SourceFolder::class)
            ->bind(ISourceFilesMatcher::class, function ($container) {
                return $container->get('sourceFilesMatcher');
            });

        $container->set('sourceCache', SourceCache::class);

        $container->set('php', PhpDriver::class);
        $container->set('ini', IniDriver::class);
        $container->set('json', JsonDriver::class);
        $container->set('yml', YamlDriver::class);
        $container->set('xml', XmlDriver::class);
    }
}
