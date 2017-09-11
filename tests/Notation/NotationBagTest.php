<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Notation\NotationBag;

class NotationBagTest extends TestCase
{
    public function testGetRoot()
    {
        $root = 'database';

        $notationBag = new NotationBag($root, []);

        $this->assertEquals($root, $notationBag->getRoot());
    }

    public function testGetKeys()
    {
        $keys = ['user', 'db', 'host'];

        $notationBag = new NotationBag(null, $keys);

        $this->assertEquals($keys, $notationBag->getKeys());
    }
}
