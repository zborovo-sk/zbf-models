<?php

namespace ZborovoSK\ZBFModels\MVC\ModelTraits;

use ZborovoSK\ZBFModels\Interfaces\CacherInterface;
use ZborovoSK\ZBFModels\MVC\Model;

/**
 * we define a trait that will be used by all models that need to cache their data
 */
trait CacheTrait
{
    /**
     * @var CacherInterface $cacher
     */
    protected $cacher;

    /**
     * @var int $defaultTtl
     */
    protected $defaultTtl = 3600;

    /**
     * create a cache key based on the model name, query and data
     * @param string $query
     * @param array $data
     * @return string
     */
    protected function createCacheKey(string $query, array $data, string $sufix = ''): string
    {
        return md5(get_class($this) . $query . json_encode($data).$sufix);
    }

    /**
     * get cached data or query the database and cache the result
     * @param string $query
     * @param array $data
     * @param int $ttl
     * @return array - arrat of rows, even if there is only one row
     */
    protected function cachedSelectAll(string $query, array $data, int $fetchStyle = Model::FETCH_ASSOC, int $ttl = null): array
    {
        //check if cacher is set
        if (!$this->cacher) {
            //if not, just query the database
            return $this->selectAll($query, $data, $fetchStyle);
        }
        //generate a cache key
        $key = $this->createCacheKey($query, $data, "all".$fetchStyle);

        //try to get the data from cache, return if found
        $cached = $this->cacher->get($key);
        if ($cached) {
            return $cached;
        }

        //if not found, query the database
        $result = $this->selectAll($query, $data, $fetchStyle);

        //cache the result
        $ttl = $ttl ?? $this->defaultTtl;
        $this->cacher->set($key, $result, $ttl ?? $this->defaultTtl);

        return $result;
    }

    /**
     * get cached data or query the database and cache the result
     * @param string $query
     * @param array $data
     * @param int $ttl
     * @return array - arrat of rows, even if there is only one row
     */
    protected function cachedSelectOne(string $query, array $data, int $fetchStyle = Model::FETCH_ASSOC, int $ttl = null)
    {
        //check if cacher is set
        if (!$this->cacher) {
            //if not, just query the database
            return $this->selectOne($query, $data, $fetchStyle);
        }

        //generate a cache key
        $key = $this->createCacheKey($query, $data, "one".$fetchStyle);

        //try to get the data from cache, return if found
        $cached = $this->cacher->get($key);
        if ($cached) {
            return $cached;
        }

        //if not found, query the database
        $result = $this->selectOne($query, $data, $fetchStyle);

        //cache the result
        $ttl = $ttl ?? $this->defaultTtl;
        $this->cacher->set($key, $result, $ttl ?? $this->defaultTtl);

        return $result;
    }

    /**
     * set the cacher
     * @param CacherInterface $cacher
     * @return void
     */
    public function setCacher(CacherInterface $cacher): void
    {
        $this->cacher = $cacher;
    }
}
