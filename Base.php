<?php
/**
 * Created by PhpStorm.
 * User: Amir Hossein Babaeian
 * Date: 1/3/16
 * Time: 1:46 PM
 */
namespace hipersia;

class Base
{

    private static $db;

    public static function getBasePath()
    {
        return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
    }

    public static function getDbLocator()
    {
        if (is_null(self::$db)) {
            $config = \Spyc::YAMLLoad(self::getBasePath() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.yml');

            $cfg = new \Spot\Config();

            $cfg->addConnection($config['name'], $config['dsn']);

            $spot = new \Spot\Locator($cfg);

            self::$db = $spot;
        }

        return self::$db;
    }
}