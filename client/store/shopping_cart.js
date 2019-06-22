import Cookies from 'js-cookie'

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
    SET_ITEMS (state, { items }) {
        state.items = items
    },
    PUSH_ITEM (state, { item }) {
        state.items.push(item)
    },
    UPDATE_ITEM (state, { index, item }) {
        state.items[index] = item
    },
    DELETE_ITEM: (state, index) => {
        state.items.splice(index.index, 1);
    }
}

// actions
export const actions = {
    setItems ({ commit }, { items }) {
        commit('SET_ITEMS', { items })
        Cookies.set('shopping_cart',items , { expires: 365 })
    },
    pushItem ({ commit,state }, { item }) {
        commit('PUSH_ITEM', { item })
        Cookies.set('shopping_cart',state.items , { expires: 365 })
    },
    deleteItem ({ commit,state }, { index }) {
        commit('DELETE_ITEM', { index })
        Cookies.set('shopping_cart',state.items , { expires: 365 })
    },

    updateItem ({ commit,state }, { index,item }) {
        commit('UPDATE_ITEM', { index,item })
        Cookies.set('shopping_cart',state.items , { expires: 365 })
    },
}
