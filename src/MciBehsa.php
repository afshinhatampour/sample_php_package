<?php


namespace Afs\Src;


class MciBehsa implements Concrete
{
    /**
     * @param $username
     * @param $password
     * @return mixed
     */
    public function authenticate($username, $password)
    {
        // TODO: Implement authenticate() method.
    }

    /**
     * @param $token
     * @return mixed
     */
    public function serviceToken($token)
    {
        // TODO: Implement serviceToken() method.
    }

    /**
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getProductTypeList($serviceToken, $version)
    {
        // TODO: Implement getProductTypeList() method.
    }

    /**
     * @param $productId
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getProductList($productId, $serviceToken, $version)
    {
        // TODO: Implement getProductList() method.
    }

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function requestOrder($params, $serviceToken, $version)
    {
        // TODO: Implement requestOrder() method.
    }

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getPaymentUrl($params, $serviceToken, $version)
    {
        // TODO: Implement getPaymentUrl() method.
    }

    /**
     * @param $serviceToken
     * @return mixed
     */
    public function getConfig($serviceToken)
    {
        // TODO: Implement getConfig() method.
    }
}