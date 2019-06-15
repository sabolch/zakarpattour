<template>
    <v-container fill-height fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-container pa-2>
                    <v-layout row>
                        <v-card>
                            <v-navigation-drawer
                                    v-model="drawer"
                                    :mini-variant="$store.state.user_cart_mini"
                                    hide-overlay
                                    stateless
                            >
                                    <v-list class="pa-0">
                                        <v-list-tile v-if="$store.state.user_cart_mini" @click.stop="setMinify">
                                            <v-list-tile-action>
                                                <v-icon>chevron_right</v-icon>
                                            </v-list-tile-action>
                                        </v-list-tile>
                                        <v-list-tile avatar>
                                            <v-list-tile-avatar>
                                                <v-icon color="primary" size="32">favorite</v-icon>
                                            </v-list-tile-avatar>

                                            <v-list-tile-content>
                                                <v-list-tile-title class="title font-weight-light">Bucket list
                                                </v-list-tile-title>
                                            </v-list-tile-content>
                                            <v-list-tile-action>
                                                <v-btn
                                                        icon
                                                        @click.stop="setMinify"
                                                >
                                                    <v-icon>chevron_left</v-icon>
                                                </v-btn>
                                            </v-list-tile-action>
                                        </v-list-tile>
                                    </v-list>
                                <v-list class="pa-0" dense>
                                    <v-divider></v-divider>
                                    <v-list-tile
                                            v-for="item in fav_items"
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

                                <v-flex xs12 class="pl-1">
                                    <transition name="page" mode="out-in">
                                        <router-view/>
                                    </transition>
                                </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        middleware: 'auth',
        name: "cart",
        data() {
            return {
                drawer: true,
                mini:true,

                items: [
                    {title: 'Cart', icon: 'shopping_cart', route: 'cart.cart'},
                    {title: 'Processing', icon: 'loop', route: 'cart.processing'},
                    {title: 'Checked', icon: 'done_all', route: 'cart.checked'},
                    {title: 'History', icon: 'history', route: 'cart.history'}
                ],

                fav_items: [
                    {title: 'Sights', icon: 'location_on', route: 'user.favorite.sights'},
                    {title: 'Tours', icon: 'timeline', route: 'user.favorite.tours'},
                ],
                right: null,

            }
        },

        methods: {
            setMinify () {
                this.$store.commit('user_cart_mini')
            }
        }
    }
</script>

<style scoped>
    .active-cart-route {
        border-left: 2px solid red;
    }
    .page-enter-active, .page-leave-active {
        transition: opacity .5s;
    }
    .page-enter, .page-leave-to {
        opacity: 0;
    }
</style>
