import axios from 'axios'
import Cookies from 'js-cookie'

// state
export const state = () => ({
    user: null,
    token: null,
    SxAr:null
})

// getters
export const getters = {
    user: state => state.user,
    token: state => state.token,
    check: state => state.user !== null,
    SxAr: state => state.SxAr
}

// mutations
export const mutations = {
    SET_TOKEN (state, token) {
        state.token = token
    },

    FETCH_USER_SUCCESS (state, user) {
        state.user = user
    },

    FETCH_ADMIN_ROUTE (state, route) {
        state.SxAr = route
    },

    FETCH_USER_FAILURE (state) {
        state.token = null
    },

    LOGOUT (state) {
        state.user = null
        state.token = null
    },

    UPDATE_USER (state, { user }) {
        state.user = user
    }
}

// actions
export const actions = {
    saveToken ({ commit, dispatch }, { token, remember }) {
        commit('SET_TOKEN', token)
        Cookies.set('admin_token', token, { expires: remember ? 365 : null })
    },

    async fetchUser ({ commit }) {
        try {
            const { data } = await axios.get('/admin/user')
            commit('FETCH_USER_SUCCESS', data)
        } catch (e) {
            Cookies.remove('admin_token')
            commit('FETCH_USER_FAILURE')
        }
    },

    updateUser ({ commit }, payload) {
        commit('UPDATE_USER', payload)
    },

    updateRoute ({ commit }, payload) {
        commit('FETCH_ADMIN_ROUTE', payload)
    },

    async logout ({ commit }) {
        try {
            await axios.post('/admin/logout')
        } catch (e) { }

        Cookies.remove('admin_token')
        commit('LOGOUT')
    },
}
