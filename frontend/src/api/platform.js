import request from '@/utils/request'

/**
 * 手动添加平台应用
 * @param data
 * @returns {AxiosPromise}
 */
export function addPlatformApp(data) {
  return request({
    url: '/platform/app/add',
    method: 'post',
    data
  })
}

/**
 * 获取平台创建的应用列表
 * @param data
 * @returns {AxiosPromise}
 */
export function getPlatformList(data) {
  return request({
    url: '/platform/app/list',
    method: 'get',
    data
  })
}

/**
 * 获取授权连接
 * @param data
 * @returns {AxiosPromise}
 */
export function getAuthorizeUrl(data) {
  return request({
    url: '/platform/app/authorize-url',
    method: 'get',
    data
  })
}

/**
 * 获取授权连接
 * @param data
 * @returns {AxiosPromise}
 */
export function getAuthorizeAccountList(data) {
  return request({
    url: '/platform/app/account',
    method: 'get',
    data
  })
}

