<?php
namespace Phpnova\Nova\Database;

use PDO;

class DBConfig
{
    /**
     * @return DBConnect
     */
    public static function connect(): DBConnect
    {
        return new DBConnect();
    }

    /**
     * Agrag la zona horaria por defecto en la consulta SQL de la base de dtos
     */
    public static function setTimezone(string $timezone): void
    {
        $_ENV['nvx']['database']['timezone'] = $timezone;
    }

    public static function setDefaultPDO(PDO $pdo): void
    {
        $_ENV['nvx']['database']['pdo'] = $pdo;
    }

    /**
     * @param 'camelcase'|'snakecase' $type
     */
    public static function setQueryWritingStyle(string $type): void
    {
        $_ENV['nvx']['database']['handles']['query']['parce-writing-style'] = $type;
    }
 
    /**
     * @param 'camelcase'|'snakecase' $type
     */
    public static function setResultWritingStyle(string $type): void
    {
        $_ENV['nvx']['database']['handles']['result']['parce-writing-style'] = $type;
    }
}