<?php

namespace MiaoxingTest\Sms\Service;

use Miaoxing\Plugin\Test\BaseTestCase;

class SmsTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->sms->resetLimit('13800138000');
    }

    public function testSendErrorMobile()
    {
        $ret = wei()->sms->send([
            'mobile' => 'err',
            'content' => 'content',
        ]);

        $this->assertRetErr($ret, '手机号码必须是11位长度的数字,以13,14,15,17或18开头', -1);
    }

    public function testSendEmptyDriver()
    {
        wei()->sms->setOption('drivers', []);

        $mock = $this->getServiceMock('logger', ['alert']);
        $mock->expects($this->once())
            ->method('alert')
            ->with('所有短信服务发送失败', ['errors' => []]);

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
            'content' => 'content',
        ]);

        $this->assertRetErr($ret, '很抱歉,短信服务暂时不可以用,请稍后再试', -4);
    }

    public function testSendContentByLogSmsDriver()
    {
        wei()->sms->setOption('drivers', ['logSms']);

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
            'content' => 'content',
        ]);

        $this->assertRetSuc($ret);
    }

    public function testSendTplByLogSmsDriver()
    {
        wei()->sms->setOption('drivers', ['logSms']);

        $mock = $this->getServiceMock('logSms', ['sendTplSms']);
        $mock->expects($this->once())
            ->method('sendTplSms')
            ->with('13800138000', 1, [0 => '123456'])
            ->willReturn([
                'code' => 1,
                'message' => '发送成功',
            ]);

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
            'tplIds' => [
                'logSms' => 1,
            ],
            'data' => [
                0 => 123456,
            ],
        ]);

        $this->assertRetSuc($ret);

        unset(wei()->sms->logger);
    }

    public function testSendLogSmsDriverError()
    {
        wei()->sms->setOption('drivers', ['logSms']);

        $mock = $this->getServiceMock('logSms', ['sendContentSms']);
        $mock->expects($this->once())
            ->method('sendContentSms')
            ->with('13800138000', 'content')
            ->willReturn([
                'code' => -1,
                'message' => '失败',
            ]);

        $mock = $this->getServiceMock('logger', ['alert']);
        $mock->expects($this->once())
            ->method('alert')
            ->with('所有短信服务发送失败', [
                'errors' => [
                    [
                        'code' => -1,
                        'message' => '失败',
                    ],
                ],
            ]);

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
            'content' => 'content',
        ]);

        $this->assertRetErr($ret, '很抱歉,短信服务暂时不可以用,请稍后再试', -4);

        unset(wei()->sms->logger);
    }

    public function testMobileTimeLimit()
    {
        wei()->sms->setOption([
            'maxMobileTimes' => 1,
            'drivers' => ['logSms'],
        ]);

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
        ]);
        $this->assertRetSuc($ret);

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
        ]);
        $this->assertRetErr($ret, '很抱歉,您的操作太频繁了,请稍后再试', -2);
    }

    public function testIpTimeLimit()
    {
        wei()->sms->setOption([
            'maxIpTimes' => 1,
            'drivers' => ['logSms'],
        ]);

        wei()->sms->resetLimit('13800138001');

        $ret = wei()->sms->send([
            'mobile' => '13800138000',
        ]);
        $this->assertRetSuc($ret);

        $ret = wei()->sms->send([
            'mobile' => '13800138001',
        ]);
        $this->assertRetErr($ret, '很抱歉,您的操作太频繁了,请稍后再试', -3);
    }
}
