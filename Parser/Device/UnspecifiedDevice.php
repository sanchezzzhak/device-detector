<?php
namespace DeviceDetector\Parser\Device;


class UnspecifiedDevice extends DeviceParserAbstract
{
    protected $fixtureFile = 'regexes/device/unspecified_device.yml';
    protected $parserName  = 'unspecified_device';

    /**
     * @return bool
     * @throws \Exception
     */
    public function parse()
    {
        $regexes = $this->getRegexes();
        foreach ($regexes as $brand => $regex) {
            $matches = $this->matchUserAgent($regex['regex']);
            if ($matches) {
                break;
            }
        }

        if (empty($matches)) {
            return false;
        }


        return true;
    }

}