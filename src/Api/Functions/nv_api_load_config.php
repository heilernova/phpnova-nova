<?php

use Phpnova\Nova\Http\HttpFuns;

function nv_api_load_config(): void
{
    $dir = $_ENV['nv']['api']['dir'];

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

    $_ENV['nv']['api']['config'] = json_decode(file_get_contents($dir_config), true);

    if (json_last_error() != JSON_ERROR_NONE) throw new Exception("El contenido del env.json es erroneo");

    # Cargamos la información de la Solicitud HTTTP
    $_ENV['nvx']['request']['ip'] = HttpFuns::getIP();
    $_ENV['nvx']['request']['platform'] = HttpFuns::getPlatform();
    $_ENV['nvx']['request']['device'] = HttpFuns::getDevice();

}