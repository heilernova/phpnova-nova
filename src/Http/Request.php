<?php
namespace Phpnova\Nova\Http;

class Request
{
    public static function getURL(): string
    {
        return $_ENV['nvx']['request']['url'];
    }

    public static function getHeaders(): array
    {
        return apache_request_headers();
    }

    /**
     * Retorna el tipo de contenido de la petición HTTP. Ejemplo: text/html, application/json ...
     */
    public static function getContentType(): string
    {
        return apache_response_headers()['Content-Type'];
    }

    /**
     * Retorna el metodo de la petición HTTP
     */
    public static function getMethod(): string
    {
        return $_ENV['nvx']['request']['method'];
    }

    /**
     * Devuelve el contenido de la petición HTTP
     */
    public static function getBody(): mixed
    {
        return $_ENV['nvx']['request']['body'];
    }

    /**
     * @return \Phpnova\Nova\Http\HttpFile[];
     */
    public static function getFiles(): array
    {
        return $_ENV['nvx']['request']['files'];
    }

    /**
     * Devuelve un array asociativo de los parametro asignados en la URL mediante el ruter
     */
    public static function getParams(): array
    {
        return $_ENV['nvx']['request']['params'];
    }

    /**
     * Devuelve una array asociativo con los parametro enviado por la URL ejemplo: test?param=1
     */
    public static function getParamsURL(): array
    {
        return $_ENV['nvx']['request']['query-params'];
    }

    /**
     * Devuelve el tipo de disponsitio que realiza la petición HTTP
     */
    public static function getDevice(): string
    {
        return $_ENV['nvx']['request']['device'];
    }

    /**
     * Devuelve la plataforma o sistema operativo desde donde se realiza la petición HTTP
     */
    public static function getPlatform(): string
    {
        return $_ENV['nvx']['request']['platform'];
    }

    /**
     * Devuelve la IP de donde se raliza la petición HTTP
     */
    public static function getIP(): string
    {
        return $_ENV['nvx']['request']['ip'];
    }
}