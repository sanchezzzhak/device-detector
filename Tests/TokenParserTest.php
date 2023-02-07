<?php
declare(strict_types=1);

namespace DeviceDetector\Tests;

use DeviceDetector\Parser\TokenParser;
use PHPUnit\Framework\TestCase;

class TokenParserTest extends TestCase
{
    public function testParse()
    {
        $ua = 'Mozilla/5.0 (Linux; U; Android 4.2.2; zh-CN; R831K Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.3.1.549 U3/0.8.0 Mobile Safari/534.30';

        $ua = 'Mozilla/5.0 (Linux; Android 4.4.2; X10 (M1D3)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36';
//        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_2 like Mac OS X) AppleWebKit/603.2.4 (KHTML, like Gecko) Mobile/14F89 JsKit/1.0 (iOS) /SohuNews SohuNews/5.9.2';
//        $ua = 'TCL J706T_TD/1.0 Linux/3.4.5 Android/4.1.2 Release/08.27.2013 Browser/AppleWebKit534.30 Profile/MIDP-2.0 Configuration/CLDC-1.1 baiduboxapp/4.2 (Baidu; P1 4.1.2)';

        $dd = new TokenParser($ua);

        var_dump($dd->tokens);





    }
}