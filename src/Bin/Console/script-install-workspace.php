<?php
namespace Phpnova\Nova\Bin\Console;

use SplFileInfo;

$app_json = [
    'timezone' => 'UTC',
    'databases' => [
        'default' => [
            'type' => 'mysql',
            'structure' => 'src/databases/default',
            'connectionData' => [
                'hostname' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => '',
                'port' => null
            ]
        ]
    ]
];

$dir = Scripts::getDir();
$dir_src = "src";
$io = Scripts::getIO();

if (file_exists("$dir/$dir_src") && count(scandir("$dir/$dir_src"))) {
    # quere decir que el direcctorio esta en uso
    $res = null;
    while (is_null($res)){
        $res = $io->ask(" - El directorio [src] esta en suso, ¿desea cargar los archivos aun asi? ( si/no ): ");
        
        if (is_null($res)) continue;

        if (is_string($res)) {
            if (strtolower($res) == "no") {
                $dir_src = $io->ask(" - ¿Por favor ingrese el nombre de directorio que desa utilizar? ( api ): ", "api");
            }
        }
    }

} 

Console::log("n: $dir_src");
# Creamos el archivo .nvapp.json
Scripts::filesAdd("$dir/.nvapp.json", "");
Scripts::filesAdd("$dir/$dir_src/app.run.php", "");
Scripts::filesAdd("$dir/$dir_src/app.router.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseModel.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseController.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseEntity.php", "");

Scripts::filesAdd("$dir/$dir_src/Databases/default/structure.sql", "-- Aqui ingrese la estructura de la base de datos por defecto");

Scripts::filesSave();