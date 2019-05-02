<?php


namespace IcarusTests\StaticParameters;


use Icarus\StaticParameters\StaticParameters;


class CustomParameters extends StaticParameters
{

    public static function apiKey()
    {
        return self::getValueByName('apiKey');
    }



    public static function someNull()
    {
        return self::getValueByName('someNull');
    }
}