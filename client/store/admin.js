import axios from 'axios'
import Cookies from 'js-cookie'

// state
export const state = () => ({
  admin: null,
  token: null
})

// getters
export const getters = {
  admin: state => state.admin,
  token: state => state.token,
  check: state => state.admin !== null
}

// mutations
export const mutations = {
  SET_TOKEN (state, token) {
    state.token = token
  },

  FETCH_ADMIN_SUCCESS (state, admin) {
    state.admin = admin
  },

  FETCH_ADMIN_FAILURE (state) {
    state.token = null
  },

  LOGOUT (state) {
    state.admin = null
    state.token = null
  },

  UPDATE_ADMIN (state, { admin }) {
    state.admin = admin
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, { token, remember }) {
    commit('SET_TOKEN', token)

    Cookies.set('token', token, { expires: remember ? 365 : null })
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/admin')

      commit('FETCH_ADMIN_SUCCESS', data)
    } catch (e) {
      Cookies.remove('token')

      commit('FETCH_ADMIN_FAILURE')
    }
  },

  updateUser ({ commit }, payload) {
    commit('UPDATE_ADMIN', payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/logout')
    } catch (e) { }

    Cookies.remove('token')

    commit('LOGOUT')
  },
}
