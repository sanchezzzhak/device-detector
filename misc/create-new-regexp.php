<?php
include_once __DIR__ . "/../vendor/autoload.php";

use DeviceDetector\DeviceDetector;

// -- uc --- UCWEB/2.0 (Java; U; MIDP-2.0; fr-FR; ALCATEL_one_touch_585) U2/1.0.0 UCBrowser/9.4.1.377 U2/1.0.0 Mobile UNTRUSTED/1.0
// -- chrome --- Mozilla/5.0 (Linux; Android 4.4.2; 7045Y Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.135 Mobile Safari/537.36
// -- opera --- Mozilla/5.0 (Linux; U; Android 7.0; Plume L1 Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/62.0.3202.84 Mobile Safari/537.36 OPR/35.0.2254.127755
// -- Mozilla/5.0 (Linux; Android 7.0; CUBOT CHEETAH 2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.105 Mobile Safari/537.36
// --


// User-Agent: Mozilla/<version> (<system-information>) <platform> (<platform-details>) <extensions>


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

//$userAgent = 'Mozilla/5.0 (Linux; Android 4.4.2; 4035A Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36';
//var_dump(parseUserAgent($userAgent));
//
//
//exit;


$fixtures = [];
$fixtureFiles = glob(realpath(__DIR__) . '/../Tests/fixtures/*.yml');

foreach ($fixtureFiles AS $fixturesPath) {
    if (strpos($fixturesPath, 'smartphone') === false && strpos($fixturesPath, 'tablet') === false) {
        continue;
    }

    $typeFixtures = \Spyc::YAMLLoad($fixturesPath);
    $deviceType = str_replace('_', ' ', substr(basename($fixturesPath), 0, -4));
    if ($deviceType === 'bots') {
        continue;
    }

    foreach ($typeFixtures as $fixture) {
        $result = DeviceDetector::getInfoFromUserAgentInfo($fixture['user_agent']);
        $userAgentInfo = parseUserAgent($fixture['user_agent']);

        if (!count($userAgentInfo)) {
            file_put_contents(
                'problem-useragent-info.txt', $fixture['user_agent'] . "\n", FILE_APPEND
            );
            continue;
        }

        $hash = parseModelHash($userAgentInfo['systemInfo']);
        if ((string)$hash === '') {
            file_put_contents(
                'problem-useragent-hash.txt', $fixture['user_agent'] . "\n", FILE_APPEND
            );
            continue;
        }

        if (!isset($result['model_regexp'])) {
            file_put_contents(
                'problem-useragent-model_regexp.txt', $fixture['user_agent'] . "\n", FILE_APPEND
            );
        }


        $models = $fixtures[$hash]['models'] ?? [];
        $fixtures[$hash]['models'][] = [
            'useragent' => $fixture['user_agent'],
            'regexp' => $result['model_regexp'],
            'type' => $fixture['device']['type'],
            'brand' => $fixture['device']['brand'],
            'model' => $fixture['device']['model'],
        ];

    }
}
$content = \Spyc::YAMLDump($fixtures, false, 0);
file_put_contents('new_models.yml', $content);



/*
 array(6) {
  ["user_agent"]=>
  string(135) "Mozilla/5.0 (Linux; Android 6.0.1; 5051D Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.132 Mobile Safari/537.36"
  ["os"]=>
  array(4) {
    ["name"]=>
    string(7) "Android"
    ["short_name"]=>
    string(3) "AND"
    ["version"]=>
    string(5) "6.0.1"
    ["platform"]=>
    string(0) ""
  }
  ["client"]=>
  array(6) {
    ["type"]=>
    string(7) "browser"
    ["name"]=>
    string(13) "Chrome Mobile"
    ["short_name"]=>
    string(2) "CM"
    ["version"]=>
    string(13) "57.0.2987.132"
    ["engine"]=>
    string(5) "Blink"
    ["engine_version"]=>
    string(0) ""
  }
  ["device"]=>
  array(3) {
    ["type"]=>
    string(10) "smartphone"
    ["brand"]=>
    string(2) "AL"
    ["model"]=>
    string(24) "One Touch Pop 4 Dual SIM"
  }
  ["os_family"]=>
  string(7) "Android"
  ["browser_family"]=>
  string(6) "Chrome"
}

 */


//var_dump($fixtures);
