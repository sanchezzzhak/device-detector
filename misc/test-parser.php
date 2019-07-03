<?php

include_once __DIR__ . "/../vendor/autoload.php";

use DeviceDetector\DeviceDetector;

/**
 * @param string $userAgent
 * @return array
 */
function parseUserAgent($userAgent)
{
    $pattern = '#^([^\/]+)\/([^\s]+)\s\((.*)\)\s(.*)$#is';
    if (preg_match($pattern, $userAgent, $userAgentPart)) {
        $vendorEngine = $userAgentPart[1];
        $vendorEngineVersion = $userAgentPart[2];
        $systemInfo = $userAgentPart[3];
        $platformInfo = $userAgentPart[4];

        return compact(
            'vendorEngine',
            'vendorEngineVersion',
            'systemInfo',
            'platformInfo'
        );
    }

    return [];
}

/**
 * @param string $systemInfo
 * @return string|null
 */
function parseModelHash($systemInfo)
{
    $pattern = '#;?[ ]([^;]+) Build\/|;?[ ]([^;]+)$#is';
    if (preg_match($pattern, $systemInfo, $userAgentPart)) {
        $hash = trim(strtolower($userAgentPart[1]));
        $hash = str_replace(['_'], [' '], $hash);
        return $hash;
    }
    return null;
}



$userAgent = 'Mozilla/5.0 (Linux; Android 7.0; JMM-AL00 Build/HONORJMM-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.0.0 Mobile Safari/537.36';

$fixtures = \Spyc::YAMLLoad(__DIR__. '/new_models.yml');

echo "test parse {$userAgent} \n";


// ===============================

$start = microtime(true);
$userAgentInfo = parseUserAgent($userAgent);
$hash = parseModelHash($userAgentInfo['systemInfo']);

$result = [];
$device = null;
$model = null;
$brand = null;

$models = $fixtures[$hash]['models'] ?? [];

foreach ($models as $model){
    if(preg_match( '#' . str_replace('/', '\/', $model['regexp']) . '#i'  , $userAgent, $match)){
        $device = $model['type'];
        $modelName =  $model['model'];
        $brand =  $model['brand'];
        $result = [
            'device' => $device,
            'model' => $modelName,
            'brand' => $brand
        ];
        break;
    }
}
echo json_encode($result) . "\n";
echo "\nnew parse ". number_format(microtime(true) - $start, 5). " seconds.\n\n";


// ===============================

$start = microtime(true);
$result = [];
$deviceDetector = new DeviceDetector($userAgent);
$parsers = $deviceDetector->getDeviceParsers();
$device = null;
$model = null;
$brand = null;
foreach ($parsers as $parser) {
    $parser->setYamlParser($deviceDetector->getYamlParser());
    $parser->setCache($deviceDetector->getCache());
    $parser->setUserAgent($deviceDetector->getUserAgent());
    if ($parser->parse()) {

        $device = $parser->getDeviceType();
        $model = $parser->getModel();
        $brand = $parser->getBrand();
        $result = [
            'device' => $device,
            'model' => $model,
            'brand' => $brand
        ];
        break;
    }
}

echo json_encode($result) . "\n";
echo "\nold parse ". number_format(microtime(true) - $start, 5). " seconds.\n\n";