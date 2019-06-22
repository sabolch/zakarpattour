<template>
    <v-card>
        <v-card-title
                class="headline primary white--text font-weight-bold"
                primary-title
        >
            Your Order
            <v-spacer></v-spacer>
            <v-icon large dark>shopping_basket</v-icon>
        </v-card-title>

        <v-card-text>
            <v-list three-line>
                <template v-for="(item, index) in cart">
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
                                        >Price ₴ {{item.price }}
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
                                            Person {{item.persons}}
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
                                            {{item.date}}
                                        </v-chip>
                                    </template>
                                    <span>Дата відправки</span>
                                </v-tooltip>

                            </v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-btn v-on="on" icon flat @click="removeItem(index)">
                                        <v-icon color="pink">remove_shopping_cart</v-icon>
                                    </v-btn>
                                </template>
                                <span>Видалити з кошика</span>
                            </v-tooltip>
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
                <v-divider v-if="noItemInCart"></v-divider>
                <v-list-tile
                        v-if="noItemInCart"
                >
                    <v-list-tile-content class="text-xs-center font-weight-bold">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-chip
                                        color="red"
                                        label
                                        outline
                                        v-on="on"
                                >Total Price ₴ {{ getTotalPrice }}
                                </v-chip>
                            </template>
                            <span>Ціна у грн</span>
                        </v-tooltip>

                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile
                        v-if="!noItemInCart"
                >
                    <v-list-tile-content>
                        <v-list-tile-title class="text-xs-center grey--text">No items :(</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    color="primary"
                    outline
                    :disabled="!noItemInCart"
                    @click="removeAll"
            >
                Clear all
            </v-btn>
            <v-btn
                    color="red"
                    outline
                    :disabled="!noItemInCart"
                    @click="confirmOrder"
            >
                Confirm
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "wish",
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
            }
        },
        mounted() {
            this.$axios.defaults.headers.common['Authorization'] = `Bearer ${this.$store.state.auth.token}`
        },
        methods: {
            removeAll() {
                this.$store.dispatch('shopping_cart/setItems', {items: []})
            },
            removeItem(index) {
                this.$store.dispatch('shopping_cart/deleteItem', {index: index})
            },
            async confirmOrder() {
                let self = this
                // console.log(this.$store.state.auth.user.id)
                await this.$axios.$post(`order/store`, {
                    total_price: this.getTotalPrice,
                    tours: this.$store.state.shopping_cart.items
                }).then(function (res) {
                    console.log(res)
                    self.$store.dispatch('shopping_cart/setItems', {items: []})
                    self.$router.push({name: 'cart.processing'})
                }).catch(function (e)  {
                    console.log(e)
                })
            },
            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            }
        },
        computed: {
            cart() {
                return this.$store.state.shopping_cart.items
            },
            noItemInCart() {
                return this.cart.length > 0
            },
            getLocal() {
                return this.$i18n.locale
            },
            getTotalPrice() {
                let total = 0
                for (let i = 0; i < this.cart.length; i++) {

                    total += parseInt(this.cart[i].price)
                }
                return total
            }
        }
    }
</script>
<style scoped></style>
