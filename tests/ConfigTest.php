<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\ConfigBuilder;

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
            $this->folder.'settings.dll',
            $this->database,
        ];
    }

    public function testGetWithFile()
    {
        $config = (new ConfigBuilder())
            ->setSource($this->database)
            ->build();

        $this->assertEquals('root', $config->get('user'));
    }

    public function testGetWithFolder()
    {
        $config = (new ConfigBuilder())
            ->setSource($this->folder)
            ->build();

        $this->assertEquals('root', $config->get('database.user'));
    }

    /**
     * @covers Config::get()
     */
    public function testGetWithArrayFiles()
    {
        $config = (new ConfigBuilder())
                        ->setSource($this->arrayFiles)
                        ->build();

        $this->assertEquals('root', $config->get('database.user'));
    }

    /**
     * @covers Config::get()
     */
    public function testGetWithArrayFolders()
    {
        $folders = [
            'configs' => [
                'database.dll' => '',
            ],
            'settings' => [
                'database.json' => '{"user": "root"}',
            ],
        ];

        $virtualFolder = vfsStream::setup(
            'folder',
            444,
            $folders
        );

        $config = (new ConfigBuilder())
            ->setSource([
                $virtualFolder->url().DIRECTORY_SEPARATOR.'configs',
                $virtualFolder->url().DIRECTORY_SEPARATOR.'settings',
            ])
            ->build();

        $this->assertEquals('root', $config->get('database.user'));
    }
}
