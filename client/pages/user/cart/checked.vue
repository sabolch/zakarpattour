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
                <template v-for="(item, index) in cart" >
                    <v-divider></v-divider>

                    <v-list-tile
                            :key="index"
                            avatar
                    >
                        <v-list-tile-avatar>
                            <v-icon size="50" color="primary">explore</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title class="font-weight-bold">{{getTitle(item)}}</v-list-tile-title>
                            <v-list-tile-sub-title>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-chip
                                                color="red"
                                                label
                                                outline
                                                small
                                                v-on="on"
                                        >Price ₴ {{item.price}}</v-chip>
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
                                            Person {{item.persons}}</v-chip>
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
                                            {{item.date}}</v-chip>
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
                                            :to="{name:'tour.show',params: {slug:item.slug}}"

                                    >
                                        <v-icon color="blue">remove_red_eye</v-icon>
                                    </v-btn>
                                </template>
                                <span>Показати деталі туру</span>
                            </v-tooltip>
                        </v-list-tile-action>
                    </v-list-tile>
                </template>
                <v-divider  v-if="noItemInCart"></v-divider>
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
        data() {
            return {
                rating: 4,
                available: true,
                confirmModal:false,
                items: []
            }
        },
        mounted () {
            // this.$store.dispatch('shopping_cart/setItems', { items: this.cart_items })
        },
        methods: {
            removeAll() {
                this.$store.dispatch('shopping_cart/setItems', { items: [] })
            },
            removeItem(index){
                this.$store.dispatch('shopping_cart/deleteItem', { items: index })
            },
            confirmOrder(){
                console.log(this.$store.state.auth.user.id)
            },
            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            }
        },
        computed:{
            cart(){
                return this.$store.state.shopping_cart.items
            },
            noItemInCart(){
                return this.cart.length > 0
            },
            getLocal(){
                return this.$i18n.locale
            },
        }

    }
</script>
<style scoped></style>
