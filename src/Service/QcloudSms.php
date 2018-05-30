<?php

namespace Miaoxing\Sms\Service;

use Miaoxing\Sms\BaseSms;
use Qcloud\Sms\SmsSingleSender;
use Wei\RetTrait;

class QcloudSms extends BaseSms
{
    use RetTrait;

    protected $appId;

    protected $appKey;

    protected $sign = '';

    /**
     * {@inheritdoc}
     */
    public function sendContentSms($mobile, $content)
    {
        return ['code' => -1, 'message' => '不支持内容短信'];
    }

    /**
     * {@inheritdoc}
     */
    public function sendTplSms($mobile, $tplId, array $data)
    {
        $sender = new SmsSingleSender($this->appId, $this->appKey);
        $result = $sender->sendWithParam('86', $mobile, $tplId, $data, $this->sign);
        $res = json_decode($result, true);
        if ($res['result'] === 0) {
            return $this->suc($res);
        }

        return $this->err($res + ['message' => $res['errmsg']]);
    }
}
