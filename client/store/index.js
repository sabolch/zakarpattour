import Cookies from 'js-cookie'
import { cookieFromRequest } from '~/utils'

export const actions = {

  nuxtServerInit ({ commit }, { req }) {
    const token = cookieFromRequest(req, 'token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = cookieFromRequest(req, 'locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }


  },

  nuxtClientInit ({ commit }) {
    const token = Cookies.get('token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }

    const locale = Cookies.get('locale')
    if (locale) {
      commit('lang/SET_LOCALE', { locale })
    }


  }
}

export const state = () => ({
  user_navbar_mini: false,
  user_cart_mini:false,
})

export const mutations = {
  user_navbar_mini (state) {
    state.user_navbar_mini = !state.user_navbar_mini
  },
  user_cart_mini (state) {
    state.user_cart_mini = !state.user_cart_mini
  }
}
