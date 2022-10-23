<?php
namespace Phpnova\Nova\Bin\Console;

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

Scripts::filesAdd("$dir/.htaccess", Templates::htaccess());
Scripts::filesAdd("$dir/index.json", Templates::indexJSON());
Scripts::filesAdd("$dir/index.php", Templates::index($dir_src));
Scripts::filesAdd("$dir/env.json", Templates::getEnv());
Scripts::filesAdd("$dir/$dir_src/app.run.php", Templates::getAppRun());
Scripts::filesAdd("$dir/$dir_src/app.router.php", Templates::getAppRun());
Scripts::filesAdd("$dir/$dir_src/Bin/BaseModel.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseController.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseEntity.php", "");

Scripts::filesAdd("$dir/$dir_src/Databases/default/structure.sql", "-- Aqui ingrese la estructura de la base de datos por defecto");

Scripts::filesSave();