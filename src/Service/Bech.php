<?php

namespace Miaoxing\Sms\Service;

use Miaoxing\Sms\BaseSms;

/**
 * 来信码短信服务
 *
 * @link http://sms.bechtech.cn
 */
class Bech extends BaseSms
{
    protected $accessKey;

    protected $secretKey;

    protected $messages = [
        '01' => '发送成功',
        '02' => 'IP限制',
    ];

    /**
     * {@inheritdoc}
     */
    public function sendContentSms($mobile, $content)
    {
        // 1. 调用接口
        $http = wei()->http([
            'url' => 'http://sms.bechtech.cn/Api/send/data/json',
            'dataType' => 'json',
            'throwException' => false,
            'data' => [
                'accesskey' => $this->accessKey,
                'secretkey' => $this->secretKey,
                'mobile' => $mobile,
                'content' => urlencode($content)
            ]
        ]);

        // 2. 记录日志
        $res = $http->getResponse();
        $msg = '短信:' . $content . ' 结果:' . var_export($http->isSuccess(), true) . ' code: ' . var_export($res, true);
        wei()->logger->info($msg);

        // 3. 处理HTTP请求失败
        if (!$http->isSuccess()) {
            return ['code' => -1, 'message' => '很抱歉,系统繁忙,请稍后再试'];
        }

        // 4. 处理业务结果
        if ('01' == $res['result']) {
            return ['code' => 1, 'message' => '发送成功'];
        } else {
            return ['code' => -$res['result'], 'message' => $this->messages[$res['result']] ?: '系统繁忙'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendTplSms($mobile, $tplId, array $data)
    {
        return ['code' => -1, 'message' => '不支持模板短信'];
    }
}
