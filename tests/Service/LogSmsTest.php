<?php

namespace MiaoxingTest\Sms\Service;

class LogSmsTest extends \Miaoxing\Plugin\Test\BaseTestCase
{
    public function testSendTplSms()
    {
        $ret = wei()->logSms->sendTplSms(13800138000, 1, []);

        $this->assertSame(1, $ret['code']);
    }

    public function testSendContentSms()
    {
        $ret = wei()->logSms->sendContentSms(13800138000, 'test');

        $this->assertSame(1, $ret['code']);
    }
}
