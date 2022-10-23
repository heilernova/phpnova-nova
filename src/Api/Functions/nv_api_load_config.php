<?php

function nv_api_load_config(): void
{
    $dir = $_ENV['nv']['api']['dir'];

    $dir_config = "$dir/env.json";

    if (!file_exists($dir_config)) throw new Exception("Falta establecer el archivo de las condiguraciones del [config/env.json]");

    $_ENV['nv']['api']['config'] = json_decode(file_get_contents($dir_config), true);

    if (json_last_error() != JSON_ERROR_NONE) throw new Exception("El contenido del env.json es erroneo");
}