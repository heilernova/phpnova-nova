<?php
namespace Phpnova\Nova\Http;

class Response
{
    public static function json($body, $status = 200): HttpResponse
    {
        return new HttpResponse($body, $status, 'json');
    }

    public static function file($path, $status = 200): HttpResponse
    {
        return new HttpResponse($path, $status, 'file');
    }

    public static function html($html, $status = 200): HttpResponse
    {
        return new HttpResponse($html, $status, 'html');
    }

    public static function text($content, $status = 200): HttpResponse
    {
        return new HttpResponse($content, $status, 'text');
    }
}