<?php
/**
 * simple package for learning purpose
 */

use Afs\Src\Config;
use Afs\Src\HttpClient;

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

$indexes = (new Config())->info;
$indexes = json_decode($indexes);

$config = new Config();

echo $config->get('User')->UserName;

$mciBehsa = new HttpClient();

// login and get token
$token = $mciBehsa->authenticate($config->get('User')->UserName, $config->get('User')->Password);

// get service token
$serviceToken = $mciBehsa->serviceToken($token['data']['token']);

// get product type list
$productTypeList = $mciBehsa->getProductTypeList($serviceToken['data']['serviceToken'], 1);

// get product list from specific product type
$productList = $mciBehsa->getProductList(1, $serviceToken['data']['serviceToken'], 1);

// get order status
$order = $mciBehsa->requestOrder($orderParams, $serviceToken['data']['serviceToken'], 1);

// get payment link
$paymentUrl = $mciBehsa->getPaymentUrl($orderUrl, $serviceToken['data']['serviceToken'], 1);


// get config
$config = $mciBehsa->getConfig($serviceToken['data']['serviceToken']);
echo '<pre>';
var_dump($config);
echo '</pre>';

//var_dump($paymentUrl);
