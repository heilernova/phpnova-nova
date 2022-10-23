<?php
namespace Phpnova\Nova\Bin\Console;

use Exception;
use PDO;
use Phpnova\Nova\Api\Settings\DatabaseInfo;
use Phpnova\Nova\Database\Connect;

$dir = Scripts::getDir();

# Estraemos la información del index.json

if (!file_exists("$dir/index.json")) {
    throw new Exception("No se entro el archivo index.json en la raiz del proyecto.");
}

$index_config = json_decode(file_get_contents("$dir/index.json"), true);
if (json_last_error() != JSON_ERROR_NONE) throw new Exception("Error con el formato del contenido del index.json");

if (count($index_config['databases']) == 0) throw new Exception('No hay bases de datos establecidas en el index.json');

# Extraemos en env.json
if (!file_exists("$dir/env.json")) throw new Exception("No se encotro el archivo env.json en la raiz del proyecto");

$env_config = json_decode(file_get_contents("$dir/env.json"), true);
if (json_last_error() != JSON_ERROR_NONE) throw new Exception("Error con el formato del contenido del env.json");


$db_env = $env_config['databases'];
$db_idx = $index_config['databases'];

$db_env_keys = array_keys($db_env);
$db_idx_keys = array_keys($db_idx);

if (count($db_idx_keys) == 0) throw new Exception("No hay conexión establecidad en el index.json");

$key = $db_env_keys[0];

if (count($db_env_keys) > 1) {
    # debemos definir a que base de datos queremos realizar la instalacion
    // $db_info = false;
}

$db_info = $db_idx[$key];
$db_cone = $db_env[$key] ?? null;

if (is_null($db_cone)) throw new Exception("No hay configuración de la base de datos [$key] en el env.json");

$path_structure = null;
if (array_key_exists('structure', $db_info)) $path_structure = "$dir/" . $db_info['structure'];

$info = new DatabaseInfo($db_cone, $path_structure);

try {
    $pdo = $info->getPDO();
} catch (\Throwable $th) {
    throw new Exception("# la conexión de la base de datos\nMessage: " . $th->getMessage());
}

$sql = $info->getStructure();

try {
    $scritp_list = explode(';', trim($sql, ';'));
    foreach ($scritp_list as $script) {
        $pdo->prepare($script)->execute();
    }

    echo "Script finalizado\n";
} catch (\Throwable $th) {
    throw new Exception("Error al ejecutar la instalaacion de la base de datos\n\nMessage: " . $th->getMessage() . "\n\nSQL: $script\n");
}