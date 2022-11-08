<?php

use Phpnova\Nova\Http\HttpFile;
use Phpnova\Nova\Http\Request;
use Phpnova\Nova\Http\Response;

function nv_router_run(): Response
{
    $url = $_ENV['nv']['request']['url'];
    $method = $_ENV['nv']['request']['method'];
    // echo $method ; exit;

    $route = nv_router_searh_route($url, $method, '');

    if ($route instanceof Response) return $route;

    if (is_null($route)) return new Response(null, 404);

    $url_explode = explode('/', $url);
    $key_explode = explode('/', $route['url'] ?? '');

    foreach ($key_explode as $key => $val) {
        if (str_starts_with($val, ':')) {
            $_ENV['nv']['request']['params'][ltrim($val, ':')] = $url_explode[$key];
        }
    }

    # Mapeamos el contenido
    $conent_type = apache_request_headers()['content-type'] ?? (apache_request_headers()['Content-Type'] ?? null);
    switch(explode(';', $conent_type ?? '')[0]){
        case "application/json":
            $body_conent = file_get_contents("php://input");
            if ($body_conent == '') break;

            $_ENV['nv']['request']['body'] = json_decode($body_conent);
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new Exception("El contendio JSON enviado en el boyd tiene un error : " . json_last_error_msg());
            }
            break;
        case "multipart/form-data":
            if ($method == 'POST' && $method != 'GET'){
                $_ENV['nv']['request']['body'] = array_map(array: $_POST, callback: function($item){
                    if (nv_is_json_structure($item)){
                        $json = json_decode($item);
                        return json_last_error() == JSON_ERROR_NONE ? $json : $item;
                    }
                    return $item;
                });


                break;
            }
            
            require __DIR__ . '/../../Http/Scripts/script-http-parce-body.php';
            break;
        default: break;
    }

    
    # Cargamos los archivos
    $_ENV['nv']['request']['files'] = array_map(array: $_FILES, callback: fn($data) => new HttpFile($data));
    # Cargamos los datos del body
    $response = $route['fun'](new Request());

    if (!($response instanceof Response)){
        $response = new Response($response);
    }
    return $response;
}