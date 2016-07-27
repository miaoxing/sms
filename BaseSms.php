<?php

namespace plugins\sms;

abstract class BaseSms extends \miaoxing\plugin\BaseService
{
    /**
     * 发送内容短信
     *
     * @param string $mobile
     * @param string $content
     * @return array
     */
    abstract public function sendContentSms($mobile, $content);

    /**
     * 发送模板短信
     *
     * @param string $mobile
     * @param string $tplId
     * @param array $data
     * @return array
     */
    abstract public function sendTplSms($mobile, $tplId, array $data);
}
