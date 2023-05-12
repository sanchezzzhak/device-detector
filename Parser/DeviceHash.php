<?php

namespace DeviceDetector\Parser;


class DeviceHash extends AbstractParser
{
    protected $fixtureFile = 'device-hash';

    public function parse(): array
    {
        foreach ($this->getRegexes() as $regex) {
            $matches = $this->matchUserAgent($regex['regex']);
            if ($matches) {
                return [
                    'name' => $matches[1],
                ];
            }
        }
        return [];
    }



}