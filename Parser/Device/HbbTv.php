<?php
/**
 * Device Detector - The Universal Device Detection library for parsing User Agents
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/lgpl.html LGPL v3 or later
 */
namespace DeviceDetector\Parser\Device;

/**
 * Class HbbTv
 *
 * Device parser for hbbtv detection
 *
 * @package DeviceDetector\Parser\Device
 */
class HbbTv extends DeviceParserAbstract
{
    protected $fixtureFile = 'regexes/device/televisions.yml';
    protected $parserName = 'tv';

    /**
     * Parses the current UA and checks whether it contains HbbTv information
     *
     * @see televisions.yml for list of detected televisions
     */
    public function parse()
    {
        // only parse user agents containing hbbtv fragment
        if (!$this->isTV()) {
            return false;
        }

        // always set device type to tv, even if no model/brand could be found
        $this->deviceType = self::DEVICE_TYPE_TV;
        parent::parse();

        return true;
    }

    /**
     * Returns true if an HbbTv, Smart Tv, or Android Tv fragment is present
     *
     * @return bool
     */
    public function isTV()
    {
        $regex = '(HbbTV/(?:[1-9]{1}(?:\.[0-9]{1}){1,2})|Android[ _-]?TV|TV[ _-]?(?:BOX|SMART)|SMART[ _-]?TV)[/;) ]';
        $match = $this->matchUserAgent($regex);
        return is_array($match) && array_key_exists(0, $match);
    }
}
