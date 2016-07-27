<?php

namespace plugins\sms\services;

use plugins\sms\BaseSms;

/**
 * @property \services\Logger $logger
 */
class LogSms extends BaseSms
{
    /**
     * {@inheritdoc}
     */
    public function sendContentSms($mobile, $content)
    {
        $this->logger->info(get_defined_vars());
        return ['code' => 1, 'message' => '发送成功'];
    }

    /**
     * {@inheritdoc}
     */
    public function sendTplSms($mobile, $tplId, array $data)
    {
        $this->logger->info(get_defined_vars());
        return ['code' => 1, 'message' => '发送成功'];
    }
}
