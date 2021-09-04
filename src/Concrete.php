<?php


namespace Afs\Src;


interface Concrete
{

    /**
     * @param $username
     * @param $password
     * @return mixed
     */
    public function authenticate($username, $password);

    /**
     * @param $token
     * @return mixed
     */
    public function serviceToken($token);

    /**
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getProductTypeList($serviceToken, $version);

    /**
     * @param $productId
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getProductList($productId, $serviceToken, $version);

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function requestOrder($params, $serviceToken, $version);

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getPaymentUrl($params, $serviceToken, $version);

    /**
     * @param $serviceToken
     * @return mixed
     */
    public function getConfig($serviceToken);
}