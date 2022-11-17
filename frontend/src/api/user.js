import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/user/get-token',
    method: 'post',
    data
  })
}

export function getInfo() {
  return request({
    url: '/user/info',
    method: 'get',
    params: {}
  })
}

/**
 * 注册一个新用户
 * @param data
 * @returns {AxiosPromise}
 */
export function registerUser(data) {
  return request({
    url: '/user/register',
    method: 'post',
    params: data
  })
}

export function logout() {
  return request({
    url: '/user/logout',
    method: 'post'
  })
}

export function getWechatLoginInfo() {
  return request({
    url: '/api/user/logout',
    method: 'post'
  })
}
