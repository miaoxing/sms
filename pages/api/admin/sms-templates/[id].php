<?php

use Miaoxing\Plugin\BaseController;
use Miaoxing\Services\Page\ItemTrait;
use Miaoxing\Services\Service\UpdateAction;
use Miaoxing\Sms\Service\SmsTemplateModel;
use Wei\V;

return new class () extends BaseController {
    use ItemTrait;

    public function patch()
    {
        return UpdateAction::new()
            ->validate(function (SmsTemplateModel $template, $req) {
                $v = V::defaultOptional()->defaultNotEmpty();
                $v->setModel($template);
                $v->modelColumn('name', '名称')->requiredIfNew();
                $v->modelColumn('code', '标识')->requiredIfNew()->notModelDup();
                $v->modelColumn('templateId', '模板编号')->requiredIfNew();
                $v->modelColumn('remark', '备注');
                return $v->check($req);
            })
            ->exec($this);
    }
};
