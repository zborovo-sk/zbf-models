<?php

namespace ZborovoSK\ZBFModels\Database;


use ZborovoSK\ZBFModels\Database\Connection;
use PDOStatement;

class Statement
{
    private PDOStatement $statement;
    private Connection $connection;

    public function __construct(PDOStatement $statement, Connection $connection)
    {
        $this->statement = $statement;
        $this->connection = $connection;
    }

    public function execute($data = []): self
    {
        $this->statement->execute($data);
        return $this;
    }

    public function fetchAll($fetchStyle = null): array
    {
        return $this->statement->fetchAll($fetchStyle);
    }

    public function fetch($fetchStyle = null)
    {
        return $this->statement->fetch($fetchStyle);
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function lastInsertId($name = null): string
    {
        return $this->connection->getPdo()->lastInsertId($name);
    }

    public function getStatement(): PDOStatement
    {
        return $this->statement;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function __destruct()
    {
        $this->statement->closeCursor();
    }
}
