<?php namespace DeviceDetector\Tests\Parser\Devices;
use DeviceDetector\Parser\Device\UnspecifiedDevice;
use PHPUnit\Framework\TestCase;

/**
 * Class UnspecifiedDeviceTest
 * @package DeviceDetector\Tests\Parser\Devices
 */
class UnspecifiedDeviceTest extends TestCase
{
    /**
     * @dataProvider getFixtures
     */
    public function testParse($useragent, $device)
    {
        $consoleParser = new UnspecifiedDevice();
        $consoleParser->setUserAgent($useragent);
        $this->assertTrue($consoleParser->parse());
        $this->assertEquals($device['type'], $consoleParser->getDeviceType());
        $this->assertEquals($device['brand'], $consoleParser->getBrand());
        $this->assertEquals($device['model'], $consoleParser->getModel());
    }

    public function getFixtures()
    {
        return \Spyc::YAMLLoad(realpath(dirname(__FILE__)) . '/fixtures/unspecified_device.yml');
    }
}