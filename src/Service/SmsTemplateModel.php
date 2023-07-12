<?php

namespace Miaoxing\Sms\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;
use Miaoxing\Plugin\Model\ReqQueryTrait;
use Miaoxing\Plugin\Model\SnowflakeTrait;
use Miaoxing\Sms\Metadata\SmsTemplateTrait;
use Wei\Model\SoftDeleteTrait;

class SmsTemplateModel extends BaseModel
{
    use ModelTrait;
    use ReqQueryTrait;
    use SmsTemplateTrait;
    use SnowflakeTrait;
    use SoftDeleteTrait;
}
