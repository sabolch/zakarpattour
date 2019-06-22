import Cookies from 'js-cookie'
import axios from "axios";

// state
export const state = () => ({
    items: []
})

// getters
export const getters = {
    items: state => state.items
}

// mutations
export const mutations = {
    SET_FAVORITES (state, { items }) {
        state.items = items
    }
}

// actions
export const actions = {
    async setFavourite({ commit }) {
        try {
            const { data } = await axios.post('favourite/get')
            commit('SET_FAVORITES', { items:data.data })
            Cookies.set('favorites',data.data , { expires: 365 })
        } catch (e) {
            console.log(e)
        }
    }
}
