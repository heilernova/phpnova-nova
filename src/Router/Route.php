<?php
namespace Phpnova\Nova\Router;

use Exception;
use Phpnova\Nova\Bin\ErrorCore;

class Route
{
    private static function _register(string $type,  callable $acction, string $path = null, string $method = null): void
    {
        try {

            if (is_string($path)){
                $path = "/" . trim($path, "/") . "/";
                $path = str_replace('//', '/', $path);

                $patterns[] = "/(:\w+)/i";
                $replacements[] = ':p';
                $path_key = preg_replace($patterns, $replacements, $path);
            }

            switch($type){
                case 'middleware':
                    $_ENV['nv']['router']['routes'][] = $acction;
                    break;

                case 'router':
                    $_ENV['nv']['router']['routes'][] = [
                        'key'  => $path_key,
                        'type' => 'router',
                        'path' => $path,
                        'fun'  =>  $acction,
                    ];
                    break;
                
                default: 
                    $_ENV['nv']['router']['routes'][] = [
                        'key'  => $path_key,
                        'type' => 'route',
                        'method' => $method,
                        'path' => $path,
                        'fun'  => $acction,
                    ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function use(mixed ...$args): void
    {
        try {
            $num_args = count($args);
            if ($num_args == 1){
                if (!is_callable($args[0])) {
                    throw new Exception("El parametro debe ser una función [callable]");
                }
                self::_register('middleware', $args[0]);
            } elseif ($num_args == 2) {
                if (!is_string($args[0])) {
                    throw new Exception("El primer parametro debe ser un string de la ruta");
                }
                if (!is_callable($args[1])) {
                    throw new Exception("El segundo parametro de ser una función [callable]");
                }
                
                self::_register(
                    type: 'router',
                    path: $args[0],
                    acction: $args[1]
                );
            } else {
                throw new Exception("Las función solo permite maximo 2 argumentos");
            }            
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public static function get(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'GET',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public static function post(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'POST',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public static function put(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'PUT',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public static function patch(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'PATCH',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public static function delete(string $path, callable $action): void
    {
        try {
            self::_register(
                type: 'route',
                path: $path,
                method: 'DELETE',
                acction: $action
            );
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}