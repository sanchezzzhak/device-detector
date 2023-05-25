<?php


use DeviceDetector\Parser\DeviceHash;
use PHPUnit\Framework\TestCase;

class DeviceHashTest extends TestCase
{
    public function testBase()
    {
        $dd = new DeviceHash();
        $dd->setUserAgent('Mozilla/5.0 (Linux; arm_64; Android 13; SM-A346E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 YaSearchBrowser/23.33.1 BroPP/1.0 YaSearchApp/23.33.1 webOmni SA/3 Mobile Safari/537.36');
        var_dump($dd->parse());



    }
}