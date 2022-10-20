<?php
namespace Phpnova\Nova\Bin\Console;

class Templates
{
    public static function getAppRun(): string
    {
        return file_get_contents(__DIR__ . '/Templates/app.run.php');
    }
}