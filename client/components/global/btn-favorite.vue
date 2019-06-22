<template>
    <v-btn
            :dark="dark"
            :color="color"
            small
            fab
            @click="addToFavorites"
    >
        <v-icon :color=" inFavotites ? 'amber darken-4' : ''">{{icon}}</v-icon>
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
                inFavotites: false,
            }
        },
        computed: mapGetters({
            user: 'auth/user'
        }),
        mounted() {
            if (this.$store.state.auth.user) {
                this.checkInFavorites()
            }
        },
        methods: {
            async addToFavorites() {
                // check authentication
                if (!this.$store.state.auth.user) {
                    this.$router.push({name: 'login'})
                    return
                }
                // set token to axios
                this.$axios.defaults.headers.common['Authorization'] = `Bearer ${this.$store.state.auth.token}`
                // if in favourites
                if (this.inFavotites) {
                    this.removeFromFavorites(this.favType)
                    return;
                }
                this.form = new Form({id: this.itemID})
                try {
                    // add to favorites
                    const {data} = await this.form.put(`favourite/add/${this.favType}`)
                    await this.$store.dispatch('favorite/setFavourite')
                    this.checkInFavorites()
                } catch (e) {
                    console.log(e)
                }
            },
            async removeFromFavorites(type){
                console.log(this.favType)

                await this.$axios.$delete(`favourite/${type}`, {data:{item_id: this.itemID}})
                await this.$store.dispatch('favorite/setFavourite')
                this.checkInFavorites()
            },

            checkInFavorites(){
                if (this.type === 'tour') {
                    this.inFavotites = this.$store.state.favorite.items.tours.find(function (item) {
                        return item.tour_id === this.itemID;
                    }, this);
                } else {
                    this.inFavotites = this.$store.state.favorite.items.markers.find(function (item) {
                        return item.marker_id === this.itemID;
                    }, this);
                }
            }

        },
        computed:{
            favType(){
                return this.type === 'tour' ? 'tour' : 'marker'
            }
        }
    }
</script>
<style scoped></style>
