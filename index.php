<?php

use Mci\Behsa\Config;
use Mci\Behsa\MciBehsa;
use Mci\Behsa\Cache;

/**
 * required composer autoload file
 */
require "vendor/autoload.php";

$orderParams = [
    "orderType" => "200",
    "sourceMSISDN" => "9922096585",
    "destinationMSISDN" => "9922096585",
    "productId" => "1",
    "payloadId" => "1",
    "payloadAmount" => "10000",
    "payloadChargeType" => "10000",
    "payloadPackageType" => "100"
];

$orderUrl = [
    'callBackUrl' => 'mysite.com',
    'orderId' => "684"
];

$resumeOrderData = [
    "msisdn" => "989187946870",
    "orderId" => "945",
    "fwdString" => "687652*85*44125876*20000*945*6"
];

$config = new Config();
//(new Cache())->clear();die();
$mciBehsaWebService = new MciBehsa();

// Get user params
$username = $config->get('User')->UserName;
$userPassword = $config->get('User')->Password;

$pre = '<pre>';
$closePre = '</pre>';
$tokenIndex = 'token';
$serviceTokenIndex = 'serviceToken';

// Auth token
$token = $mciBehsaWebService->authenticate();


// Service token
$serviceToken = $mciBehsaWebService->serviceToken();


// Get product type list
$productType = $mciBehsaWebService->getProductTypeList();


// Get list of product by product type id
$productList = $mciBehsaWebService->getProductList(1);


// Request order
$requestOrder = $mciBehsaWebService->requestOrder($orderParams, 1);

// Payment url
$getPaymentUrl = $mciBehsaWebService->getPaymentUrl([
    'deepLinkUrl' => 'web.whatsapp.com',
    'orderId' => $requestOrder['data']['orderId']
], 1);

// request status
$getrequestStatus = $mciBehsaWebService->getInformOrderStatus($requestOrder['data']['orderId']);
var_dump($getrequestStatus);


