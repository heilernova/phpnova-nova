<?php
// namespace Phpnova\Nova;
/**
 * Crae el directorio
 */
function nv_create_dir(string $dir): void
{
    if (file_exists($dir)) return;

    $explode = explode("/", $dir);

    $tempo = "";
    foreach ($explode as $value) {
        $tempo .= "$value/";
        if (file_exists($tempo)){
            continue;
        }
        mkdir($tempo);
    }
}