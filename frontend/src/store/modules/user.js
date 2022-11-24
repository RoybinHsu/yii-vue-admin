import { login, logout, getInfo } from '@/api/user'
import { getToken, setToken, removeToken } from '@/utils/auth'
import { resetRouter } from '@/router'

const getDefaultState = () => {
  return {
    token: getToken(),
    name: '',
    avatar: '',
    phone: '',
    email: '',
    menus: [],
    reLogin: null
  }
}

const state = getDefaultState()

const mutations = {
  RESET_STATE: (state) => {
    Object.assign(state, getDefaultState())
  },
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  },
  SET_PHONE: (state, phone) => {
    state.phone = phone
  },
  SET_EMAIL: (state, email) => {
    state.email = email
  },
  SET_MENUS: (state, menus) => {
    state.menus = menus
  },
  RE_LOGIN: (state, data) => {
    state.reLogin = data
  }
}

const actions = {
  // user login
  login({ commit }, userInfo) {
    const { username, password, captcha } = userInfo
    return new Promise((resolve, reject) => {
      login({ username: username.trim(), password: password, captcha: captcha }).then(response => {
        const { data } = response
        commit('SET_TOKEN', data.token)
        setToken(data.token)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // get user info
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      getInfo(state.token).then(response => {
        const { data } = response
        if (!data) {
          return reject('Verification failed, please Login again.')
        }
        const { username, avatar, phone, email, menus } = data
        commit('SET_NAME', username)
        commit('SET_AVATAR', avatar)
        commit('SET_PHONE', phone)
        commit('SET_EMAIL', email)
        commit('SET_MENUS', menus)
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  },

  // user logout
  logout({ commit, state }) {
    return new Promise((resolve, reject) => {
      logout(state.token).then(() => {
        removeToken() // must remove  token  first
        resetRouter()
        commit('RESET_STATE')
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // remove token
  resetToken({ commit }) {
    return new Promise(resolve => {
      removeToken() // must remove  token  first
      commit('RESET_STATE')
      resolve()
    })
  },

  // 标记需要重新登录在登录也给出提示
  reLogin({ commit }, data) {
    commit('RE_LOGIN', data)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

