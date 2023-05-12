<?php
require_once __DIR__ . '/vendor/autoload.php';

function pretty($arr, $level = 0)
{
    $tabs = "";
    for ($i = 0; $i < $level; $i++) {
        $tabs .= "    ";
    }
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            print($tabs . $key . " : " . "\n");
            pretty($val, $level + 1);
        } elseif ($val && $val !== 0) {
            print($tabs . $key . " : " . $val . "\n");
        }
    }
}

use DeviceDetector\ClientHints;
use DeviceDetector\Parser\AbstractParser;
use DeviceDetector\Parser\Device\AbstractDeviceParser;




class DeviceDetectorNew
{
    public $userAgent;

    public $hashes = [
        '5044S' => [
            'AL',
        ],
        'SM-A346E' => [
            'SA',
        ],
    ];

    public function parse()
    {

        $alias = new DeviceAlias();
        $alias->setUserAgent($this->userAgent);
        $code = $alias->parse()['name'] ?? '';
        return $this->getDataByHash($code);

        /*$found = [];
        for ($i = 0, $l = count($chunks); $i <= $l; $i++) {
            $partCurrent = $chunks[$i] ?? '';
            $nextHash = $partCurrent;
            for ($j = $i; $j <= $l; $j++) {
                if (!isset($chunks[$j])) {
                    continue;
                }
                $nextHash .= sprintf(' %s', $chunks[$j]);
                $nextHash = trim($nextHash);
                $data = $this->getDataByHash($nextHash);
                if (null !== $data) {
                    $found[] = $data;
                }
                $hashSpace = str_replace(' ', '_', $nextHash);
                $data = $this->getDataByHash($hashSpace);
                if (null !== $data) {
                    $found[] = $data;
                }
            }
        }*/

        return [];
    }

    public function getBrandByShort(string $hash)
    {
        $id = $this->hashes[$hash][0] ?? null;
        if (null === $id) {
            return null;
        }

        return AbstractDeviceParser::$deviceBrands[$id] ?? null;
    }

    public function getDataByHash($hash)
    {
        $data = $this->hashes[$hash] ?? null;
        if (null === $data) {
            return null;
        }

        return [
            'id' => $data[0],
            'brand' => $data['brand'] ?? $this->getBrandByShort($hash),
            'model' => $data['model'] ?? $hash,
            'device' => $data[1] ?? 'smartphone',
            'code' => $hash,
        ];
    }
}


$userAgents = [
    'Mozilla/5.0 (Linux; Android 7.0; 5044S Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125',
    'Mozilla/5.0 (Linux; arm_64; Android 13; SM-A346E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 YaBrowser/23.3.4.84.00 SA/3 Mobile Safari/537.36',
    "Mozilla/5.0 (Linux; Android 5.1.1; X70 R(C7F9)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.99 Safari/537.36",
];

$dd = new DeviceDetectorNew();
foreach ($userAgents as $userAgent) {
    $dd->userAgent = $userAgent;
    pretty($dd->parse());
}
