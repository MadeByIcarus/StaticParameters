<?php


namespace Icarus\StaticParameters;


use Nette\InvalidArgumentException;


final class Parameters extends StaticParameters
{

    public static function get($name)
    {
        return self::getValueByName($name);
    }
}