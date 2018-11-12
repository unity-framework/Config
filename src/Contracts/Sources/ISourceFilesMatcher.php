<?php

namespace Unity\Component\Config\Contracts\Sources;

/**
 * Class ISourceFilesMatcher.
 *
 * Matches files in a source folder.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISourceFilesMatcher
{
    /**
     * Matchs source files in `$folder`.
     *
     * @param string $folder Folder containing source files.
     * @param string $driver Driver alias
     * @param string $ext    Extension for source files.
     *                       Setting `$ext`, will filter and load only files that
     *                       matches this extension.
     *
     *                    Setting the `$driver` will filter and load only files
     *                    supported by the driver associated with this `$driver`.
     *
     * @return IFileSource[]
     */
    public function match($folder, $driver, $ext);
}
