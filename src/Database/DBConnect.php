<?php
namespace Phpnova\Nova\Database;

use PDO;
use Phpnova\Nova\Bin\ErrorCore;

class DBConnect
{
    public function __construct(private bool $set_dafault = true)
    {
        
    }

    private function setDefault(PDO $pdo): PDO
    {
        if ($this->set_dafault){
            $_ENV['nvx']['db']['pdo'] = $pdo;
            $_ENV['nvx']['db']['client'] = new DBClient();
            $_ENV['nvx']['db']['table'] = new DBTable();
        }
        return $pdo;
    }

    /**
     * Crea una conexión PDO a MYSQL con los parametros ingresado
     */
    public function mysql(string $username, string $password, string $database, string $hostname = 'localhost', string $port = null): PDO
    {
        try {
            return $this->setDefault(new PDO("mysql:host=$hostname; dbname=$database;" . ($port ? " port=$port;" : ''), $username, $password));
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * Crea una conexión PDO a PostgreSQL con los parametros ingresados
     */
    public function postgreSQL(string $username, string $password, string $database, string $hostname = 'localhost', $port = null): PDO
    {
        try {
            return $this->setDefault(new PDO("pgsql:host=$hostname; dbname=$database;" . ($port ? " port=$port;" : ''), $username, $password));
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * Crae una conexión PDO para Microsft Access con el parametro ingreado
     */
    public function microsftAccess(string $path)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}