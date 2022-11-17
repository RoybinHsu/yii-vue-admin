/**
 * Created by PanJiaChen on 16/11/18.
 */

/**
 * @param {string} path
 * @returns {Boolean}
 */
export function isExternal(path) {
  return /^(https?:|mailto:|tel:)/.test(path)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validUsername(str) {
  let reg = /^[a-zA-Z0-9_\u4e00-\u9fa5]{2,16}$/
  return reg.test(str)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validPhone(str) {
  let reg = /^1\d{10}$/
  return reg.test(str)
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validPassword(str) {
    if (str.length < 6 || str.length > 18) {
      return false
    } else {
      return true
    }
}

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validEmail(str) {
  let reg = /^[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/
  return reg.test(str)
}
