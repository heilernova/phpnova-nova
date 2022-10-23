<?php
namespace Phpnova\Nova\Api\Settings;

use Exception;
use Phpnova\Nova\Bin\ErrorCore;

class Databases
{
    /**
     * @return DatabaseInfo[]
     */
    public function getList()
    {
        // return [];
        // return $_ENV['nv']['api']['config']['databases'] ?? [];
        return array_map(fn($item) => new DatabaseInfo($item), $_ENV['nv']['api']['config']['databases']);
    }

    public function getDefault(): DatabaseInfo
    {
        try {

            $default = array_key_first($_ENV['nv']['api']['config']['databases']);
            if (is_null($default))  throw new Exception("No hay base de datos por default");
            
            return new DatabaseInfo($_ENV['nv']['api']['config']['databases'][$default]);
            
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function get(string $name): DatabaseInfo
    {
        try {
            if (!array_key_exists($name,$_ENV['nv']['api']['config']['databases'])) {
                throw new Exception("Datos de l abase de datos");
            }

            return new DatabaseInfo($_ENV['nv']['api']['config']['databases'][$name]);
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}