<?php

namespace MiaoxingTest\Sms\Service;

use MiaoxingTest\Plugin\BaseTestCase;

class BechTest extends BaseTestCase
{
    public function testSendContentSms()
    {
        $mock = $this->getServiceMock('http', ['__invoke']);
        $mock->expects($this->once())
            ->method('__invoke')
            ->willReturn(wei()->newInstance('http', [
                'result' => true,
                'response' => [
                    'result' => '01',
                ],
            ]));

        $ret = wei()->bech->sendContentSms(13800138000, 'test');

        $this->assertRetSuc($ret, '发送成功');
    }

    public function testSendContentSmsError()
    {
        $mock = $this->getServiceMock('http', ['__invoke']);
        $mock->expects($this->once())
            ->method('__invoke')
            ->willReturn(wei()->newInstance('http', [
                'result' => true,
                'response' => [
                    'result' => '02',
                ],
            ]));

        $ret = wei()->bech->sendContentSms(13800138000, 'test');

        $this->assertRetErr($ret, -2, 'IP限制');
    }

    public function testSendContentSmsHttpError()
    {
        $mock = $this->getServiceMock('http', ['__invoke']);
        $mock->expects($this->once())
            ->method('__invoke')
            ->willReturn(wei()->newInstance('http', [
                'result' => false,
            ]));

        $ret = wei()->bech->sendContentSms(13800138000, 'test');

        $this->assertRetErr($ret, -1, '很抱歉,系统繁忙,请稍后再试');
    }

    public function testSendTplSms()
    {
        $ret = wei()->bech->sendTplSms(13800138000, 123, []);

        $this->assertRetErr($ret, -1, '不支持模板短信');
    }
}
