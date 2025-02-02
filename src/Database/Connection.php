<?php

namespace ZborovoSK\ZBFModels\Database;

use ZborovoSK\ZBFModels\Database\Statement;
use PDO;

class Connection
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function prepare(string $sql): Statement
    {
        return new Statement($this->getPdo()->prepare($sql), $this);
    }

    public function query(string $sql, $data = []): Statement
    {
        return $this->prepare($sql)
                    ->execute($data);
    }

    public function beginTransaction(): bool
    {
        return $this->getPdo()->beginTransaction();
    }

    public function commitTransaction(): bool
    {
        return $this->getPdo()->commit();
    }

    public function rollbackTransaction(): bool
    {
        return $this->getPdo()->rollBack();
    }
}
