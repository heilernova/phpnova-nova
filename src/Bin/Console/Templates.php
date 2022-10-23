<?php
namespace Phpnova\Nova\Bin\Console;

class Templates
{
    public static function getAppRun(): string
    {
        return file_get_contents(__DIR__ . '/Templates/app.run.txt');
    }

    public static function getAppRotuer(): string
    {
        return file_get_contents(__DIR__ . '/Templates/app.router.txt');
    }

    public static function getEnv(): string
    {
        return file_get_contents(__DIR__ . '/Templates/env.json.txt');
    }

    public static function index(string $dir): string
    {
        return str_replace('$dir', $dir, file_get_contents(__DIR__ . '/Templates/index.txt'));
    }
    public static function indexJSON(string $dir): string
    {
        return str_replace('$dir', $dir, file_get_contents(__DIR__ . '/Templates/index.json.txt'));
    }

    public static function htaccess(): string
    {
        return "RewriteEngine On\nRewriteRule ^(.*) index.php [L,QSA]";
    }
}