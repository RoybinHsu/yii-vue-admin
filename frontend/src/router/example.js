import Layout from '@/layout'

export default [
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
        meta: { title: '样例表格', icon: 'table', noCache: false, text: '1312355' },
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
  }
]
