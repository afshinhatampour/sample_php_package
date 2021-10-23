<?php


namespace Mci\Behsa;


use GuzzleHttp\Exception\GuzzleException;
use Phpfastcache\Exceptions\PhpfastcacheDriverCheckException;
use Phpfastcache\Exceptions\PhpfastcacheDriverException;
use Phpfastcache\Exceptions\PhpfastcacheDriverNotFoundException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Phpfastcache\Exceptions\PhpfastcacheLogicException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

class MciBehsa
{
    /**
     * @string
     */
    const AUTHENTICATE_TOKEN_KEY = 'mci-behsa-authenticate-token-key';

    /**
     * @string
     */
    const SERVICE_TOKEN_KEY = 'mci-behsa-service-token-key';

    /**
     * @string
     */
    const GLOBAL_CACHE_TIME_OUT_MS = 'mci-behsa-global-cache-time-out-ms';

    /**
     * @string
     */
    const GLOBAL_CACHE_TIME_STAMP = 'mci-behsa-global-cache-time-stamp';

    /**
     * @string
     */
    const PRODUCT_TYPE = 'mci-behsa-product-type';

    /**
     * @string
     */
    const PRODUCT_LIST_PREFIX = 'mci-behsa-product-list';

    /**
     * @string
     */
    const SUCCESS_MESSAGE = '/success';

    /**
     * @string
     */
    const SERVICE_TOKEN_CONST = 'serviceToken';

    /**
     * @string
     */
    const TOKEN_CONST = 'token';

    /**
     * @var mixed
     */
    private $cacheDriver;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Cache
     */
    public $cache;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Response
     */
    private $responder;

    /**
     * MciBehsa constructor.
     * @throws PhpfastcacheDriverCheckException
     * @throws PhpfastcacheDriverException
     * @throws PhpfastcacheDriverNotFoundException
     * @throws PhpfastcacheInvalidArgumentException
     * @throws PhpfastcacheInvalidConfigurationException
     * @throws PhpfastcacheLogicException
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->responder = new Response();
        $this->httpClient = new HttpClient();
        $this->config = new Config();
        $this->cacheDriver = $this->config->get('cacheDriver');
        $this->cache = new Cache($this->cacheDriver);
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function authenticate()
    {
        $password = $this->config->get('User')->Password;
        $username = $this->config->get('User')->UserName;
        if ($this->cache->has(self::AUTHENTICATE_TOKEN_KEY)) {
            $data = $this->cache->get(self::AUTHENTICATE_TOKEN_KEY);
            return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE, true, 200);
        }

        $data = $this->httpClient->authenticate($username, $password);

        if ($data instanceof GuzzleException) {
            return $this->responder->getExceptionResponse($data->getResponse());
        }

        $this->cache->setByExpireDuration(self::AUTHENTICATE_TOKEN_KEY, $data, $this->config->get('AuthTokenCacheTimeOffset'));

        return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE,false, 200);
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function serviceToken()
    {
        $token = $this->authenticate();
        if ($this->cache->has(self::SERVICE_TOKEN_KEY)) {
            $data = $this->cache->get(self::SERVICE_TOKEN_KEY);
            return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE,true, 200);
        }

        $data = $this->httpClient->serviceToken($token['data'][self::TOKEN_CONST]);

        if ($data instanceof GuzzleException) {
            return $this->responder->getExceptionResponse($data->getResponse());
        }

        $this->cache->setByExpireDuration(self::SERVICE_TOKEN_KEY, $data, $this->config->get('ServiceTokenCacheTimeOffset'));

        return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE,false, 200);
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getProductTypeList()
    {
        $serviceToken = $this->serviceToken();
        if (!$this->isChangeProductTypeTimeStamp()) {
            $data = $this->cache->get(self::PRODUCT_TYPE);
            return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE,true, 200);
        }

        $data = $this->httpClient->getProductTypeList($serviceToken['data'][self::SERVICE_TOKEN_CONST]);

        if ($data instanceof GuzzleException) {
            return $this->responder->getExceptionResponse($data->getResponse());
        }

        $this->cache->setByExpireDuration(self::PRODUCT_TYPE, $data, 3600);

        return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE,false, 200);
    }

    /**
     * @return bool
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function isChangeProductTypeTimeStamp()
    {
        $authToken = $this->authenticate();
        $oldChangeProductTimeStamp = $this->cache->get(self::GLOBAL_CACHE_TIME_STAMP);
        $newChangeProductTimeStamp = $this->httpClient->getConfig($authToken['data'][self::TOKEN_CONST]);
        $newChangeProductTimeStamp = $newChangeProductTimeStamp['globalCacheTimeStamp'];

        if ($oldChangeProductTimeStamp !== $newChangeProductTimeStamp) {
            $this->cache->setByExpireDuration(self::GLOBAL_CACHE_TIME_STAMP, $newChangeProductTimeStamp, 3600);
            return true;
        }

        return false;
    }

    /**
     * @param $productId
     * @return array
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getProductList($productId)
    {
        $authToken = $this->authenticate();
        $serviceToken = $this->serviceToken();
        $this->checkGlobalCacheTimeOut($authToken['data']['token']);
        $cacheData = $this->cache->get(self::PRODUCT_LIST_PREFIX . $productId);

        if ($cacheData != null) {
            return $this->responder->getSuccessResponse($cacheData, self::SUCCESS_MESSAGE, true, 200);
        }

        $newData = $this->httpClient->getProductList($productId, $serviceToken['data'][self::SERVICE_TOKEN_CONST]);
        $this->cache->setByExpireDuration(self::PRODUCT_LIST_PREFIX . $productId, $newData, 3600);

        return $this->responder->getSuccessResponse($newData, self::SUCCESS_MESSAGE,false, 200);
    }

    public function checkGlobalCacheTimeOut()
    {
        $authToken = $this->authenticate();
        $newGlobalCacheTimeOut = $this->httpClient->getConfig($authToken['data'][self::TOKEN_CONST]);
        $newGlobalCacheTimeOut = $newGlobalCacheTimeOut['globalCacheTimeOutMS'];
        $this->cache->setByExpireDuration(self::GLOBAL_CACHE_TIME_OUT_MS, $newGlobalCacheTimeOut, 0);
    }

    /**
     * @param $params
     * @param $version
     * @return array
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function requestOrder($params, $version)
    {
        $serviceToken = $this->serviceToken();
        $data = $this->httpClient->requestOrder($params, $serviceToken['data'][self::SERVICE_TOKEN_CONST], $version);

        return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE, false, 200);
    }

    /**
     * @param $params
     * @param $version
     * @return array
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getPaymentUrl($params, $version)
    {
        $serviceToken = $this->serviceToken();
        $data = $this->httpClient->getPaymentUrl($params, $serviceToken['data'][self::SERVICE_TOKEN_CONST], $version);

        return $this->responder->getSuccessResponse($data, self::SUCCESS_MESSAGE, false, 200);
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getConfig()
    {
        $serviceToken = $this->serviceToken();
        return $this->httpClient->getConfig($serviceToken['data'][self::SERVICE_TOKEN_CONST]);
    }

    /**
     * @param $params
     * @return mixed
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getResumeOrder($params)
    {
        $serviceToke = $this->serviceToken();

        return $this->httpClient->resumeOrder($serviceToke['data'][self::SERVICE_TOKEN_CONST], $params);
    }

    /**
     * @param $orderId
     * @return mixed
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getInformOrderStatus($orderId)
    {
        $authenticate = $this->authenticate();

        return $this->httpClient->informOrderStatus($orderId, $authenticate['data'][self::TOKEN_CONST]);
    }
}