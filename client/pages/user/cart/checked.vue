<template>
    <v-card>
        <v-card-title
                class="headline primary white--text font-weight-bold"
                primary-title
        >
            Checked order <v-spacer></v-spacer> <v-icon large dark>done_all</v-icon>
        </v-card-title>

        <v-card-text>
            <v-list three-line>
                <v-list-group
                        v-for="(item, index) in items.data"
                        :key="index"
                        no-action
                >
                    <template v-slot:activator>
                        <v-list-tile  avatar>
                            <v-list-tile-avatar>
                                <v-icon size="50" color="primary">add_shopping_cart</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title class="font-weight-bold">Cart item</v-list-tile-title>
                                <v-list-tile-sub-title>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-chip
                                                    color="red"
                                                    label
                                                    outline
                                                    small
                                                    v-on="on"
                                            >Total price ₴ {{item.total_price }}
                                            </v-chip>
                                        </template>
                                        <span>Ціна у грн</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-chip
                                                    color="purple"
                                                    label
                                                    outline
                                                    small
                                                    v-on="on"
                                            >
                                                <v-icon small left>event</v-icon>
                                                {{item.updated_at}}
                                            </v-chip>
                                        </template>
                                        <span>Дата відправки</span>
                                    </v-tooltip>
                                </v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </template>

                    <v-list-tile
                            v-for="subItem in item.orders"
                            :key="subItem.id"
                            avatar
                    >
                        <v-list-tile-avatar>
                            <v-icon size="50" color="primary">explore</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-bold">{{getTitle(subItem.tour)}}</v-list-tile-title>
                            <v-list-tile-sub-title>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-chip
                                                color="red"
                                                label
                                                outline
                                                small
                                                v-on="on"
                                        >Price ₴ {{subItem.price }}
                                        </v-chip>
                                    </template>
                                    <span>Ціна у грн</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-chip
                                                color="blue"
                                                label
                                                outline
                                                small
                                                v-on="on"
                                        >
                                            <v-icon small left>person</v-icon>
                                            Person {{subItem.persons}}
                                        </v-chip>
                                    </template>
                                    <span>Кількість осіб</span>
                                </v-tooltip>

                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-chip
                                                color="purple"
                                                label
                                                outline
                                                small
                                                v-on="on"
                                        >
                                            <v-icon small left>event</v-icon>
                                            {{subItem.date}}
                                        </v-chip>
                                    </template>
                                    <span>Дата відправки</span>
                                </v-tooltip>
                            </v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn
                                            v-on="on"
                                            icon
                                            flat
                                            :to="{name:'tour.show',params: {slug:subItem.tour.slug}}"
                                    >
                                        <v-icon color="blue">remove_red_eye</v-icon>
                                    </v-btn>
                                </template>
                                <span>Показати деталі туру</span>
                            </v-tooltip>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-group>
                <v-list-tile
                        v-if="!noItemInCart"
                >
                    <v-list-tile-content>
                        <v-list-tile-title class="text-xs-center grey--text">No items :(</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-card-text>

    </v-card>
</template>

<script>
    export default {
        name: "checked",
        head() {
            return {
                title: 'Shopping cart',
            }
        },

        async asyncData({params, $axios, redirect}) {
            try {
                let data = await $axios.$post(`/order/user`,{status:'checked'})
                return {items: data}
            } catch (e) {
                console.log(e)
                // redirect('/erorr')
            }
        },

        data() {
            return {
                rating: 4,
                available: true,
                confirmModal:false,
                items: []
            }
        },
        mounted () {
            console.log(this.items)
        },
        methods: {

            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            }
        },
        computed:{

            getLocal(){
                return this.$i18n.locale
            },
            noItemInCart(){
                return this.items.data.length > 0
            }
        }

    }
</script>
<style scoped>
</style>
