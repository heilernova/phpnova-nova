<?php
namespace Phpnova\Nova\Database;

use PDO;
use Phpnova\Nova\Bin\ErrorCore;

class Table
{   
    public function __construct(private ?string $table = null)
    { }
    
    private function getTable(): string
    {
        return  $this->table ? $this->table : $_ENV['nv']['db']['table-name'];
    }

    private function getClient(): Client
    {
        return $_ENV['nv']['db']['client'];
    }

    public function get(string $condition, array $params = null): ?object
    {
        try {
            $table = $this->getTable();
            return $this->getClient()->execCommnad("SELECT * FROM $table WHERE $condition LIMIT 1", $params)->rows[0] ?? null;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function getAll(string $condition = null, array $params = null): array
    {
        try {
            $table = $this->getTable();
            return $this->getClient()->execCommnad("SELECT * FROM $table" . ($condition ? " WHERE $condition" : ""), $params)->rows;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function insert(array $values, $returning = null): object|bool
    {
        try {
            $res = $this->getClient()->execInsert($values, $this->getTable(), $returning);
            if ($returning){
                return $res->rows[0];
            }

            return true;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function update(array $values, string $condition, array $params = null): bool
    {
        try {
            return $this->getClient()->execUpdate($values, $condition, $this->getTable(), $params)->rowCount > 0;
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function delete(string $condition, array $params = null): int
    {
        try {
            return $this->getClient()->execDelete($this->getTable(), $condition, $params);
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}