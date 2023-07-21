import React, { useState } from 'react'
import {
  AppstoreOutlined,
  ContainerOutlined,
  DesktopOutlined,
  MailOutlined,
  MenuFoldOutlined,
  MenuUnfoldOutlined,
  PieChartOutlined,
} from '@ant-design/icons'
import {getItem} from "../lib/navigationUtils"
import {useTranslation} from "react-i18next";
import {Link, NavLink} from "react-router-dom";
import {Menu} from "antd";

export const Navigation = () => {
  const {t} = useTranslation()

  const items = [
    getItem(<Link to='/'>{t('menu.dashboard')}</Link>,'dashboard', <PieChartOutlined />),
    getItem(t('menu.content.head'),'content',<></>,[
      getItem(<NavLink to="/content/categories">{t('menu.content.categories')}</NavLink>, 'content/categories'),
      getItem(<NavLink to="/content/articles">{t('menu.content.articles')}</NavLink>, 'content/articles'),
    ]),
    getItem(t('menu.management.nead'), 'management', <AppstoreOutlined />, [
      getItem(<NavLink to="/management/users">{t("menu.management.users")}</NavLink>, 'management/users'),
      getItem(<NavLink to="/management/roles">{t("menu.management.roles")}</NavLink>, 'management/roles'),
      getItem(<NavLink to="/management/permissions">{t("menu.management.permissions")}</NavLink>, 'management/permissions'),
    ]),
  ]

	return <Menu
    // defaultSelectedKeys={['dashboard']}
    mode="inline"
    theme="dark"
    items={items}
  />
}
