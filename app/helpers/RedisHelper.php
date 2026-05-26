<?php

require_once 'vendor/autoload.php';

use Predis\Client;

class RedisHelper
{
    private static $redis = null;

    public static function connect()
    {
        if (self::$redis == null)
        {
            self::$redis = new Client([
                'scheme' => 'tcp',
                'host'   => '127.0.0.1',
                'port'   => 6379,
            ]);
        }

        return self::$redis;
    }
}