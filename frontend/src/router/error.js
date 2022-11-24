export default [
  {
    path: '/404',
    redirect: '',
    component: () => import('@/views/404'),
    hidden: true,
    meta: { title: '未找到页面' },
    children: []
  },
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
