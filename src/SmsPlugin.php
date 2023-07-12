<?php

namespace Miaoxing\Sms;

use Miaoxing\Admin\Service\AdminMenu;

class SmsPlugin extends \Miaoxing\Plugin\BasePlugin
{
    protected $name = '短信通知';

    public function onAdminMenuGetMenus(AdminMenu $menu)
    {
        $setting = $menu->getChild('setting');
        $templates = $setting->addChild()->setLabel('短信模板管理')->setUrl('admin/sms-templates');
        $templates->addChild()->setUrl('admin/sms-templates/new')->setLabel('添加');
        $templates->addChild()->setUrl('admin/sms-templates/[id]/edit')->setLabel('编辑');
    }
}
