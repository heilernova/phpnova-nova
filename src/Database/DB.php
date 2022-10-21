<?php
namespace Phpnova\Nova\Database;

use Phpnova\Nova\Bin\ErrorCore;

class DB
{
    public static function beginTransaction(): void
    {
        self::client()->getPDO()->beginTransaction();
    }

    public static function commit(): void
    {
        self::client()->getPDO()->commit();
    }

    public static function table(string $name): DBTable
    {
        try {
            $_ENV['nvx']['db']['table-name'] = $name;
            return $_ENV['nvx']['db']['table'];
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public static function view(string $name){

    }

    public static function client():DBClient
    {
        try {
            return $_ENV['nnx']['database']['client'];
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}