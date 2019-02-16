<template>
    <v-container fill-height fluid>
        <v-layout row wrap>

            <v-flex xs12>
                <v-container pa-2>
                    <v-layout row class="indigo">
                        <v-card>
                            <v-navigation-drawer
                                    v-model="drawer"
                                    permanent
                            >
                                <v-toolbar flat class="transparent">
                                    <v-list class="pa-0">
                                        <v-list-tile avatar>
                                            <v-list-tile-avatar>
                                                <v-icon color="primary" size="32">favorite</v-icon>
                                            </v-list-tile-avatar>

                                            <v-list-tile-content>
                                                <v-list-tile-title class="title font-weight-light">Bucket list
                                                </v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-toolbar>
                                <v-list class="pa-0" dense>
                                    <v-divider></v-divider>
                                    <v-list-tile
                                            :to="{name:'user.favorites'}"
                                            :class="'user.favorites' === $route.name ? 'active-cart-route' : ''"
                                    >
                                        <v-list-tile-action>
                                            <v-icon>favorite_border</v-icon>
                                        </v-list-tile-action>

                                        <v-list-tile-content>
                                            <v-list-tile-title>Favorites</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>

                                <v-divider></v-divider>
                                <v-toolbar flat class="transparent">
                                    <v-list class="pa-0">
                                        <v-list-tile avatar>
                                            <v-list-tile-avatar>
                                                <v-icon color="primary" size="32">shopping_cart</v-icon>
                                            </v-list-tile-avatar>

                                            <v-list-tile-content>
                                                <v-list-tile-title class="title font-weight-light">Shopping cart
                                                </v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-toolbar>

                                <v-list class="pa-0" dense>
                                    <v-divider></v-divider>
                                    <v-list-tile
                                            v-for="item in items"
                                            :key="item.title"
                                            :to="{name:item.route}"
                                            :class="item.route === $route.name ? 'active-cart-route' : ''"
                                    >
                                        <v-list-tile-action>
                                            <v-icon>{{ item.icon }}</v-icon>
                                        </v-list-tile-action>

                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-navigation-drawer>
                        </v-card>

                        <v-container fill-height grid-md-list>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <transition name="fade" mode="out-in">
                                        <router-view/>
                                    </transition>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "cart",
        data() {
            return {
                drawer: true,
                items: [
                    {title: 'Cart', icon: 'shopping_cart', route: 'cart.cart'},
                    {title: 'Processing', icon: 'loop', route: 'cart.processing'},
                    {title: 'Checked', icon: 'done_all', route: 'cart.checked'},
                    {title: 'History', icon: 'history', route: 'cart.history'}
                ],
                right: null
            }
        },
        mounted () {}
    }
</script>

<style scoped>
    .active-cart-route {
        border-left: 2px solid red;
    }
</style>
