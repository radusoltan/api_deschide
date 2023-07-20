import {Button, Layout, Select, Space} from "antd"
import React, {useEffect, useState} from "react"
import {MenuFoldOutlined, MenuUnfoldOutlined} from "@ant-design/icons"
import {Outlet} from "react-router-dom"
import {useDispatch} from "react-redux"
import {useGetUserDetailsQuery} from "../services/auth";
import {setCredentials} from "../features/auth/authSlice";

export const MainLayout = () => {
  const {data, isFetching} = useGetUserDetailsQuery('userDetails',{
    pollingInterval: 100000
  })
  const {Header, Sider, Content} = Layout
  const [collapsed, setCollapsed] = useState(false)
  const dispatch = useDispatch()

  useEffect(() => {
    if (data){
      dispatch(setCredentials(data))
    }
  }, [data, dispatch])

  const handleLogout = () => {
    console.log('logout')
  }
  const changeLang = () => {}

  return <Layout>
    <Sider
      trigger={null}
      collapsible
      collapsed={collapsed}
      breakpoint="lg"
    >

    </Sider>
    <Layout className="site-layout">
      <Header className="site-layout-background" style={{ padding: 0 }}>
        {React.createElement(
          collapsed ? MenuUnfoldOutlined : MenuFoldOutlined,
          {
          	className: 'trigger',
          	onClick: () => setCollapsed(!collapsed),
        	}
        )}
        <div className="header-buttons">
          <Space>
            <Button type="primary" onClick={handleLogout}>Logout</Button>
            <Select defaultValue="en" style={{ width: 120 }} onChange={changeLang}>
              <Select.Option value="ro">RO</Select.Option>
              <Select.Option value="en">EN</Select.Option>
              <Select.Option value="ru">RU</Select.Option>
            </Select>
          </Space>
        </div>
      </Header>
      <Content>
        <Outlet />
      </Content>
    </Layout>
  </Layout>
}
