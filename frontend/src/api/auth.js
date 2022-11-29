import request from '@/utils/request'

/**
 * 创建用户
 * @returns {AxiosPromise}
 */
export function addUser(data) {
  return request({
    url: '/auth/user/add',
    method: 'post',
    data
  })
}

/**
 * 创建用户
 * @returns {AxiosPromise}
 */
export function userList(data) {
  return request({
    url: '/auth/user/list',
    method: 'get',
    data
  })
}

/**
 * 获取路由api列表
 * @returns {AxiosPromise}
 */
export function routeIndex() {
  return request({
    url: '/auth/route/index',
    method: 'get'
  })
}

/**
 * 添加路由
 * @returns {AxiosPromise}
 */
export function routeCreate(data) {
  return request({
    url: '/auth/route/create',
    method: 'post',
    data
  })
}

/**
 * 移除路由
 * @returns {AxiosPromise}
 */
export function routeRemove(data) {
  return request({
    url: '/auth/route/remove',
    method: 'post',
    data
  })
}

/**
 * 移除路由
 * @returns {AxiosPromise}
 */
export function routeFresh() {
  return request({
    url: '/auth/route/fresh-cache',
    method: 'get'
  })
}

/**
 * 获取权限列表
 * @returns {AxiosPromise}
 */
export function permissionIndex(data) {
  return request({
    url: '/auth/permission/index',
    method: 'get',
    data
  })
}

/**
 * 获取权限列表
 * @returns {AxiosPromise}
 */
export function permissionAdd(data) {
  return request({
    url: '/auth/permission/create',
    method: 'post',
    data
  })
}

/**
 * 获取权限列表
 * @returns {AxiosPromise}
 */
export function permissionDel(data) {
  return request({
    url: '/auth/permission/delete',
    method: 'get',
    data
  })
}

/**
 * 分配路由
 * @returns {AxiosPromise}
 */
export function assignIndex(type, data) {
  return request({
    url: `/auth/${type}/view`,
    method: 'get',
    data
  })
}

/**
 * 分配路由
 * @returns {AxiosPromise}
 */
export function assignAssign(type, data) {
  return request({
    url: `/auth/${type}/assign`,
    method: 'post',
    data
  })
}

/**
 * 分配路由
 * @returns {AxiosPromise}
 */
export function assignRemove(type, data) {
  return request({
    url: `/auth/${type}/remove`,
    method: 'post',
    data
  })
}

/**
 * 获取权限列表
 * @returns {AxiosPromise}
 */
export function roleIndex(data) {
  return request({
    url: '/auth/role/index',
    method: 'get',
    data
  })
}

/**
 * 创建角色
 * @returns {AxiosPromise}
 */
export function roleAdd(data) {
  return request({
    url: '/auth/role/create',
    method: 'post',
    data
  })
}

/**
 * 创建角色
 * @returns {AxiosPromise}
 */
export function roleDel(data) {
  return request({
    url: '/auth/role/delete',
    method: 'get',
    data
  })
}
