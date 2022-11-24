import Layout from '@/layout'

export default [
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
    path: '/login',
    name: 'Login',
    component: () => import('@/views/login/index'),
    hidden: true,
    meta: { title: '登录' },
    children: []
  }
  // ,{
  //   path: '/register',
  //   name: 'Register',
  //   component: () => import('@/views/login/register'),
  //   hidden: true,
  //  meta: {title: '注册'}
  // },
]
