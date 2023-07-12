<?php

namespace Miaoxing\Sms\Service;

use Miaoxing\Sms\BaseSms;
use Wei\RetTrait;

/**
 * 云之讯短信服务
 *
 * @link http://www.ucpaas.com/
 */
class Ucpaas extends BaseSms
{
    use RetTrait;

    /**
     * 开发者账号ID。由32个英文字母和阿拉伯数字组成的开发者账号唯一标识符。
     *
     * @var string
     */
    protected $accountSid;

    /**
     * 开发者账号TOKEN
     *
     * @var string
     */
    protected $accountToken;

    /**
     * 应用ID
     *
     * @var string
     */
    protected $appId;

    /**
     * 官方类库对象
     *
     * @var \Ucpaas
     */
    protected $ucpaas;

    /**
     * {@inheritdoc}
     */
    public function sendContentSms($mobile, $text)
    {
        return ['code' => -1, 'message' => '不支持内容短信'];
    }

    /**
     * {@inheritdoc}
     */
    public function sendTplSms($mobile, $tplId, array $data)
    {
        $param = get_defined_vars();

        // 1. 调用官方类库发送
        $ucpaas = $this->getUcpaas([
            'accountsid' => $this->accountSid,
            'token' => $this->accountToken,
        ]);
        $response = $ucpaas->templateSMS($this->appId, $mobile, $tplId, implode(',', $data));
        $result = json_decode($response, true);

        // 2. 处理发送结果
        if (null != $result && '000000' == $result['resp']['respCode']) {
            return $this->suc('发送成功');
        }

        return $this->err([
            'message' => '发送失败',
            'param' => $param,
            'result' => $result,
            'response' => $response,
        ]);
    }

    /**
     * 获取官方类库对象
     *
     * @param array $options
     * @return \Ucpaas
     */
    public function getUcpaas(array $options = [])
    {
        if (!$this->ucpaas) {
            $this->ucpaas = new \Ucpaas($options);
        }

        return $this->ucpaas;
    }

    /**
     * 设置官方类库对象
     *
     * @param \Ucpaas $ucpaas
     * @return $this
     */
    public function setUcpaas(\Ucpaas $ucpaas = null)
    {
        $this->ucpaas = $ucpaas;

        return $this;
    }
}
