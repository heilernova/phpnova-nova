<?php
namespace Phpnova\Nova\Bin\Console;

class Console
{
    public static function log(string $text): void
    {
        echo $text . "\n";
    }

    public static function fileCreate(string $name): void
    {
        $size = file_exists($name) ? filesize($name) : 0;
        echo "\e[1;32mCREATE:\e[0m $name ( $size bytes )\n";
    }

    public static function fileUpdate(string $name): void
    {
        $size = file_exists($name) ? filesize($name) : 0;
        echo "\e[1;36mUPDATE:\e[0m $name ( $size bytes )\n";
    }
}