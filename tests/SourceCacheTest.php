<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    protected $virtualFolder;

    protected function setUp()
    {
        parent::setUp();

        $dir = [
            'database.php' => "<?php return ['user' => 'root'];",
        ];

        $this->virtualFolder = vfsStream::setup(
            'configs',
            444,
            $dir
        );
    }
}
