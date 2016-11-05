<?php

namespace MiaoxingTest\Sms\Service;

class LogSmsTest extends \MiaoxingTest\Plugin\BaseTestCase
{
    public function testSendTplSms()
    {
        $ret = wei()->logSms->sendTplSms(13800138000, 1, []);

        $this->assertRetSuc($ret);
    }

    public function testSendContentSms()
    {
        $ret = wei()->logSms->sendContentSms(13800138000, 'test');

        $this->assertRetSuc($ret);
    }
}
