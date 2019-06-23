<template>
    <v-container fill-height fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-container pa-2>
                    <v-layout row>
                        <v-card>
                            <v-navigation-drawer
                                    v-model="drawer"
                                    :mini-variant="$store.state.user_navbar_mini"
                                    hide-overlay
                                    temporary
                                    stateless
                            >
                                <v-list class="pa-1">
                                    <v-list-tile v-if="$store.state.user_navbar_mini" @click.stop="setMinify">
                                        <v-list-tile-action>
                                            <v-icon>chevron_right</v-icon>
                                        </v-list-tile-action>
                                    </v-list-tile>

                                    <v-list-tile avatar tag="div">
                                        <v-list-tile-avatar size="50">
                                            <v-img :src="$store.state.auth.user ? `${$store.state.auth.user.photo_url}/64x64` : '' ">
                                                <v-layout
                                                        slot="placeholder"
                                                        fill-height
                                                        align-center
                                                        justify-center
                                                        ma-0
                                                >
                                                    <v-progress-circular indeterminate color="blue lighten-5"></v-progress-circular>
                                                </v-layout>
                                            </v-img>
                                        </v-list-tile-avatar>

                                        <v-list-tile-content>
                                            <v-list-tile-title class="title font-weight-light">
                                                {{$store.state.auth.user ? $store.state.auth.user.name : ''}}
                                            </v-list-tile-title>
                                        </v-list-tile-content>

                                        <v-list-tile-action>
                                            <v-btn icon @click.stop="setMinify">
                                                <v-icon>chevron_left</v-icon>
                                            </v-btn>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                </v-list>

                                <v-list class="pt-0" dense>
                                    <v-divider light></v-divider>

                                    <v-list-tile
                                            v-for="item in items"
                                            :key="item.title"
                                            @click=""
                                            :to="{name:item.route}"
                                            :class="item.route === $route.name ? 'active-cart-route' : ''"
                                    >
                                        <v-list-tile-action>
                                            <v-icon>{{ item.icon }}</v-icon>
                                        </v-list-tile-action>

                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ $t(item.title )}}</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile
                                            :key="'Logout'"
                                            @click="logout"
                                    >
                                        <v-list-tile-action>
                                            <v-icon>logout</v-icon>
                                        </v-list-tile-action>

                                        <v-list-tile-content>
                                            <v-list-tile-title>{{$t('logout')}}</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-navigation-drawer>
                        </v-card>
                        <v-container fill-height grid-md-list pa-0 ml-1>
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
        middleware: 'auth',
        name: "settings",
        data() {
            return {
                drawer: true,
                // mini: false,
                items: [
                    {title: 'profile', icon: 'account_circle', route: 'settings.profile'},
                    {title: 'password', icon: 'vpn_key', route: 'settings.password'},
                ],
                right: null,

            }
        },
        mounted() {
        },
        methods: {
            setMinify() {
                this.$store.commit('user_navbar_mini')
            },
            async logout() {
                // Log out the user.
                await this.$store.dispatch('auth/logout')
                // Redirect to login.
                this.$router.push({name: 'login'})
            }
        },
        watch: {
            // 'window.innerWidth': function (value) {
            // }
        }
    }
</script>

<style scoped>
    .active-cart-route {
        border-left: 2px solid red;
    }
</style>
