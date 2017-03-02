<?php

namespace Miaoxing\Sms\Service;

/**
 * @property \services\Logger $logger
 * @property \Wei\Validator\MobileCn $isMobileCn
 * @property \Wei\Request $request
 * @method   bool isMobileCn($input) Check if the input is valid Chinese mobile number
 */
class Sms extends \miaoxing\plugin\BaseService
{
    /**
     * 短信提供商
     *
     * 配置如['logSms', 'ucpaas']
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * 一个时间窗口的长度,单位是秒
     *
     * @var int
     */
    protected $timeWindow = 300;

    /**
     * 在一个时间窗口内,同个手机号最多操作的次数
     *
     * @var int
     */
    protected $maxMobileTimes = 5;

    /**
     * 在一个时间窗口内,同个IP最多操作次数
     *
     * @var int
     */
    protected $maxIpTimes = 20;

    /**
     * 发送短信
     *
     * @param array $options
     * @return array
     */
    public function send(array $options)
    {
        // 1. 合并参数
        $options += [
            'mobile' => '',
            'content' => '',
            'tplIds' => [],
            'data' => [],
        ];

        // 2. 检查手机号码格式是否正确
        if (!$this->isMobileCn($options['mobile'])) {
            return ['code' => -1, 'message' => $this->isMobileCn->getFirstMessage('手机号码')];
        }

        // 3. 增加手机号码频率限制
        $mobileKey = $this->getTimeKey($options['mobile']);
        $times = wei()->counter->incr($mobileKey);
        if ($times > $this->maxMobileTimes) {
            wei()->logger->alert('手机号码发送短信太频繁', $options);

            return ['code' => -2, 'message' => '很抱歉,您的操作太频繁了,请稍后再试'];
        }

        // 4. 增加IP频率限制
        $ipKey = $this->getIpKey();
        $times = wei()->counter->incr($ipKey);
        if ($times > $this->maxIpTimes) {
            wei()->logger->alert('用户发送短信太频繁', $options + ['ipKey' => $ipKey]);

            return ['code' => -3, 'message' => '很抱歉,您的操作太频繁了,请稍后再试'];
        }

        // 5. 逐个调用短信服务商的接口
        $errors = [];
        foreach ($this->drivers as $driver) {
            /** @var \Miaoxing\Sms\BaseSms $service */
            $service = $this->wei->get($driver);
            if (isset($options['tplIds'][$driver])) {
                $ret = $service->sendTplSms($options['mobile'], $options['tplIds'][$driver], $options['data']);
            } else {
                $ret = $service->sendContentSms($options['mobile'], $options['content']);
            }

            // 只要发送成功,立即返回
            if ($ret['code'] === 1) {
                return $ret;
            } else {
                $errors[] = $ret;
            }
        }

        // 6. 全部发送失败,告警
        $this->logger->alert('所有短信服务发送失败', ['errors' => $errors]);

        return ['code' => -4, 'message' => '很抱歉,短信服务暂时不可以用,请稍后再试'];
    }

    /**
     * 获取手机号码频率限制的标识
     *
     * @param string $mobile
     * @return string
     */
    protected function getMobileKey($mobile)
    {
        return $this->getTimeKey($mobile);
    }

    /**
     * 获取IP地址频率限制的标识
     *
     * @return string
     */
    protected function getIpKey()
    {
        return $this->getTimeKey($this->request->getIp());
    }

    /**
     * 获取频率限制的标识
     *
     * @param string $id
     * @return int
     */
    protected function getTimeKey($id)
    {
        return sprintf('sms-%s-' . (int) (time() / $this->timeWindow), $id);
    }

    /**
     * 重置指定手机的频率限制
     *
     * @param string $mobile
     * @return $this
     */
    public function resetLimit($mobile)
    {
        wei()->counter->remove($this->getMobileKey($mobile));
        wei()->counter->remove($this->getIpKey());

        return $this;
    }
}
