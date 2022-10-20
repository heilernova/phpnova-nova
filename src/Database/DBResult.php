<?php
namespace Phpnova\Nova\Database;

use PDO;
use PDOStatement;

require_once __DIR__ . '/Functions/db_parce_result.php';
use function Phpnova\Nova\is_json_structure;

class DBResult
{
    public readonly int $rowCount;
    public readonly array $rows;
    public readonly array $fields;

    public function __construct(PDOStatement $stmt, array $config)
    {
        $this->rowCount = $stmt->rowCount();

        $this->rows = $stmt->fetchAll(PDO::FETCH_FUNC, function() use ($stmt, $config) {
            
            // $values = func_get_args();
            return db_parce_result(func_get_args(), $stmt, $config);
            $params = [];

           

            return (object)$params;
        });
    }
}