/** When your routing table is too long, you can split it into small modules **/
//lpc 左侧菜单栏统一存放地，会用来做管理员能访问的权限操作
import Layout from '@/layout'

const menuRouter = [{
  path: '/setting',
  component: Layout,
  redirect: '/platform-info',
  name: '系统管理',
  meta: {
    title: '系统管理',
    icon: 'el-icon-setting'
  },
  children: [
    {
      path: '/platform-info',
      component: () => import('@/views/dashboard/index'),
      name: '平台设置',
      meta: {
        title: '平台设置',
        icon: 'el-icon-setting'
      }
    },
    {
      path: '/queue-list',
      component: () => import('@/views/queue/index'),
      name: '队列列表',
      meta: {
        title: '队列列表',
        icon: 'el-icon-s-unfold'
      }
    },
    {
      path: '/sms-list',
      component: () => import('@/views/sms/index'),
      name: '短信列表',
      meta: {
        title: '短信列表',
        icon: 'el-icon-message'
      }
    }

  ]
},

{
  path: '/menu',
  component: Layout,
  name: '菜单管理',
  redirect: '/menu-list',
  meta: {
    title: '菜单管理',
    icon: 'el-icon-menu'
  },
  children: [{
    path: '/menu-list',
    component: () => import('@/views/menu/index'),
    name: '菜单管理',
    meta: {
      title: '菜单管理',
      icon: 'el-icon-menu'
    }
  }]
},

{
  path: '/admin',
  component: Layout,
  name: '管理员管理',
  redirect: '/admin-list',
  meta: {
    title: '管理员管理',
    icon: 'el-icon-s-custom'
  },
  children: [{
    path: '/role-list',
    component: () => import('@/views/role/index'),
    name: '角色列表',
    meta: {
      title: '角色列表',
      icon: 'el-icon-user'
    }
  },
  {
    path: '/admin-list',
    component: () => import('@/views/admin/index'),
    name: '管理员列表',
    meta: {
      title: '管理员列表',
      icon: 'el-icon-s-custom'
    }
  }
  ]
},


  // 404 page must be placed at the end !!!
  // {
  //   path: '*',
  //   redirect: '/404',
  //   hidden: true
  // }
]
export default menuRouter
