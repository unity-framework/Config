<?php

use Unity\Component\Config\Drivers\File\JsonDriver;
use PHPUnit\Framework\TestCase;

class JsonDriverTest extends TestCase
{
    /**
     * @covers JsonDriver::getExt()
     *
     * `getExt()` Should return the default extension: `json`
     */
    function testJsonIsDefaultExt()
    {
        $jsonDriver = $this->getJsonDriverForTest();

        $this->assertEquals('json', $jsonDriver->getExt());
    }

    /**
     * @return JsonDriver
     */
    private function getJsonDriverForTest()
    {
        return new JsonDriver;
    }
}