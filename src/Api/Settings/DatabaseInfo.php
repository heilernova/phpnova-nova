<?php
namespace Phpnova\Nova\Api\Settings;

use Exception;
use PDO;
use Phpnova\Nova\Bin\ErrorCore;
use Phpnova\Nova\Database\Connect;

class DatabaseInfo
{
    public readonly string $type;
    public function __construct(private array $data)
    {
        $this->type = $data['type'];
    }

    public function getPDO(): PDO 
    {
        try {
            $connect = new Connect(false);
            $d = $this->data['connectionData'];

            switch($this->data['type']) {
                case 'mysql': return $connect->mysql($d['username'], $d['password'], $d['database'], $d['hostname'], $d['port'] ?? null);
                default:
                    throw new Exception('Tipo de base de datos no soportado');
                    break;
            }
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }

    public function default(): void
    {
        try {
            // exit;
            $connect = new Connect();
            $d = $this->data['connectionData'];

            switch($this->data['type']) {
                case 'mysql':
                    $connect->mysql($d['username'], $d['password'], $d['database'], $d['hostname'], $d['port'] ?? null);
                    break;
                default:
                    throw new Exception('Tipo de base de datos no soportado');
                    break;
            }
        } catch (\Throwable $th) {
            throw new ErrorCore($th);
        }
    }
}