import { LockOutlined, UserOutlined } from '@ant-design/icons'
import {Button, Checkbox, Form, Input, Spin} from 'antd'
import {useNavigate} from "react-router-dom";
import {useEffect, useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {userLogin} from "../features/auth/authActions"
export const Login = () => {
  const {loading, error, success} = useSelector(state => state.auth)
  const dispatch = useDispatch()
  const navigate = useNavigate()

  useEffect(()=>{

    if (error) {}

    if (localStorage.getItem('userInfo')) {
      navigate('/')
    }
    if (success) {
      navigate('/')
    }
  },[navigate, loading, success])
  if (loading) {
    return <Spin />
  }
  const onFinish = async ({email, password, remember}) => {
    dispatch(
      userLogin({email, password, remember})
    )
  }

  return <Form
    name="normal_login"
    className="login-form"
    initialValues={{
      remember: true,
    }}
    onFinish={onFinish}
  >
    <Form.Item
      name="email"
      rules={[
        {
          required: true,
          message: 'Please input your Email!',
        },
      ]}
    >
      <Input prefix={<UserOutlined className="site-form-item-icon" />} placeholder="email" />
    </Form.Item>
    <Form.Item
      name="password"
      rules={[
        {
          required: true,
          message: 'Please input your Password!',
        },
      ]}
    >
      <Input
        prefix={<LockOutlined className="site-form-item-icon" />}
        type="password"
        placeholder="Password"
      />
    </Form.Item>
    <Form.Item>
      <Form.Item name="remember" valuePropName="checked" noStyle>
        <Checkbox>Remember me</Checkbox>
      </Form.Item>
    </Form.Item>

    <Form.Item>
      <Button type="primary" htmlType="submit" className="login-form-button">
        Log in
      </Button>
    </Form.Item>
  </Form>
}
