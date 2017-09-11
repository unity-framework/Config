<?php

namespace Unity\Component\Config\Notation;

class NotationBag
{
    /** @var $root string */
    protected $root;

    /** @var $keys array */
    protected $keys;

    /**
     * NotationBag constructor.
     *
     * @param $root
     * @param array $keys
     */
    public function __construct($root, array $keys)
    {
        $this->root = $root;
        $this->keys = $keys;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }
}
