<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\ConfigBuilder;
use Unity\Component\Config\Exceptions\DriverNotFoundException;

class ConfigTest extends TestCase
{
    protected $virtualFolder;
    protected $folder;
    protected $database;
    protected $arrayFiles;
    protected $arrayFolders;

    protected function setUp()
    {
        parent::setUp();

        $dir = [
            'settings.exe' => '',
            'database.php' => "<?php return ['user' => 'root'];",
        ];

        $this->virtualFolder = vfsStream::setup(
            'configs',
            444,
            $dir
        );

        $this->folder = $this->virtualFolder->url().DIRECTORY_SEPARATOR;
        $this->database = $this->folder.'database.php';
        $this->arrayFiles = [
            $this->folder.'settings.exe',
            $this->folder.'database.php',
        ];
    }

    public function testGetWithFile()
    {
        $config = (new ConfigBuilder())
            ->setSource($this->database)
            ->build();

        $this->assertEquals('root', $config->get('user'));
    }

    public function testGetWithFileWithoutExt()
    {
        $this->expectException(DriverNotFoundException::class);

        $dir = ['cache' => 'expiration=300'];

        $this->virtualFolder = vfsStream::setup(
            'configs',
            444,
            $dir
        );

        $file = $this->virtualFolder->url().DIRECTORY_SEPARATOR.'cache';

        (new ConfigBuilder())
            ->setSource($file)
            ->build();
    }

    public function testGetWithFileWithoutExtAndProvidingDriver()
    {
        $dir = ['cache' => 'expiration=300'];

        $this->virtualFolder = vfsStream::setup(
            'configs',
            444,
            $dir
        );

        $file = $this->virtualFolder->url().DIRECTORY_SEPARATOR.'cache';

        $config = (new ConfigBuilder())
            ->setSource($file)
            ->setDriver('ini')
            ->build();

        $this->assertEquals('300', $config->get('expiration'));
    }

    public function testGetWithFolder()
    {
        $config = (new ConfigBuilder())
            ->setSource($this->folder)
            ->build();

        $this->assertEquals('root', $config->get('database.user'));
    }
}
