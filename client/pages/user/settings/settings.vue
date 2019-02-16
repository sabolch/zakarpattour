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
                                        <img src="https://randomuser.me/api/portraits/men/85.jpg">
                                    </v-list-tile-avatar>

                                    <v-list-tile-content>
                                        <v-list-tile-title class="title font-weight-light">username</v-list-tile-title>
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
                                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
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
        name: "settings",
        data() {
            return {
                drawer: true,
                // mini: false,
                items: [
                    {title: 'Profile', icon: 'account_circle', route: 'settings.profile'},
                    {title: 'Password', icon: 'vpn_key', route: 'settings.password'},
                ],
                right: null
            }
        },
        mounted() {},
        methods: {
            setMinify () {
                this.$store.commit('user_navbar_mini')
            }
        },
        watch: {
            'window.innerWidth':function (value) {

            }
        }
    }
</script>

<style scoped>
    .active-cart-route {
        border-left: 2px solid red;
    }
</style>
