<?php

use Phpnova\Nova\Http\HttpRequest;
use Phpnova\Nova\Http\HttpResponse;

/**
 * Retorna la ruta que hace referencia al recurso buscaod por el usuario
 */
function nv_router_searh_route(string $url, string $http_method, string $url_parent = ''): array|HttpResponse|null {

    # Cargamos las rutas a variable
    $routes = $_ENV['nvx']['router']['routes'] ?? [];
    
    foreach ($routes as $value) {
        if (is_array($value)){

            # Definimos la expresión regular para buscar la ruta
            $patters = "/" . str_replace(':p', '(.+)', str_replace('/', '\/', $value['key']) ) . "/i";
            $match_result = preg_match($patters, $url);
            if ($match_result != false || $value['key'] == "/") {
                
                if ($value['type'] == 'router') {
                    # Si es un ruter cargamos las nueva rutas.
                    $_ENV['nvx']['router']['routes'] = [];
                    $value['fun'](); # Ejeuctamos la función

                    $num_delete = substr_count($value['key'], "/");
                    $url_explode = explode("/", $url);

                    $url_new = "";

                    if (is_int($match_result) && $value['key'] != "/") {
                        for ($i = $num_delete; $i < count($url_explode) - 1; $i++) {
                            $url_new .= "/" . trim($url_explode[$i] ?? "");
                        }
                        $url_new .= "/";
                    } else {
                        $url_new = $url;
                    }

                    $result = nv_router_searh_route($url_new, $http_method, rtrim($url_parent, '/') . '/' . ltrim($value['path'], '/'));

                    if (!is_null($result)) return $result;
                } else {
                    # En caso de que sea una ruta

                    if (substr_count($url, '/') == substr_count($value['key'], '/') && $value['method'] == $http_method) {
                        $value['url'] = $url_parent  . ltrim($value['path'], '/');
                        return $value;
                    }

                    if ($value['key'] == '/' && $url == "//" && $value['method'] == $http_method) {
                        return $value;
                    }
                }
            }
        } else {
            # Middleware
            $result = $value(new HttpRequest());
            if (!is_null($result)) {
                return $result instanceof HttpResponse ? $result : new HttpResponse($result);
            }
        }
    }
    return null;
}