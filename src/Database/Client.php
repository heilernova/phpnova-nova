<?php
namespace Phpnova\Nova\Database;

use Exception;
use PDO;
use PDOStatement;
use Phpnova\Nova\Bin\ErrorCore;

class Client
{
    private array $config = [];
    private readonly PDO $pdo;

    public function __construct(PDO $pdo = null)
    {
        try {
            if ($pdo) {
                $this->pdo = $pdo;
            } else {
                if (is_null($_ENV['nv']['db']['pdo'])){
                    throw new Exception("No se ha definido el PDO por default");
                }
                /** @var POD */
                $this->pdo = $_ENV['nv']['db']['pdo'];
            }

            $this->config['driver_name'] = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            if (is_string($_ENV['nv']['db']['timezone'])) {
                $this->setTimezone($_ENV['nv']['db']['timezone']);
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
                $sql = "SET TIME_ZONE = '$timezone'";
                $this->pdo->exec($sql);
            }
        } catch (\Throwable $th) {
            throw new ErrorCore(new Exception("Error al establecer la zona horaria\n Error: " . $th->getMessage() . "\nSQL: $sql"));
        }
    }
    
    public function getPDO(): PDO
    {
        return $this->pdo;
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
            $message = "# Error al ejecutar las cunsulta SQL \n\n";
            $message .= "Message: " . $th->getMessage();
            $message .= "\nSQL: $sql";
            if ($params) {
                $message .= "\nParameters: ";
                foreach ($params as $key => $val) {
                    $message .= "\n - $key : " . gettype($val) . " => $val ";
                }
            }
            throw new Exception($message, (int)$th->getCode(), $th);
        }

        return false;
    }

    /**
     * Execute an SQL Command in the database
     * @param string $sql SQL command to send
     * @param array|null $params SQL query parameters
     */
    public function execCommnad(string $sql, array $params = null): Result|false
    {
        try {
            $stmt = $this->exec($sql, $params);
            
            return $stmt ? new Result($stmt, $this->config) : false;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * Executes a data insertion in a table
     * @param array|object $values It can be an associative array or an object, where the key refers to the table field
     * @param string $table Name of the table to which data will be inserted
     */
    public function execInsert(array|object $values, string $table, string $returning = null): Result|false
    {
        try {
            $values = (array)$values;
            $fields = "";
            $values_sql = "";
            $params = [];
            foreach ($values as $key => $val) {
                $write_style = $_ENV['nv']['db']['writing_style']['queries'] ?? null;
                if ($write_style) {
                    $key = $write_style == 'snakecase' ? nv_parse_camelcase_to_snakecase($key) : nv_parse_snakecase_to_camelcase($key);
                }

                $fields .= ", `$key`";
                if (is_bool($val)) {
                    $values_sql .= ", " . ($val ? 'TRUE' : 'FALSE');
                    continue;
                }

                $params[$key] = $val;
                $values_sql .= ", :$key";
            }

            $values_sql = ltrim($values_sql, ', ');
            $fields = ltrim($fields, ', ');

            $res = $this->exec("INSERT INTO $table($fields) VALUES($values_sql)" . ($returning ? " RETURNING $returning"  : "") , $params);
            return $res ? new Result($res, $this->config) : false;
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
    public function execUpdate(array|object $values, string $condition, string $table, array $params = null): Result|false
    {
        try {
            $values = (array)$values;
            $sql_values = "";
            $sql_parms = [];

            foreach($values as $key => $val) {
                $write_style = $_ENV['nv']['db']['writing_style']['queries'] ?? null;
                if ($write_style) {
                    $key = $write_style == 'snakecase' ? nv_parse_camelcase_to_snakecase($key) : nv_parse_snakecase_to_camelcase($key);
                }

                if (is_bool($val)) {
                    $sql_values .= ", `$key` = " . ($val ? 'TRUE' : 'FALSE');
                    continue;
                }

                $sql_parms[$key] = $val;
                $sql_values .= ", `$key` = :$key";
            }

            $sql_values = ltrim($sql_values, ', ');

            # Generamos la condición.
            $sql_condition = $condition;
            $sql_condition_params = [];

            if (str_contains($condition, '?')) {
                $index = -1;
                $sql_condition = preg_replace_callback("/\?/", function($matches) use (&$index) {
                    $index++;
                    return ":$index";
                }, $condition);
            }

            $sql_condition = str_replace(':', ':pw_', $sql_condition);

            foreach($params ?? [] as $key => $val) {
                $sql_condition_params["pw_$key"] = $val;
            }
            
            $res = $this->exec("UPDATE `$table` SET $sql_values WHERE $sql_condition", array_merge($sql_parms, $sql_condition_params));

            return $res ? new Result($res, $this->config) : false;

        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    /**
     * @return int Returns the number of rows removed
     */
    public function execDelete(string $table, string $condition, array $params = null): Result|false
    {
        try {
            $res = $this->exec("DELETE FROM $table WHERE $condition", $params);
            return $res ? new Result($res, $this->config) :  false;
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