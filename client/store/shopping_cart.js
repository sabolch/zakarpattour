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
    DELETE_ITEM: (state, itemID) => {
        let index = state.items.findIndex(obj => obj.id === itemID);
        state.items.splice(index, 1);
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
    deleteItem ({ commit,state }, { itemID }) {
        commit('DELETE_ITEM', { itemID })

        Cookies.set('shopping_cart',state.items , { expires: 365 })
    },
}
