<?php
/**
 * Created by PhpStorm.
 * User: Amir Hossein Babaeian
 * Date: 1/3/16
 * Time: 1:46 PM
 */
namespace hipersia;

class Base {

    /** @var  $db Locator*/
    private static $db;

    protected function __construct($name, $dsn)
    {
        $cfg = new \Spot\Config();

        $cfg->addConnection($name, $dsn);

        $spot = new \Spot\Locator($cfg);
        $this->db = $spot;
    }

    public static function getDbLocator()
    {
        $config = Spyc::YAMLLoad('../../config/config.yml');

        if (null === static::$db) {
            static::$db = new static($config['name'], $config['dsn']);
        }

        return static::$db;
    }
}