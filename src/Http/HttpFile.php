<?php
namespace Phpnova\Nova\Http;

use SplFileInfo;

class HttpFile
{
    public readonly string $name;
    public readonly string $type;
    public readonly string $tmpName;
    public readonly int $size;
    public readonly mixed $error;
    public function __construct(
        array $data
    ){
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->tmpName = $data['tmp_name'];
        $this->error = $data['error'];
        $this->size = $data['size'];
    }

    /**
     * Guarda el archivo en base al directorio raiz del proyecto.
     * Si el nombre termina le barra inclinada lo tomara como el directorio donde se alojara
     * y el nombre sera el que trae por defecto.
     * @note En caso de cambiar el nombre por defecto no es necesario definir la extencion del archivo
     */
    public function save(string $name = null): bool
    {
        $dir = $_ENV['nvx']['directories']['files']; # Directoprio de archivos
        $file_name = $this->name;
        $file_info = new SplFileInfo($file_name);
        $file_extencion = strtolower($file_info->getExtension());

        if ($name && strlen($name) > 0){

            # Si es un direcotrio el nombre
            if (str_ends_with($name, '/')) {
                $dir .= "/" . trim($name, '/');
            } else {
                # Es el nuevo nombre del archivo
                $dirname = dirname($name);
                $basename = basename($name);
                if ($dirname != ".") $dir .= "/$dirname";

                $file_name = $basename;
                $pre = "/\.$file_extencion$/i";

                if (preg_match($pre, $file_name)) {
                    $file_name = preg_replace($pre, ".$file_extencion", $file_name);
                } else {
                    $file_name .= "." . $file_extencion;
                }
            }
        } else {
            $dir .= "/uploads";
        }


        nv_create_dir($dir);
        $full_name = "$dir/$file_name";
        if (request::getMethod() == "POST" || request::getMethod() == "GET") {
            return move_uploaded_file($this->tmpName, $full_name);
        } else {
            return rename($this->tmpName, $full_name);
        }

        return false;
    }
}