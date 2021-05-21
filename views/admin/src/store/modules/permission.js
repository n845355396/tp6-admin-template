import {
  asyncRoutes,
  constantRoutes
} from '@/router'

import {
  menuList
} from '@/api/menu'

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role))
  } else {
    return true
  }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, data) {
  const res = []

  routes.forEach(route => {
    const tmp = {
      ...route
    }
    if (data[tmp.path]) {
      tmp['sort'] = data[tmp.path]['sort'];
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, data[tmp.path]['child_list'])
        //拍个序？
        tmp.children = tmp.children.sort(compareSort('sort'));
      }
      res.push(tmp)
    }

  })
  return res
}

/**
 * 根据对象中的某个属性值来进行排序
 * @param {*} property  属性值
 * @param {*} type      排序方式 down：降序；默认升序
 */
function compareSort(property, type) {
  return function(a, b) {
    let value1 = a[property];
    let value2 = b[property];
    let result = type === 'down' ? value2 - value1 : value1 - value2;

    return result;
  };
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({
    commit
  }, roles) {
    return new Promise((resolve, reject) => {
      // let accessedRoutes
      // if (roles.includes('admin')) {
      //   accessedRoutes = asyncRoutes || []
      // } else {
      //   accessedRoutes = filterAsyncRoutes(asyncRoutes, roles)
      // }

      let accessedRoutes;
      menuList().then(response => {
        const {
          data
        } = response

        accessedRoutes = filterAsyncRoutes(asyncRoutes, data)
        //排个序
        accessedRoutes = accessedRoutes.sort(compareSort('sort'));

        commit('SET_ROUTES', accessedRoutes)
        resolve(accessedRoutes)
      }).catch(error => {
        reject(error)
      })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
