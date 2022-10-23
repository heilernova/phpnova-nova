<?php
namespace Phpnova\Nova\Bin\Console;

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

# Actualizamos el gitignore
$content = file_exists("$dir/.gitignore") ? file_get_contents("$dir/.gitignore") : "";
$fopen = fopen("$dir/.gitignore", 'a');

$ignores = ['.htaccess', 'index.json', 'env.json', "/$dir/"];
$text = "";
foreach($ignores as $text) {
    if (!str_contains($content, $text)) {
        $text .= "\n$text";
    }
}

if (strlen($text) > 0) {
    fputs($fopen, "\n\n$text");
}

Scripts::filesAdd("$dir/.htaccess", Templates::htaccess());
Scripts::filesAdd("$dir/index.json", Templates::indexJSON($dir_src));
Scripts::filesAdd("$dir/index.php", Templates::index($dir_src));
Scripts::filesAdd("$dir/env.json", Templates::getEnv());
Scripts::filesAdd("$dir/$dir_src/app.run.php", Templates::getAppRun());
Scripts::filesAdd("$dir/$dir_src/app.router.php", Templates::getAppRotuer());
Scripts::filesAdd("$dir/$dir_src/Bin/BaseModel.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseController.php", "");
Scripts::filesAdd("$dir/$dir_src/Bin/BaseEntity.php", "");

Scripts::filesAdd("$dir/$dir_src/Database/structure.sql", "-- Aqui ingrese la estructura de la base de datos por defecto");
Scripts::filesAdd("$dir/$dir_src/Database/inserts.sql", "-- Aqui ingrese los datos a insertar");

Scripts::filesSave();