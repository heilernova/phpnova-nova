<?php
namespace Phpnova\Nova;

use Phpnova\Nova\Api\ApiServer;

require_once __DIR__ . '/Bin/enviroments.php';

class apirest
{
    public static function init()
    {
        return new ApiServer();
    }

    /**
     *  Returns the direcotory where the projecto is located
     */
    public static function getDir(): string
    {
        return $_ENV['nvx']['directories']['project'] ?? '';
    }
}