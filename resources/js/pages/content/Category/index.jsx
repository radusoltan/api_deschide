import {Button, Card, notification, Pagination, Spin, Switch, Table} from "antd"
import {useState} from "react"
import {
  useAddCategoryMutation,
  useDeleteCategoryMutation,
  useGetCategoriesQuery,
  useUpdateCategoryMutation
} from "../../../services/category"
import i18n from "../../../i18n"
import {Link} from "react-router-dom"
import {DeleteOutlined, EditOutlined} from "@ant-design/icons"
import {EditCategory, NewCategory} from "./_forms"
import {values} from "../../../../../public/js/app";

export const Categories = () => {

  const [page, setPage] = useState(1)
  const {data,isLoading, isSuccess} = useGetCategoriesQuery(page)
  const [addCategory] = useAddCategoryMutation()
  const [updateCategory] = useUpdateCategoryMutation()
  const [deleteCategory] = useDeleteCategoryMutation()
  const [isEdit, setIsEdit] = useState(false)
  const [isNew, setIsNew] = useState(false)
  const [isTranslate, setIsTranslate] = useState(false)
  const [category, setCategory] = useState(null)

	const categories = data?.data.map(({id,in_menu,translations})=>{
    const translation = translations.find(({locale})=>locale === i18n.language)
    return translation ? {
      key: id,
      title: translation.title,
      in_menu
    } : {
      key: id,
      title: 'No translation',
      in_menu
    }
  })

  const columns = [
    {
      title: 'Title',
      dataIndex: 'title',
      key: 'title',
      render: (text,{key}) => (<Link to={`/content/categories/${key}`}>{text}</Link>)
    },
    {
      title: 'In menu',
      dataIndex: 'in_menu',
      key: 'in_menu',
      render: (text,{key, in_menu, title}) => (
        <Switch onChange={()=>{
          const body = {
            title: title,
            in_menu: !in_menu,
            lng: i18n.language
          }
          updateCategory({id: key, body})
        }} checked={in_menu}/>
      )
    },
    {
      title: '',
      render: ({key}) => (<>
        <Button
          type="danger"
          className='table-buttons'
          icon={<DeleteOutlined/>}
          onClick={()=>deleteCategory(key)}
        />
        <Button
          type='info'
          className="table-buttons"
          onClick={()=>{}}
        >Translate</Button >
        <Button
          type='warning'
          className="table-buttons"
          onClick={()=>{
            setIsEdit(true)
            setCategory(key)
          }}
          icon={<EditOutlined />}
        />
      </>)
    }
  ]

  const add = values => {
		// addCategory({...values, lng: i18n.language})
    // notification.success({
    //   message: 'Success',
    // })
    // setIsNew(false)
  }

  const edit = ({id, body}) => {
    updateCategory({id, body})
    notification.success({
      message: 'Success',
    })
    setIsEdit(false)

  }

  if (isLoading) <Spin />


  return <Card
    extra={
      <Button type="success" onClick={()=>{
				setIsNew(true)
      }}>
        Add
      </Button>
    }
  >
    <Table pagination={false} dataSource={categories} columns={columns}/>
    <Pagination
      total={data?.total}
      defaultCurrent={data?.current_page}
      onChange={page => setPage(page)}
    />
    {
      isEdit && <EditCategory visible={isEdit} onCancel={()=>{
        setIsEdit(false)
        setCategory(null)
      }} onEdit={edit} id={category} />
    }
    {
      isNew && <NewCategory visible={isNew} onCancel={()=>{setIsNew(false)}} onCreate={values=>add(values)} />
    }
  </Card>
}
