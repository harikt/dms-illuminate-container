<?php

namespace Dms\Ioc\Tests;

class Example
{
    protected $foo;

    public function __construct($foo = 'Foo')
    {
        $this->foo = $foo;
    }

    public function getFoo()
    {
        return $this->foo;
    }
}