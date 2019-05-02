<?php

declare(strict_types=1);

namespace Icarus\StaticParameters;


use Nette\InvalidArgumentException;
use Nette\StaticClass;


abstract class StaticParameters
{

    use StaticClass;

    protected static $locked = [];

    protected static $values = [];



    public static function add($name, $value)
    {
        if (self::isLocked()) {
           throw new \RuntimeException("Parameters are locked. Use config file to change the values.");
        }
        self::$values[static::class][$name] = $value;
    }



    public static function lock()
    {
        self::$locked[static::class] = true;
    }



    public static function isLocked(): bool
    {
        return self::$locked[static::class] ?? false;
    }



    protected static function getValueByName($name)
    {
        if (!isset(self::$values[static::class][$name])) {
            throw new InvalidArgumentException("No such parameter named '$name'.");
        }
        return self::$values[static::class][$name];
    }


    /**
     * @internal
     */
    public static function reset()
    {
        self::$values = [];
        self::$locked = [];
    }



    public static function all()
    {
        return self::$values[static::class];
    }
}