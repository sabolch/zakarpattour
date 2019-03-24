export default ({ store }) => {
    if(store.getters['admin/SxAr']){
        store.dispatch('admin/updateRoute',false)
    }
}
