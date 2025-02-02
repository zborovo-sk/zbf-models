<?php

namespace ZborovoSK\ZBFModels\Interfaces;


interface CacherInterface
{
    public function get(string $key);

    public function set(string $key, $value, int $ttl = 0);

    public function delete(string $key);
}
