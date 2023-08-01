<?php

/**
 * @property    Miaoxing\Sms\Service\LogSms $logSms
 */
class LogSmsMixin
{
}

/**
 * @property    Miaoxing\Sms\Service\LogSms $logSms
 */
class LogSmsPropMixin
{
}

/**
 * @property    Miaoxing\Sms\Service\Sms $sms
 */
class SmsMixin
{
}

/**
 * @property    Miaoxing\Sms\Service\Sms $sms
 */
class SmsPropMixin
{
}

/**
 * @property    Miaoxing\Sms\Service\SmsTemplateModel $smsTemplateModel
 */
class SmsTemplateModelMixin
{
}

/**
 * @property    Miaoxing\Sms\Service\SmsTemplateModel $smsTemplateModel
 */
class SmsTemplateModelPropMixin
{
}

/**
 * @mixin LogSmsMixin
 * @mixin SmsMixin
 * @mixin SmsTemplateModelMixin
 */
class AutoCompletion
{
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion();
}
