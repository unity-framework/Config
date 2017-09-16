<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Notation\NotationBag;

class NotationBagTest extends TestCase
{
    function testGetRoot()
    {
        $root = 'database';

        $notationBag = new NotationBag($root, []);

        $this->assertEquals($root, $notationBag->getRoot());
    }

    function testGetKeys()
    {
        $keys = ['user', 'db', 'host'];

        $notationBag = new NotationBag(null, $keys);

        $this->assertEquals($keys, $notationBag->getKeys());
    }
}