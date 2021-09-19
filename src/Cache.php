<?php

namespace Mci\Behsa;

use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;
use Phpfastcache\Exceptions\PhpfastcacheDriverException;
use Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Phpfastcache\Exceptions\PhpfastcacheLogicException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Phpfastcache\Helper\Psr16Adapter;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

class Cache {

    private $cache;

    private $driver;

    /**
     * Cache constructor.
     * @param $driver
     * @throws PhpfastcacheDriverCheckException
     * @throws PhpfastcacheDriverException
     * @throws PhpfastcacheDriverNotFoundException
     * @throws PhpfastcacheInvalidArgumentException
     * @throws PhpfastcacheInvalidConfigurationException
     * @throws PhpfastcacheLogicException
     * @throws ReflectionException
     */
    public function __construct($driver = 'Files')
    {
        $this->driver = $driver;
        $this->cache = new Psr16Adapter($this->driver);
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param $key
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function has($key)
    {
        return $this->cache->has($key);
    }

    /**
     * @param $key
     * @param $value
     * @param $date
     * @return mixed
     */
    public function setByExpireDate($key, $value, $date)
    {
        return $this->set($key, $value)->expireAt($date);
    }

    /**
     * @param $key
     * @param $value
     * @param int $ttl
     * @return mixed
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function setByExpireDuration($key, $value, $ttl = 3600)
    {
        return $this->cache->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    /**
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function clear()
    {
        return $this->cache->clear();
    }
}