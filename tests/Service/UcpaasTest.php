<?php

namespace MiaoxingTest\Sms\Service;

use Miaoxing\Plugin\Test\BaseTestCase;

class UcpaasTest extends BaseTestCase
{
    public function testSendContentSms()
    {
        $ret = wei()->ucpaas->sendContentSms(13800138000, 'test');

        $this->assertRetErr($ret, -1, '不支持内容短信');
    }

    public function testSendTplSms()
    {
        $mock = $this->getMockBuilder('Ucpaas')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('templateSMS')
            ->willReturn(json_encode([
                'resp' => [
                    'respCode' => '000000',
                ],
            ]));
        /** @var \Ucpaas $mock */
        wei()->ucpaas->setUcpaas($mock);

        $ret = wei()->ucpaas->sendTplSms(13800138000, 1, []);

        $this->assertRetSuc($ret);
    }

    public function testSendTplSmsErr()
    {
        $mock = $this->getMockBuilder('Ucpaas')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('templateSMS')
            ->willReturn(json_encode([
                'resp' => [
                    'respCode' => '000001',
                ],
            ]));
        /** @var \Ucpaas $mock */
        wei()->ucpaas->setUcpaas($mock);

        $ret = wei()->ucpaas->sendTplSms(13800138000, 1, []);

        $this->assertRetErr($ret, -1, '发送失败');
    }

    public function testGetUcpaas()
    {
        $ucpaas = wei()->ucpaas->getUcpaas();

        $this->assertInstanceOf('Ucpaas', $ucpaas);
    }

    public function testSetUcpaas()
    {
        $ucpaas = new \Ucpaas([
            'token' => '', // 官方类库要求配置不能为空
        ]);
        $ret = wei()->ucpaas->setUcpaas($ucpaas);

        $this->assertInstanceOf('\Miaoxing\Sms\Service\Ucpaas', $ret);
    }

    public function testSetGetUcpaas()
    {
        wei()->ucpaas->setUcpaas(null);

        $ucpaas = wei()->ucpaas->getUcpaas([
            'token' => '',
        ]);

        $this->assertInstanceOf('Ucpaas', $ucpaas);
    }
}
