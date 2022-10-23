<?php
namespace Phpnova\Nova;

use Phpnova\Nova\Api\{ApiServer, Settings};

require_once __DIR__ . '/Bin/enviroments.php';

class apirest
{
    private static Settings $config;
    public static function init(): ApiServer
    {
        self::$config = new Settings();
        return new ApiServer();
    }

    /**
     *  Returns the direcotory where the projecto is located
     */
    public static function getDir(): string
    {
        return $_ENV['nvx']['directories']['project'] ?? '';
    }

    public static function getConfig(): Settings
    {
        return self::$config;
    }
}