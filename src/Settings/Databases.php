<?php
namespace Phpnova\Nova\Settings;

use Exception;
use Phpnova\Nova\Bin\ErrorCore;

class Databases
{
    /**
     * @return DatabaseInfo[]
     */
    public function getList()
    {
        return array_map(fn($item) => new DatabaseInfo($item), $_ENV['nv']['config']['databases']);
    }

    public function getDefault(): DatabaseInfo
    {
        try {

            $default = array_key_first($_ENV['nv']['config']['databases']);
            if (is_null($default))  throw new Exception("No hay base de datos por default");
            
            return new DatabaseInfo($_ENV['nv']['config']['databases'][$default]);
            
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function get(string $name): DatabaseInfo
    {
        try {
            if (!array_key_exists($name,$_ENV['nv']['config']['databases'])) {
                throw new Exception("Datos de l abase de datos");
            }

            return new DatabaseInfo($_ENV['nv']['config']['databases'][$name]);
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}