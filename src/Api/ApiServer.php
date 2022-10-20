<?php
namespace Phpnova\Nova\Api;

use Exception;
use Phpnova\Nova\Bin\ErrorCore;
use Phpnova\Nova\Http\HttpFile;
use Phpnova\Nova\Http\HttpResponse;
use Phpnova\Nova\Http\Request;
use Phpnova\Nova\Router\Route;

class ApiServer
{
    public function __construct()
    {
        # Buscamos el archivo autoload para obtener la ruta del directorio 
        foreach (get_required_files() as $file) {
            if (str_ends_with($file, 'autoload.php')) {
                $dir = dirname($file, 2);
                $_ENV['nvx']['directories']['project'] = $dir;
                $_ENV['nvx']['directories']['files'] = "$dir/files"; # Directorio por defecto para guardar archivo
                break;
            }
        }
    }

    public function use(mixed ...$arg): void
    {
        try {
            
            Route::use(...$arg);
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function handleError(callable $function): void
    {
        $_ENV['nvx']['handles']['error'] = $function;
    }

    public function handleResponse(callable $function): void
    {
        $_ENV['nvx']['handles']['response'] = $function;
    }

    public function getSetting(){
        
    }

    public function run(): never
    {
        try {
            $url = "/" . urldecode(explode( '?', trim(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), "/"))[0]) . "/";
            $_ENV['nvx']['request']['url'] = $url;
            $_ENV['nvx']['request']['method'] = $_SERVER['REQUEST_METHOD'];

            require __DIR__ . '/../Router/Functions/searh_route.php';
            $route = nv_router_searh_route($url, $_SERVER['REQUEST_METHOD'], '');

            if ($route) {
                # Cargamos los parametros de la ruta.
        
                $url_explode = explode('/', $url);
                $key_explode = explode('/', $route['url'] ?? '');

                foreach ($key_explode as $key => $val) {
                    if (str_starts_with($val, ':')) {
        
                        $_ENV['nvx']['request']['params'][ltrim($val, ':')] = $url_explode[$key];
                    }
                }
            
                # Mapeamos el contenido
                switch(explode(';', apache_request_headers()['Content-Type'] ?? '')[0]){
                    case "application/json": 
                        $_ENV['nvx']['request']['body'] = json_decode(file_get_contents("php://input"));
                        if (json_last_error() != JSON_ERROR_NONE) {
                            throw new Exception("El formato del contenido es erroneo: " . json_last_error_msg());
                        }
                        break;
                    case "multipart/form-data":
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_METHOD'] != 'GET'){
                            $_ENV['nvx']['request']['body'] = array_map(array: $_POST, callback: function($item){
                                if (nv_is_json_structure($item)){
                                    $json = json_decode($item);
                                    return json_last_error() == JSON_ERROR_NONE ? $json : $item;
                                }
                                return $item;
                            });


                            $_ENV['nvx']['request']['files'] = array_map(array: $_FILES, callback: fn($data) => new HttpFile($data));
                            break;
                        }

                        require __DIR__ . '/../Http/Scripts/script-http-parce-body.php';
                        break;
                    default: break;
                }

                # Cargamos los datos del body
                $res = $route['fun']();

                if (!($res instanceof HttpResponse)){
                    $response = new HttpResponse($res);
                }

            }else{
                $response = new HttpResponse('Not found', 404, 'json');
            }
        } catch (\Throwable $th) {

            $message = "** Error **\n";
            $message .= "" . $th->getMessage() . "\n";
            $message .= "\nFile: ". $th->getFile();
            $message .= "\nLine: " . $th->getLine();
        

            $response = new HttpResponse($message, type: 'text');
        }

        $data = $response->getData();

        # Delete files tempo
        if (Request::getMethod() != 'POST' && Request::getMethod() != 'GET') {
            foreach (Request::getFiles() as $file) {
                if (file_exists($file->tmpName)) {
                    unlink($file->tmpName);
                }
            }
        }
        
        header("content-type: " . $data['content-type']);
        foreach($data['headers'] as $key => $val) {
            header("$key: $val");
        }

        echo $data['body'];
        http_response_code($data['status']);
        exit;
    }
}