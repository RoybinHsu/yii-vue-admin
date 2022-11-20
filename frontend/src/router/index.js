import Vue from 'vue'
import Router from 'vue-router'
import { formatBackendMenuList } from '@/utils'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/login/index'),
    hidden: true,
    meta: { title: '登录' },
    children: []
  },
  // {
  //   path: '/register',
  //   name: 'Register',
  //   component: () => import('@/views/login/register'),
  //   hidden: true,
  //  meta: {title: '注册'}
  // },
  {
    path: '/404',
    redirect: '',
    component: () => import('@/views/404'),
    hidden: true,
    meta: { title: '未找到页面' },
    children: []
  },
  {
    path: '/',
    component: Layout,
    name: 'Home',
    redirect: '/home',
    hidden: false,
    children: [
      {
        path: 'home',
        name: 'HomeIndex',
        redirect: '',
        hidden: false,
        component: () => import('@/views/home/index'),
        meta: { title: '首页', icon: 'el-icon-s-home', noCache: false, affix: true },
        children: []
      }
    ]
  },
  {
    path: '/site',
    name: 'Site',
    redirect: '/site/menu',
    hidden: false,
    component: Layout,
    meta: { title: '网站管理', icon: 'el-icon-s-management' },
    children: [
      {
        path: 'menu',
        api: '/user/menus',
        name: 'SiteMenu',
        redirect: '',
        hidden: false,
        component: () => import('@/views/site/menu'),
        meta: { title: '菜单管理', icon: 'el-icon-s-operation' },
        children: []
      }
    ]
  },
  {
    path: '/example',
    api: '',
    name: 'Example',
    redirect: '/site/menu',
    hidden: false,
    component: Layout,
    meta: { title: '样例模板', icon: 'el-icon-s-help' },
    children: [
      {
        path: 'table',
        name: 'ExampleTable',
        redirect: '',
        hidden: false,
        component: () => import('@/views/table/index'),
        meta: { title: '样例表格', icon: 'table', noCache: false },
        children: []
      },
      {
        path: 'tree',
        api: '/tree',
        name: 'ExampleTree',
        redirect: '',
        hidden: false,
        component: () => import('@/views/tree/index'),
        meta: { title: '样例树形', icon: 'tree', noCache: false },
        children: []
      }
    ]
  },
  {
    path: '/form',
    name: 'Form',
    redirect: '/form/index',
    hidden: false,
    component: Layout,
    meta: { title: '表单', icon: 'form' },
    children: [
      {
        path: 'index',
        name: 'FormIndex',
        redirect: '',
        hidden: false,
        component: () => import('@/views/form/index'),
        meta: { title: '表单提交', icon: 'form' },
        children: []
      }
    ]
  },
  // 404 page must be placed at the end !!!
  {
    path: '*',
    name: 'Any',
    redirect: '/404',
    hidden: true,
    children: []
  },
  // 404 page must be placed at the end !!!
  {
    path: '/:catchAll(.*)',
    name: 'CatchAll',
    redirect: '/404',
    hidden: true,
    children: []
  }
]
const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
