<?php
namespace Phpnova\Nova\Bin;

use Phpnova\Nova\Http\req;
use Phpnova\Nova\Http\Response;
use Phpnova\Nova\Router\Route;

class Server
{
    private callable|null $handdle = null;
    private callable|null $handdleError = null;

    public function use(mixed ...$arg): void
    {
        try {
            Route::use(...$arg);
        } catch (\Throwable $th) {
            throw new Error($th);
        }
    }

    public function handdleResponse(callable $fun): void
    {
        $this->handdle = $fun;
    }

    public function handdleException(callable $fun): void
    {
        $this->handdleError = $fun;
    }

    public function run(): never
    {
        try {
            $url = "/" . urldecode(explode( '?', trim(substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['SCRIPT_NAME']))), "/"))[0]) . "/";
            $_ENV['nv']['request']['url'] = $url;
            $_ENV['nv']['request']['method'] = $_SERVER['REQUEST_METHOD'];

            require __DIR__ . '/../Router/Functions/nv_router_run.php';
            require __DIR__ . '/../Router/Functions/searh_route.php';
            $response = nv_router_run();
        } catch (\Throwable $th) {
            if ($this->handdleError) {
                $fun = $this->handdleError;
                try {
                    $response = $fun($th);
                } catch (\Throwable $th) {
                    $msg = "Message: " . $th->getMessage();
                    $msg .= "\nFile: " . $th->getFile();
                    $msg .= "\nLine: " . $th->getLine();
                    $response = new Response($msg, 500, 'text');
                }
                if (!($response instanceof Response)) {
                    $response = new Response("La funtion HanndleException no retorn un Phpnova\Nova\Http\Response\n", 500, 'text');
                }
            }
        }

        $data = $response->getData();

        # Delete files tempo
        if (req::getMethod() != 'POST' && req::getMethod() != 'GET') {
            foreach (req::getFiles() as $file) {
                if (file_exists($file->tmpName)) {
                    unlink($file->tmpName);
                }
            }
        }
        
        header("content-type: " . $data['content-type']);
        foreach($data['headers'] as $key => $val) {
            header("$key: $val");
        }

        http_response_code($data['status']);
        echo $data['body'];
        exit;
    }
}