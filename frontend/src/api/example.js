import request from '@/utils/request'

/**
 * 测试1
 * @returns {AxiosPromise}
 */
export function testIndex() {
  return request({
    url: '/test/index',
    method: 'get'
  })
}

/**
 * 测试2
 * @returns {AxiosPromise}
 */
export function testTest2() {
  return request({
    url: '/test/test2',
    method: 'get'
  })
}
