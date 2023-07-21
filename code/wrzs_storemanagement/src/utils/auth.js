import Cookies from 'js-cookie'

const TokenKey = 'vue_admin_template_token'

export function getToken(TokenKey) {
  return Cookies.get(TokenKey ? TokenKey : 'vue_admin_template_token')
}

export function setToken(token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}
