<?php

use Mci\Behsa\Config;
use Mci\Behsa\MciBehsa;

/**
 * required composer autoload file
 */
require "vendor/autoload.php";

$orderParams = [
    "orderType" => "200",
    "sourceMSISDN" => "9100038202",
    "destinationMSISDN" => "9100038202",
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
echo 'auth token : ' . $pre;
var_dump($token['data']['token']);
echo $closePre;

// Service token
$serviceToken = $mciBehsaWebService->serviceToken();
echo 'service token : ' . $pre;
var_dump($serviceToken['data']['serviceToken']);
echo $closePre;

// Get product type list
$productType = $mciBehsaWebService->getProductTypeList();
echo 'product types: ' . $pre;
var_dump($productType);
echo $closePre;

// Get list of product by product type id
$productList = $mciBehsaWebService->getProductList(1);
echo 'product list : ' . $pre;
var_dump($productList);
echo $closePre;

// Request order
$requestOrder = $mciBehsaWebService->requestOrder($orderParams, 1);
echo 'request order ' . $pre;
var_dump($requestOrder);
echo $closePre;

// Payment url
$getPaymentUrl = $mciBehsaWebService->getPaymentUrl($orderUrl, 1);
echo 'Payment url ' . $pre;
var_dump($getPaymentUrl);
echo $closePre;

