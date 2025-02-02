<?php

namespace ZborovoSK\ZBFModels\Database;


use ZborovoSK\ZBFModels\Database\Connection;
use ZborovoSK\ZBFModels\DatabaseException;
use ZborovoSK\ZBFModels\Interfaces\CacherInterface;
use PDO;

class Connector
{
    private static Connector $instance;

    private array $connections = [];

    private CacherInterface $cacher;

    private function __construct()
    {
        // empty private constructor
    }

    public static function getInstance(): Connector
    {
        if (!isset(self::$instance)) {
            self::$instance = new Connector();
        }

        return self::$instance;
    }

    public function getConnection(string $name): Connection
    {
        if (!isset($this->connections[$name])) {
            throw new DatabaseException("Connection with name $name not found");
        }

        return $this->connections[$name];
    }

    public function addConnection(string $name, PDO $pdo): void
    {
        //make sure we set the error mode to exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connections[$name] = new Connection($pdo);
    }

    public function setCacher(CacherInterface $cacher): void
    {
        $this->cacher = $cacher;
    }

    public function getCacher(): CacherInterface
    {
        return $this->cacher;
    }
}
