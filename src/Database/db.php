<?php
namespace Phpnova\Nova\Database;

use Exception;
use Phpnova\Nova\Bin\ErrorCore;

class db
{
    public static function getConfig(): Config
    {
        return new Config();
    }

    public static function connect():Connect
    {
        return new Connect();
    }

    public static function table(string $table): Table
    {
        $_ENV['nv']['db']['table-name'] = $table;
        return $_ENV['nv']['db']['table'];
    }

    public static function getClient(): Client
    {
        try {
            return $_ENV['nv']['db']['client'];
        } catch (\Throwable $th) {
            throw new ErrorCore(new Exception('No se definido la coneción por default'));
        }
    }
}