<?php

namespace Miaoxing\Sms\Migration;

use Wei\Migration\BaseMigration;

class V20230708234614CreateSmsTemplatesTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('sms_templates')->tableComment('短信模板管理')
            ->bigId()
            ->string('name')->comment('名称')
            ->string('code')->comment('标识')
            ->string('template_id')->comment('模板编号')
            ->string('remark')->comment('备注')
            ->timestamps()
            ->userstamps()
            ->softDeletable()
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('sms_templates');
    }
}
