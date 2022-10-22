<?php
namespace Phpnova\Nova\Database;

class Config
{
    public function setTimezone(string $timezonte): void
    {
        $_ENV['nv']['db']['timezone'] = $timezonte;
    }

    public function setWritingStyleQueries(string $writingStyle): void
    {
        $_ENV['nv']['db']['writing_style']['queris'] = $writingStyle;
    }

    public function setWritingStyleReults(string $writingStyle): void
    {
        $_ENV['nv']['db']['writing_style']['results'] = $writingStyle;

    }
}