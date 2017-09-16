<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Notation\DotNotation;
use Unity\Component\Config\Notation\NotationBag;

class DotNotationTest extends TestCase
{
    /**
     * @covers DotNotation::denote()
     */
    function testDenote()
    {
        $notation = 'database.user';

        $instance = DotNotation::denote($notation);
        $this->assertInstanceOf(NotationBag::class, $instance);
    }
}