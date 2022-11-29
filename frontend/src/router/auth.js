import Layout from '@/layout'

export default [
  {
    path: '/site',
    name: 'Site',
    redirect: '/site/menu',
    hidden: false,
    component: Layout,
    meta: { title: '网站管理', icon: 'el-icon-s-management' },
    children: [
      {
        path: 'auth/menu',
        api: '/user/menus',
        name: 'SiteAuthMenu',
        redirect: '',
        hidden: false,
        component: () => import('@/views/site/menu'),
        meta: { title: '菜单管理', icon: 'el-icon-s-operation' },
        children: []
      },
      {
        path: 'auth/role',
        api: '/auth/role/list',
        name: 'SiteAuthRole',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/role'),
        meta: { title: '角色管理', icon: 'el-icon-suitcase' },
        children: []
      },
      {
        path: 'auth/permission',
        api: '/auth/permission/list',
        name: 'SiteAuthPermission',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/permission'),
        meta: { title: '权限管理', icon: 'el-icon-lock' },
        children: []
      },
      {
        path: 'auth/assign/router',
        api: '/auth/permission/assign',
        name: 'SiteAuthAssignRouter',
        redirect: '',
        hidden: true,
        component: () => import('@/views/auth/assign'),
        meta: { title: '分配路由' },
        children: []
      },
      {
        path: 'auth/assign/role',
        api: '/auth/permission/assign',
        name: 'SiteAuthAssignRole',
        redirect: '',
        hidden: true,
        component: () => import('@/views/auth/assign'),
        meta: { title: '分配权限' },
        children: []
      },
      {
        path: 'auth/assign/user',
        api: '/auth/user/assign',
        name: 'SiteAuthAssignUser',
        redirect: '',
        hidden: true,
        component: () => import('@/views/auth/assign'),
        meta: { title: '分配角色' },
        children: []
      },
      {
        path: 'auth/route',
        api: '/auth/route/list',
        name: 'SiteAuthRoute',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/route'),
        meta: { title: '路由管理', icon: 'el-icon-more-outline' },
        children: []
      },
      {
        path: 'auth/user',
        api: '/auth/user/list',
        name: 'SiteAuthUser',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/user'),
        meta: { title: '用户管理', icon: 'el-icon-user' },
        children: []
      }
    ]
  }
]
