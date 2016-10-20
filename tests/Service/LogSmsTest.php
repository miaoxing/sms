<?php

namespace MiaoxingTest\Sms\Service;

class LogSmsTest extends \miaoxing\plugin\tests\BaseTestCase
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
