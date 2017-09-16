<?php

namespace Unity\Component\Config\Matcher;

use Unity\Component\Config\Contracts\ISource;
use Unity\Support\File;

/**
 * Class SourcesMatcher.
 *
 * Matches all supported sources formats.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru|github:e200>
 */
class SourcesMatcher
{
    protected $sources = [];
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Matches sources and adds to SourcesCollection.
     *
     * @param $source mixed
     * @param $ext string
     * @param $driverAlias string
     *
     * @return ISource[]|ISource
     */
    public function match($source, $ext, $driverAlias)
    {
        $sources = $this->container->get('sourcesCollection');

        if (is_array($source)) {
            foreach ($source as $src) {
                $matchedSources = $this->_match($src, $ext, $driverAlias);

                $sources->add($matchedSources);
            }
        }

        return $sources;
    }

    public function _match($source, $ext, $driverAlias)
    {
        if (File::exists($source)) {
            return $this->container->get('FileSourceMatcher')->match($source, $ext, $driverAlias);
        } elseif (is_string($source)) {
            return $this->container->get('StringSourceMatcher')->match($source, $ext, $driverAlias);
        } else {
            /* Maybe a database :/ */
        }
    }
}
