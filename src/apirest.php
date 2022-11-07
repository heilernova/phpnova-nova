<?php
namespace Phpnova\Nova;

use Phpnova\Nova\Settings\Settings;
use Phpnova\Nova\Bin\Server;
use Phpnova\Nova\Http\Request;

require_once __DIR__ . '/Bin/_env.php';
require_once __DIR__ . '/Bin/Scripts/script-load-config.php';

class apirest
{
    private static Settings $config;

    public static function create(): Server
    {
        self::$config = new Settings();
        return new Server();
    }

    /**
     *  Returns the direcotory where the projecto is located
     */
    public static function getDir(): string
    {
        return $_ENV['nv']['dirs']['project'] ?? '';
    }

    public static function getConfig(): Settings
    {
        return self::$config;
    }

    /**
     * Returns an object with the information of the HTTP request
     */
    public static function getRequest(): Request
    {
        return new Request();
    }
}