<?php

namespace ZborovoSK\ZBFModels\MVC;

use ZborovoSK\ZBFModels\Database\Connection;
use ZborovoSK\ZBFModels\Database\Connector;

class Model
{
    //fetch style constants
    public const FETCH_ASSOC = 2;
    public const FETCH_OBJ = 5;


    protected Connection $db;
    protected string $connectionName = 'default';

    public function __construct()
    {
        //we auto assign connection based on the connectionName
        $this->db = Connector::getInstance()->getConnection($this->connectionName);

        //if we model has a cacheTrait we set the cacher
        if (method_exists($this, 'setCacher')) {
            $cacher = Connector::getInstance()->getCacher();
            if($cacher) {
                $this->setCacher($cacher);
            }
        }
    }

    public function getAll(string $query, array $data = [], int $fetchStyle = Model::FETCH_ASSOC): array
    {
        return $this->db->query($query, $data)->fetchAll($fetchStyle);
    }

    public function getOne(string $query, array $data = [], int $fetchStyle = Model::FETCH_ASSOC)
    {
        return $this->db->query($query, $data)->fetch($fetchStyle);
    }


}
