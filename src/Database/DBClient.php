<?php
namespace Phpnova\Nova\Database;

use Exception;
use PDO;
use PDOStatement;
use Phpnova\Nova\Bin\ErrorCore;

class DBClient
{
    private array $config = [];
    private ?string $defaultTable = null;
    private readonly PDO $pdo;

    public function __construct(PDO $pdo = null)
    {
        try {
            if ($pdo) {
                $this->pdo = $pdo;
            } else {
                if (is_null($_ENV['nvx']['database']['pdo'])){
                    throw new Exception("No se ha definido el PDO por default");
                }
                /** @var POD */
                $this->pdo = $_ENV['nvx']['database']['pdo'];
            }

            $this->config['driver_name'] = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            if (is_string($_ENV['nvx']['database']['timezone'])) {
                $this->setTimezone($_ENV['nvx']['database']['timezone']);
            }

            if (is_string($_ENV['nvx']['database']['handles']['query']['parce-writing-style'])){
                $this->config['query_parce_writing_style'] = $_ENV['nvx']['database']['handles']['query']['parce-writing-style'];
            }

            if (is_string($_ENV['nvx']['database']['handles']['result']['parce-writing-style'])){
                $this->config['result_parce_writing_style'] = $_ENV['nvx']['database']['handles']['result']['parce-writing-style'];
            }
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * Set the time zone  for queries in the database
     * @param string $timezone Example: +00:00 = UTC
     */
    public function setTimezone(string $timezone): void
    {
        try {
            if ($this->config['driver_name'] == 'mysql') {
                $this->pdo->exec("SET TIME_ZONE = $timezone");
            }
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
    
    /**
     * Set default table name for queries
     */
    public function setTableDefault(string $name): void
    {
        $this->defaultTable = $name;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    public function getConfig()
    {

    }
    
    /** Queries SQL */


    private function exec(string $sql, array $params = null): PDOStatement|false
    {
        $stmt = $this->pdo->prepare($sql);

        if ($params){
            foreach ($params as $key => $val) {
                if (is_array($val) || is_object($val)) $params[$key] = json_encode($val);
            }
        }
        
        try {
            $stmt->execute($params);
            return $stmt;
        } catch (\Throwable $th) {
            $message = "** Error al ejecutar las cunsulta SQL ** \n\n";
            throw new ErrorCore($th);
        }

        return false;
    }

    /**
     * Execute an SQL Command in the database
     * @param string $sql SQL command to send
     * @param array|null $params SQL query parameters
     * @return DBResult|null
     */
    public function execCommnad(string $sql, array $params = null): DBResult|false
    {
        try {
            $stmt = $this->exec($sql, $params);
            return $stmt ? new DBResult($stmt, $this->config) : false;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * Executes a data insertion in a table
     * @param array|object $values It can be an associative array or an object, where the key refers to the table field
     * @param string $table Name of the table to which data will be inserted
     */
    public function execInsert(array|object $values, string $table = null): DBResult|false
    {
        try {
            $values = (array)$values;
            $fields = "";
            $values_sql = "";

            foreach ($values as $key => $val) {
                $fields .= ", `$key`";
                if (is_bool($val)) {
                    $values_sql .= ", " . ($val ? 'TRUE' : 'FALSE');
                    unset($values[$key]);
                    continue;
                }

                $values_sql .= ", :$key";
            }

            $values_sql = ltrim($values_sql, ', ');
            $fields = ltrim($fields, ', ');

            $res = $this->exec("INSERT INTO $table($fields) VALUES($values_sql)", (array)$values);
            return $res ? new DBResult($res, $this->config) : false;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * Updates the data in a row
     * @param array|object $values Object or array associative of the values tobe updated from the table
     * @param string $condition Condition to execute update without WHERE Example: "id = 1", "id = ?". do not use "WHERE id = ?"
     * @param array $params Condition parameteres
     * @param string $table 
     */
    public function execUpdate(array|object $values, string $condition, array $params = null, string $table = null)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function execDelete(string $condition, array $params = null, string $table = null)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * @param string $name Nombre de la función a ejecutar.
     * @param array|null $params Array asociativo de los parametros
     */
    public function execFunction(string $name, array $params = null)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
    /**
     * @param string $name Nombre del procedimiento a ejecutar.
     * @param array|null $params Array asociativo de los parametros
     */
    public function execProcedure(string $name, array $params = null)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}