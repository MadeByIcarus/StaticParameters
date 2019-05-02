<?php


namespace IcarusTests;


use Icarus\StaticParameters\StaticParameters;


class CustomParameters extends StaticParameters
{

    public static function apiKey()
    {
        return self::getValueByName('apiKey');
    }
}