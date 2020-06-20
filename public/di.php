<?php

use Mockery\Matcher\Contains;

class A
{
}

class B
{
    public function __construct(A $a)
    {
        
    }
}

class Container
{
    protected static $service = [];

    public static function add($name, $callback)
    {
        self::$service['B'] = $callback;
    }

    public static function get($name)
    {
        return call_user_func(self::$service[$name]);
    }
}

Container::add('B', function() {
    return new B(new A);
});

$b = Container::get('B');
$b2 = Container::get('B');

var_dump($b, $b2);