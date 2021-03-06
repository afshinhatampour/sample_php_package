<?php

namespace Mci\Behsa;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class MciBehsa
 * @package Afs\Src
 * Interaction with MCI behsa webservice
 * Call recommended endpoint, Cache required data and return required developer data for interact with webservice
 */
class HttpClient
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
    const REQUEST_ORDER_SUFFIX = 'api/call/requestOrder';

    /**
     * Get config link url suffix
     * @string
     */
    const GET_CONFIG_SUFFIX = 'api/call/getConfig?ver=1';

    /**
     * Resume order link url suffix
     * @string
     */
    const RESUME_ORDER_SUFFIX = 'api/call/resumeorder';

    /**
     * Order inform status link url suffix
     * @string
     */
    const ORDER_INFORM_STATUS_SUFFIX = 'api/call/getOrdersStatus';

    /**
     * Http client header string
     * @string
     */
    const HTTP_CLIENT_HEADER_NAME = 'headers';

    /**
     * Request auth token header index
     */
    const X_AUTH_TOKEN = 'X_Auth_Token';

    /**
     * Request application json text
     * @string
     */
    const APPLICATION_JSON = 'application/json';

    /**
     * Request header content type index
     * @string
     */
    const HTTP_HEADER_CONTENT_TYPE = 'Content-Type';

    /**
     * Request body query index
     * @string
     */
    const HTTP_BODY_QUERY_INDEX = 'query';

    /**
     * Request body json index
     * @string
     */
    const HTTP_BODY_JSON_INDEX = 'json';

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
     * @return Exception|GuzzleException|mixed
     */
    public function authenticate($username, $password)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::AUTHENTICATE_SUFFIX, [
                self::HTTP_BODY_JSON_INDEX => [
                    'username' => $username,
                    'password' => $password
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $token
     * @return Exception|GuzzleException
     */
    public function serviceToken($token)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::SERVICE_TOKEN_SUFFIX, [
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $token,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_JSON_INDEX => [
                    'services' => self::SERVICES
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
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
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $serviceToken,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_QUERY_INDEX => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $productId
     * @param $serviceToken
     * @param int $version
     * @return Exception|GuzzleException|mixed
     */
    public function getProductList($productId, $serviceToken, $version = 1)
    {
        try {
            $data = $this->httpClient->request('GET', self::$mainUrl . self::UNIQUE_PRODUCT_LIST_SUFFIX, [
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $serviceToken,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_JSON_INDEX => [
                    'productId' => $productId
                ],
                self::HTTP_BODY_QUERY_INDEX => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return Exception|GuzzleException|mixed
     */
    public function requestOrder($params, $serviceToken, $version)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::REQUEST_ORDER_SUFFIX, [
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $serviceToken,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_JSON_INDEX => $params,
                self::HTTP_BODY_QUERY_INDEX => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $params
     * @param $serviceToken
     * @param $version
     * @return Exception|GuzzleException|mixed
     */
    public function getPaymentUrl($params, $serviceToken, $version)
    {
        try {
            $data = $this->httpClient->request('GET', self::$mainUrl . self::PAYMENT_URL_BY_ORDER_ID_SUFFIX, [
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $serviceToken,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_JSON_INDEX => $params,
                self::HTTP_BODY_QUERY_INDEX => [
                    'ver' => $version
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $authToken
     * @return mixed
     */
    public function getConfig($authToken)
    {
        try {
            $data = $this->httpClient->request('GET', self::$mainUrl . self::GET_CONFIG_SUFFIX, [
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $authToken,
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $serviceToken
     * @param $params
     * @return mixed
     */
    public function resumeOrder($serviceToken, $params)
    {
        try {
            $data = $this->httpClient->request('POST', self::$mainUrl . self::RESUME_ORDER_SUFFIX, [
                self::HTTP_CLIENT_HEADER_NAME => [
                    'X_Service_Token' => $serviceToken,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_JSON_INDEX => $params
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }

        return json_decode($data->getBody(), true);
    }

    /**
     * @param $orderId
     * @param $authToken
     * @param int $version
     * @return mixed
     * @throws Exception
     */
    public function informOrderStatus($orderId, $authToken, $version = 1)
    {
        try {
            $data = $this->httpClient->request('POST', 'https://apicoret.mci.ir/api/call/getOrdersStatus?ver=1', [
                self::HTTP_CLIENT_HEADER_NAME => [
                    self::X_AUTH_TOKEN => $authToken,
                    self::HTTP_HEADER_CONTENT_TYPE => self::APPLICATION_JSON
                ],
                self::HTTP_BODY_JSON_INDEX => [
                    "orderId" => $orderId,
                    "fromTimeStamp" => "1626112563",
                    "toTimeStamp" => "1626512580"
                ]
            ]);
        } catch (GuzzleException $exception) {
            return $exception;
        }
        return json_decode($data->getBody(), true);
    }
}