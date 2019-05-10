export default ({ store, redirect }) => {
    if (store.getters['admin/check']) {
        return redirect('/admin/dash')
    }
}
