<?php
// $_ENV['test'] = [];
function nv_delete_dir(string $path): array
{
    static $paths = [];
    if (is_dir($path)) {
        foreach (scandir($path) as $item) {
            if ($item == ".."  || $item == ".") continue;
    
            if (is_dir("$path/$item")) {
                nv_delete_dir("$path/$item");
                continue;
            }
            unlink("$path/$item");
            $paths[] = str_replace('//', '/',  "$path/$item");
        }
        rmdir($path);
    }

    return $paths;
}