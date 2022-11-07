<?php
namespace Phpnova\Nova\Http;

class res
{
    public static function json($body, $status = 200): Response
    {
        return new Response($body, $status, 'json');
    }

    public static function file($path, $status = 200): Response
    {
        return new Response($path, $status, 'file');
    }

    public static function html($html, $status = 200): Response
    {
        return new Response($html, $status, 'html');
    }

    public static function text($content, $status = 200): Response
    {
        return new Response($content, $status, 'text');
    }
}