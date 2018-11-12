<?php

namespace Unity\Component\Config\Contracts\Factories;

/**
 * Interface ISourceFactory.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISourceFactory
{
    /**
     * Makes and returns an ISource instance.
     *
     * @param string $file   Our source.
     * @param string $driver The driver that will be used.
     * @param null   $ext
     *
     * @return bool|ISource
     */
    public function makeFromFile($file, $driver = null, $ext = null);

    /**
     * Makes and returns an ISource instance that represents a folder.
     *
     * @param string $folder
     * @param string $driver The driver that will be used.
     * @param string $ext
     *
     * @return ISource
     */
    public function makeFromFolder($file, $driver = null, $ext = null);
}
