<?php
namespace Phpnova\Nova\Database;

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
}