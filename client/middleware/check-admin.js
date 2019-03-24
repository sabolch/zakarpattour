import axios from 'axios'

export default async ({ store, req }) => {
    const token = store.getters['admin/token']
    if (process.server) {
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        } else {
            delete axios.defaults.headers.common['Authorization']
        }
    }

    if (!store.getters['admin/check'] && token) {
        await store.dispatch('admin/fetchUser')
    }
}
