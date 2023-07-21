import {Form, Input, Modal, Spin, Switch} from "antd";
import {useGetCategoryQuery} from "../../../services/category";
import i18n from "../../../i18n";

export const NewCategory = ({visible, onCancel, onCreate}) => {
  const [form] = Form.useForm()
  return <Modal
    open={visible}
    onOk={()=>{
      form
        .validateFields()
        .then(values=>{
          form.resetFields()
          onCreate(values)
        })
    }}
    onCancel={()=> {
      form.resetFields()
      onCancel()
    }}
  >
    <Form
      form={form}
      layout='vertical'
      name="new_category"
      initialValues={{
        in_menu: false
      }}
    >
      <Form.Item name='title' label="Title" rules={[{required: true, message:'Please insert the title!'}]}>
        <Input />
      </Form.Item>
      <Form.Item label="In Menu" name="in_menu" valuePropName="checked">
        <Switch defaultChecked={false} />
      </Form.Item>
    </Form>
  </Modal>
}

export const EditCategory = ({id, visible, onCancel, onEdit}) => {
  const {data, isLoading} = useGetCategoryQuery(id)
	const [form] = Form.useForm()

  if(isLoading) return <Spin/>

  const {translations, in_menu} = data

  return <Modal
    open={visible}
    onCancel={onCancel}
    onOk={()=>{
      form
        .validateFields()
        .then(values=>{
        	form.resetFields()
          const body = {
            lng: i18n.language,
            ...values
          }
        	onEdit({id, body})
      })
    }}
  >
    <Form
      form={form}
      layout='vertical'
      name="edit_category"
      initialValues={{
        in_menu,
        title: translations.find(({locale})=>locale===i18n.language) ? translations.find(({locale})=>locale===i18n.language).title : 'No translation'
      }}
    >
      <Form.Item
        label="Title"
        name="title"
        rules={[{ required: true, message: 'Please input title!' }]}
      ><Input /></Form.Item>
      <Form.Item label="In Menu" name="in_menu" valuePropName="checked">
        <Switch defaultChecked={in_menu} />
      </Form.Item>
    </Form>
  </Modal>

}
