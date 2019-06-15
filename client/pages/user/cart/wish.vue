<template>
    <v-card>
        <v-card-title

                class="headline primary white--text font-weight-bold"
                primary-title
        >
           Your Order <v-spacer></v-spacer> <v-icon large dark>shopping_basket</v-icon>
        </v-card-title>

        <v-card-text>
            <v-list three-line>
                <template v-for="(item, index) in 5" >
                    <v-divider></v-divider>

                    <v-list-tile
                          :key="index"
                            avatar
                            @click=""
                    >
                        <v-list-tile-avatar>
                            <v-icon size="50" color="primary">explore</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>Lorem ipsum dolor set amit</v-list-tile-title>
                            <v-list-tile-sub-title>Price : 500</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
                <v-divider></v-divider>

            </v-list>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    color="red"
                    outline
            >
                Confirm
            </v-btn>
        </v-card-actions>
    </v-card>

    <!--<v-flex xs12>-->
        <!--<v-layout row wrap>-->
            <!--<v-flex xs12 md8>-->
                <!--<v-container grid-list-md pa-0 ma-0 style="min-height: 550px" fill-height>-->
                    <!--<v-layout row wrap ma-0 pa-0 align-start>-->
                        <!--<v-flex v-for="(item,index) in cart" :key="item.id" xs12 md6>-->
                            <!--<v-card>-->
                                <!--<v-img-->
                                        <!--:src="'/images/Munkacs_vara.jpg'"-->
                                        <!--aspect-ratio="1"-->
                                        <!--height="200"-->
                                        <!--class="grey lighten-2"-->
                                <!--&gt;-->
                                    <!--<v-layout-->
                                            <!--slot="placeholder"-->
                                            <!--fill-height-->
                                            <!--align-center-->
                                            <!--justify-center-->
                                            <!--ma-0-->
                                    <!--&gt;-->
                                        <!--<v-progress-circular indeterminate color="grey lighten-5"/>-->
                                    <!--</v-layout>-->

                                    <!--<v-card-actions>-->
                                        <!--<span class="white&#45;&#45;text amber darken-4 pa-1 subheading font-weight-bold">Price: &euro;255</span>-->
                                        <!--<v-spacer></v-spacer>-->
                                        <!--<v-btn dark color="indigo" small fab @click="removeItem(item.id)">-->
                                            <!--<v-icon medium>remove_shopping_cart</v-icon>-->
                                        <!--</v-btn>-->
                                    <!--</v-card-actions>-->
                                <!--</v-img>-->
                                <!--<v-card-text class="align-center text-xs-justify pa-2">-->
                                    <!--<h1 class="title">Lorem ipsum dolor set amit</h1>-->
                                    <!--<v-rating-->
                                            <!--color="blue darken-3"-->
                                            <!--readonly-->
                                            <!--background-color="grey darken-1"-->
                                            <!--v-model="rating"-->
                                    <!--&gt;</v-rating>-->
                                    <!--<h1 class="subheading font-weight-bold"><v-icon color="red lighteen-4">date_range</v-icon> 2019-05-11</h1>-->
                                <!--</v-card-text>-->
                                <!--<v-card-actions>-->
                                    <!--<v-btn icon class="purple&#45;&#45;text">-->
                                        <!--<v-icon medium>sb-instagram</v-icon>-->
                                    <!--</v-btn>-->
                                    <!--<v-btn icon class="light-blue&#45;&#45;text">-->
                                        <!--<v-icon medium>sb-twitter</v-icon>-->
                                    <!--</v-btn>-->
                                    <!--<v-btn icon class="blue&#45;&#45;text text&#45;&#45;darken-4">-->
                                        <!--<v-icon medium>sb-facebook</v-icon>-->
                                    <!--</v-btn>-->
                                    <!--<v-spacer/>-->
                                    <!--<v-btn flat class="blue&#45;&#45;text"-->
                                           <!--@click="readMore(item.slug)"-->
                                           <!--outline-->
                                    <!--&gt;Read More-->
                                    <!--</v-btn>-->
                                <!--</v-card-actions>-->

                            <!--</v-card>-->
                        <!--</v-flex>-->
                    <!--</v-layout>-->
                <!--</v-container>-->
            <!--</v-flex>-->

            <!--<v-flex xs12 md4>-->
                <!--<v-container fluid pa-0 ma-1 fill-height>-->
                    <!--<v-layout ma-0 pa-0>-->
                        <!--<v-flex xs12>-->
                            <!--<v-card class="pa-3">-->
                                <!--<v-flex xs12 class="text-xs-center pa-3">-->
                                    <!--<v-icon color="red lighteen-4" size="80">shopping_basket</v-icon>-->
                                <!--</v-flex>-->
                                <!--<v-flex xs12 class="text-xs-center">-->
                                    <!--<h1 class="headline font-weight-light pa-2">Your order</h1>-->
                                    <!--<h1 class="title font-weight-bold pa-1">Total Price : 2555 hrn</h1>-->
                                <!--</v-flex>-->
                                <!--<v-flex xs12 >-->
                                    <!--<v-btn block color="warning">Go To Checkout</v-btn>-->
                                <!--</v-flex>-->
                            <!--</v-card>-->
                        <!--</v-flex>-->
                    <!--</v-layout>-->
                <!--</v-container>-->
            <!--</v-flex>-->

        <!--</v-layout>-->
    <!--</v-flex>-->
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

                items: [

                ]
            }
        },
        mounted () {
            // this.$store.dispatch('shopping_cart/setItems', { items: this.cart_items })
        },
        methods: {
            remove(item) {
                console.log(item)
            },
            removeItem(index){

                console.log(index)
                this.$store.dispatch('shopping_cart/deleteItem', { items: index })
            },

        },
        computed:{
            cart(){
                return this.$store.state.shopping_cart.items
            }
        }

    }
</script>

<style scoped>

</style>
