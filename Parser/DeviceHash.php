<?php

namespace DeviceDetector\Parser;


use DeviceDetector\Helpers\DataPacker;

class DeviceHash extends AbstractParser
{
    public $parserName = 'device-hash';

    protected $fixtureFile = 'regexes/device-data.yml';

    private const DEVICE_SOURCE_MAP = [
        'BD' => 'brand_id',
        'MD' => 'model',
        'DV' => 'device_type',
        'OSV' => 'os_version'
    ];

    public function parse(): array
    {
        $osName = '';
        $osVersion = '';

        $results = $this->searchDeviceForHash();


        var_dump($results, $this->iterateCount);
        return [];
    }

    private $iterateCount = 0;

    /**
     * @return array
     */
    private function searchDeviceForHash()
    {
        $userAgent = $this->userAgent;
        $listHash = $this->getRegexes();
        var_dump($listHash);

        $replacePattern = '~(?:\(KHTML, like Gecko\)|([\w.]+/\d+\.[.\d]+)|[()])~i';
        $userAgent = preg_replace($replacePattern, '', $userAgent);
        $chunks = preg_split('~[; ]~', $userAgent);
        $chunks = array_values(array_filter($chunks));

        $this->iterateCount = 0;
        $found = [];
        for ($i = 0, $l = count($chunks); $i <= $l; $i++) {
            $nextHash = $chunks[$i] ?? '';
            $data = $listHash[$nextHash] ?? null;
            if (null !== $data) {
                $found[$nextHash] = DataPacker::unpack($data, self::DEVICE_SOURCE_MAP);
            }
            for ($j = $i + 1; $j <= $l; $j++) {
                if (!isset($chunks[$j])) {
                    continue;
                }
                $nextHash .= sprintf(' %s', $chunks[$j]);
                $nextHash = trim($nextHash);
                $data = $listHash[$nextHash] ?? null;
                if (null !== $data) {
                    $found[$nextHash] = DataPacker::unpack($data, self::DEVICE_SOURCE_MAP);
                }
                $this->iterateCount++;
            }



        }
        return $found;
    }


}