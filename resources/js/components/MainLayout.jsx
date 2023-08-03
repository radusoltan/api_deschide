import {Button, Layout, Select, Space} from "antd"
import React, {useEffect, useState} from "react"
import {MenuFoldOutlined, MenuUnfoldOutlined} from "@ant-design/icons"
import {Outlet} from "react-router-dom"
import {useDispatch} from "react-redux"
import {useGetUserDetailsQuery} from "../services/auth";
import {logout,setCredentials} from "../features/auth/authSlice"
import {userLogout} from "../features/auth/authActions"
export const MainLayout = () => {
  const {Header, Sider, Content} = Layout
  const [collapsed, setCollapsed] = useState(false)
  const dispatch = useDispatch()

  const {data, isFetching} = useGetUserDetailsQuery('userDetails',{
    pollingInterval: 900000,
  })

  useEffect(()=>{
    if (data) dispatch(setCredentials(data))
  },[data, dispatch])

  const handleLogout = () => {
    console.log('logout',dispatch(userLogout()))
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
