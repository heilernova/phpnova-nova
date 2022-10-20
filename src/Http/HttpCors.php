<?php
namespace Phpnova\Nova\Http;

use Phpnova\Nova\Bin\ErrorCore;

class HttpCors
{

    /**
     * @param string|string[]|null $headers
     */
    public static function loadCors(string|array $origin = null, string|array $headers = null, string|array $methods = 'GET, POST, PUT, PATCH, DELETE, OPTIONS'): void 
    {
        try {
            if ($origin) header("Access-Control-Allow-Origin: " . self::mapValues($origin));
            if ($headers) header("Access-Control-Allow-Headers: " . self::mapValues($headers));
            if ($methods) header("Access-Control-Allow-Methods: " . self::mapValues($methods));

            if (isset($_SERVER['HTTP_Origin'])) {
                header("Access-Control-Allow-Origin: ". $_SERVER['HTTP_Origin']);
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                    if ($methods) header("Access-Control-Allow-Methods: $methods");
                }

                if ($headers){
                    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                        header("Access-Control-Allow-Headers: " . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
                    }
                }
                exit(0);
            }
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * @param string[]|string $values
     */
    private static function mapValues(array|string $values): string
    {
        if (is_string($values)) return $values;

        $temp = "";
        foreach ($values as $val) {
            $temp .= ", $val";
        }
        return ltrim($temp, ', ');
    }
}