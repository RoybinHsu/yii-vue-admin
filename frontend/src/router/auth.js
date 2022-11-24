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
        path: 'menu',
        api: '/user/menus',
        name: 'SiteMenu',
        redirect: '',
        hidden: false,
        component: () => import('@/views/site/menu'),
        meta: { title: '菜单管理', icon: 'el-icon-s-operation' },
        children: []
      },
      {
        path: 'role',
        api: '/auth/role',
        name: 'AuthRole',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/role'),
        meta: { title: '角色管理', icon: 'el-icon-suitcase' },
        children: []
      },
      {
        path: 'permission',
        api: '/auth/permission',
        name: 'AuthPermission',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/permission'),
        meta: { title: '权限管理', icon: 'el-icon-lock' },
        children: []
      },
      {
        path: 'api',
        api: '/auth/api',
        name: 'AuthApi',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/api'),
        meta: { title: '路由管理', icon: 'el-icon-more-outline' },
        children: []
      },
      {
        path: 'user',
        api: '/auth/user',
        name: 'AuthUser',
        redirect: '',
        hidden: false,
        component: () => import('@/views/auth/user'),
        meta: { title: '用户管理', icon: 'el-icon-user' },
        children: []
      }
    ]
  }
]
