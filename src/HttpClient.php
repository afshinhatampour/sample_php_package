<?php

namespace Afs\Src;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class MciBehsa
 * @package Afs\Src
 * Interaction with MCI behsa webservice
 * Call recommended endpoint, Cache required data and return required developer data for interact with webservice
 */
class HttpClient implements Concrete
{
    /**
     * Login token url suffix
     * @string
     */
    const AUTHENTICATE_SUFFIX = 'authenticate';


    /**
     * Services recommended token url suffix
     * @string
     */
    const SERVICE_TOKEN_SUFFIX = 'serviceToken';

    /**
     * Services types url suffix
     * @string
     */
    const PRODUCT_TYPE_LIST_SUFFIX = 'api/call/getProductTypeList';

    /**
     * Services unique type suffix
     * @string
     */
    const UNIQUE_PRODUCT_LIST_SUFFIX = 'api/call/getProductList';

    /**
     * Payment order url suffix
     * @string
     */
    const PAYMENT_URL_BY_ORDER_ID_SUFFIX = 'api/call/getPaymentUrlForOrderId';

    /**
     * Payment link url suffix
     * @string
     */
    const REQUEST_ORDER_SUFFIX = 'api/call/getPaymentUrlForOrderId';

    /**
     * Recommended service
     * @string
     */
    const SERVICES = [
        "RestApiGwgetProductTypeList",
        "RestApiGwgetProductList",
        "RestApiGwrequestOrder",
        "RestApiGwgetPaymentUrlForOrderId",
        "RestApiGwgetOrdersStatus"
    ];

    /**
     * Mci Behsa web service base url
     * @string
     */
    private static $mainUrl = '';

    /**
     * @var
     */
    private $responder;

    /**
     * @var Client
     * instance of guzzle http client
     */
    private $httpClient;

    /**
     * MciBehsa constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
        $this->responder = new Response();
        self::$mainUrl = (new Config())->get('Url');
    }

    /**
     * @param $username
     * @param $password
     * @return array
     */
    public function authenticate($username, $password)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::AUTHENTICATE_SUFFIX, [
                'json' => [
                    'username' => $username,
                    'password' => $password
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }

        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

    /**
     * @param $token
     * @return array
     */
    public function serviceToken($token)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::SERVICE_TOKEN_SUFFIX, [
                'headers' => [
                    'X_Auth_Token' => $token,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'services' => self::SERVICES
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }

        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

    /**
     * @param $serviceToken
     * @param int $version
     * @return mixed
     */
    public function getProductTypeList($serviceToken, $version = 1)
    {
        try {
            $data = $this->httpClient->request('GET', self::$mainUrl . self::PRODUCT_TYPE_LIST_SUFFIX, [
                'headers' => [
                    'X_Auth_Token' => $serviceToken,
                    'Content-type' => 'application/json'
                ],
                'query' => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }

        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

    /**
     * @param $productId
     * @param $serviceToken
     * @param int $version
     * @return mixed
     */
    public function getProductList($productId, $serviceToken, $version = 1)
    {
        try {
            $data = $this->httpClient->request('GET', self::$mainUrl . self::UNIQUE_PRODUCT_LIST_SUFFIX, [
                'headers' => [
                    'X_Auth_Token' => $serviceToken,
                    'Content-type' => 'application/json'
                ],
                'json' => [
                    'productId' => $productId
                ],
                'query' => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }

        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function requestOrder($params, $serviceToken, $version)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::REQUEST_ORDER_SUFFIX, [
                'headers' => [
                    'X_Auth_Token' => $serviceToken,
                    'Content-type' => 'application/json'
                ],
                'json' => $params,
                'query' => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }

        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return mixed
     */
    public function getPaymentUrl($params, $serviceToken, $version)
    {
        try {
            $data = $this->httpClient->request('GET', self::$mainUrl . self::PAYMENT_URL_BY_ORDER_ID_SUFFIX, [
                'headers' => [
                    'X_Auth_Token' => $serviceToken,
                    'Content-type' => 'application/json'
                ],
                'json' => $params,
                'query' => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }

        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

    /**
     * @param $serviceToken
     * @return array|mixed
     */
    public function getConfig($serviceToken)
    {
        try {
            $data = $this->httpClient->request('GET', 'https://apicoret.mci.ir/api/call/getConfig?ver=1', [
                'headers' => [
                    'X_Auth_Token' => $serviceToken,
                ]
            ]);
        } catch (GuzzleException $e) {
            return $this->responder->getExceptionResponse($e->getResponse());
        }
        return $this->responder->getSuccessResponse(
            json_decode($data->getBody(), true),
            'success',
            200
        );
    }

}