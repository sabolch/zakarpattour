<template>
    <v-card>
        <v-card-title
                class="headline primary white--text font-weight-bold"
                primary-title
        >
            {{$t('navbar.sights')}}
            <v-spacer></v-spacer>
            <v-icon large dark>favorite</v-icon>
        </v-card-title>

        <v-card-text>
            <v-list three-line>
                <template v-for="(item, index) in items">
                    <v-divider></v-divider>

                    <v-list-tile
                            :key="index"
                            avatar
                    >
                        <v-list-tile-avatar>
                            <v-icon size="50" color="primary">location_on</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-bold">{{getTitle(item)}}</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon flat @click="removeItem(item)">
                                        <v-icon color="pink">delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>{{$t('btns.remove')}}</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                            v-on="on"
                                            icon
                                            flat
                                            :to="{name:'sight.show',params: {slug:item.slug}}"

                                    >
                                        <v-icon color="blue">remove_red_eye</v-icon>
                                    </v-btn>
                                </template>
                                <span>{{$t('hint.show_details')}}</span>
                            </v-tooltip>
                        </v-list-tile-action>
                    </v-list-tile>
                </template>
                <v-divider v-if="noItem"></v-divider>
                <v-list-tile
                        v-if="!noItem"
                >
                    <v-list-tile-content>
                        <v-list-tile-title class="text-xs-center grey--text">No items :(</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>
    </v-card>
</template>

<script>
    export default {
        name: "favoriteSights",
        head() {
            return {
                title: 'Shopping cart',
            }
        },
        data() {
            return {
                rating: 4,
                available: true,
                confirmModal: false,
                items: []
            }
        },
        async asyncData({params, store, $axios, redirect}) {
            try {
                let ids = store.state.favorite.items.markers.map(obj => {
                    return obj.marker_id
                })
                let {data} = await $axios.$post(`/marker/get`, {marker_ids: JSON.stringify(ids)})
                return {items: data}
            } catch (e) {
                redirect('/erorr')
            }
        },
        async mounted() {
            this.$axios.defaults.headers.common['Authorization'] = `Bearer ${this.$store.state.auth.token}`
        },
        methods: {
            async removeItem(item) {
                await this.$axios.$delete('favourite/marker', {data:{item_id: item.id}})
                await this.$store.dispatch('favorite/setFavourite')

                let ids = this.$store.state.favorite.items.markers.map(obj => {
                    return obj.marker_id
                })

                let {data} = await this.$axios.$post(`/marker/get`, {marker_ids: JSON.stringify(ids)})
                this.items = data
            },
            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            }
        },
        computed: {
            noItem() {
                return this.items.length > 0
            },
            getLocal() {
                return this.$i18n.locale
            },
        }
    }
</script>
<style scoped></style>
