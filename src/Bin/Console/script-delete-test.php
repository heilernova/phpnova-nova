<?php
namespace Phpnova\Nova\Bin\Console;

$dir = Scripts::getDir();

$files[] = ".htaccess";
$files[] = "env.json";
$files[] = "index.json";
$files[] = "index.php";

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
        Console::fileDelete($file);
    }
}

$res = nv_delete_dir("$dir/api/");

foreach($res as $file) {
    Console::fileDelete(substr($file, strlen($dir) + 1));
}