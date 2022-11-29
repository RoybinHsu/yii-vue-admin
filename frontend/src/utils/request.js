import axios from 'axios'
import { Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'
import qs from 'qs'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 30000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    if (config.method.toLowerCase() === 'post') {
      // post请求
      // eslint-disable-next-line
      if (config.requestJson === undefined || config.requestJson === true) {
        config.headers.post['Content-Type'] = 'application/json; charset=UTF-8'
        config.data = JSON.stringify(config.data)
      } else {
        config.data = qs.stringify(config.data)
      }
    } else {
      config.headers['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'
      // config.data = qs.stringify(config.data)
      config.params = config.data
    }
    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['Authorization'] = 'Bearer ' + getToken()
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
   */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data
    // if the custom code is not 20000, it is judged as an error.
    if (+res.code !== 200) {
      switch (+res.code) {
        case 401:
          store.dispatch('user/resetToken').then(() => {
            //window.location.reload()
          })
          break
        case 403:
          Message({
            message: res.message || 'Error',
            type: 'error',
            duration: 3000
          })
          break
        default:
          Message({
            message: res.message || 'Error',
            type: 'error',
            duration: 3000
          })
          break
      }
      // if (+res.code === 401) {
      //   // to re-login
      //   // MessageBox.alert('您当前登录状态已过期, 请重新登录', '登录过期', {
      //   //   confirmButtonText: '确认',
      //   //   cancelButtonText: '取消',
      //   //   type: 'warning'
      //   // }).then(() => {
      //   // }).finally(() => {
      //   //   store.dispatch('user/resetToken').then(() => {
      //   //     window.location.reload()
      //   //   })
      //   // })
      //   store.dispatch('user/resetToken').then(() => {
      //     window.location.reload()
      //   })
      // } else {
      //   Message({
      //     message: res.message || 'Error',
      //     type: 'error',
      //     duration: 3000
      //   })
      // }
      return Promise.reject(new Error(res.message || 'Error'))
    } else {
      return res
    }
  },
  error => {
    console.log('err' + error) // for debug
    Message({
      message: error.message,
      type: 'error',
      duration: 3000
    })
    return Promise.reject(error)
  }
)

export default service
