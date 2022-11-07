<?php
 # Buscamos el archivo autoload para obtener la ruta del directorio 

use Phpnova\Nova\Http\HttpFuns;

try {
    //code...
    foreach (get_required_files() as $file) {
        if (str_ends_with($file, 'autoload.php')) {
            $dir = dirname($file, 2);
            $_ENV['nv']['dirs']['project'] = $dir;
            $_ENV['nv']['dirs']['downlaods'] = "$dir/downloads"; # Directorio por defecto para guardar archivo
            break;
        }
    }
    
    $dir = $_ENV['nv']['dirs']['project'];
    
    $dir_config = "$dir/env.json";
    
    if (!file_exists("$dir/index.json")) throw new Exception("No se encotro el index.json");
    
    $index_config = json_decode(file_get_contents("$dir/index.json"), true);
    if (is_null($index_config)) throw new Exception("Error con el contenido del index.json");
    
    if (!file_exists($dir_config)) {
        $json['timezone'] = $index_config['databases'] ?? 'UTC';
    
        $dbs = $index_config['databases'];
    
        foreach ($dbs as $name => $db) {
            $json['databases'][$name]['type'] = $db['type'];
            $json['databases'][$name]['connectionData'] = [
                "hostname" => "localhost",
                "username" => "root",
                "password" => "",
                "database" => "test",
                "post" => null
            ];
        }
    
        $f = fopen("$dir/env.json", 'a');
        fputs($f, json_encode($json, 128));
        fclose($f);
    
        throw new Exception("Falta establecer el archivo de las condiguraciones del [env.json]");
    }
    
    $_ENV['nv']['config'] = json_decode(file_get_contents($dir_config), true);
    
    if (json_last_error() != JSON_ERROR_NONE) throw new Exception("El contenido del env.json es erroneo");
    
    # Cargamos la información de la Solicitud HTTTP
    $_ENV['nv']['request']['ip'] = HttpFuns::getIP();
    $_ENV['nv']['request']['platform'] = HttpFuns::getPlatform();
    $_ENV['nv']['request']['device'] = HttpFuns::getDevice();
} catch (\Throwable $th) {
    //throw $th;
    echo "Error al cargar la configuración: ";
    echo $th->getMessage();
    exit;
}