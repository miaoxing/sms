/**
 * @share [id]/edit
 */
import {CListBtn} from '@mxjs/a-clink';
import {Page, PageActions} from '@mxjs/a-page';
import {Form, FormItem, FormAction} from '@mxjs/a-form';

const New = () => {
  return (
    <Page>
      <PageActions>
        <CListBtn/>
      </PageActions>

      <Form>
        <FormItem label="名称" name="name" required/>

        <FormItem label="标识" name="code" required/>

        <FormItem label="模板编号" name="templateId" required/>

        <FormItem label="备注" name="remark" type="textarea"/>

        <FormItem name="id" type="hidden"/>

        <FormAction/>
      </Form>
    </Page>
  );
};

export default New;
