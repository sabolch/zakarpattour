<template>
    <v-btn
            :dark="dark"
            :color="color"
            small
            fab
            @click="addToFavorites"
    >
        <v-icon :color="icon_color">{{icon}}</v-icon>
    </v-btn>
</template>

<script>
    import Form from 'vform'
    import {mapGetters} from 'vuex'

    export default {
        name: "btn-favorite",
        props: {
            color: {
                type: String,
                default: 'indigo',
            },
            itemID: {
                type: Number,
                required: true,
            },
            dark: {
                type: Boolean,
                default: true,
            },
            icon: {
                type: String,
                default: 'favorite',
            },
            type: {
                type: String,
                required: true,
                validator: value => {
                    return ['sight', 'tour'].includes(value)
                }
            },
        },
        data() {
            return {
                icon_color: null,
                form: new Form({
                    id: -1,
                }),
            }
        },
        computed: mapGetters({
            user: 'auth/user'
        }),
        mounted(){
            console.log(this.$store.state.auth.token)
        },
        methods: {
            async addToFavorites() {
                if (!this.$store.state.auth.user) {
                    this.$router.push({name: 'login'})
                    return
                }

                this.form = new Form({id: this.itemID})
                let  url = 'favourite/add/marker'
                if (this.type === 'sight') {
                    url = 'favourite/add/tour'
                }
                try {
                    const {data} = await this.form.put(url)
                    console.log(data)
                } catch (e) {
                    alert(this.$t('errors.something'))
                }

            }
        }
    }
</script>

<style scoped>

</style>
