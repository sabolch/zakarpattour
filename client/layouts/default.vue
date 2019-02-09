<template>
    <v-app id="inspire" :dark="isDarkTheme">
        <v-navigation-drawer
                :clipped="$vuetify.breakpoint.lgAndUp"
                v-model="drawer"
                app
                :disable-resize-watcher="true"
        >
            <v-list dense>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon class="icon-fix" medium>home</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>
                            Home
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-divider/>
                <v-subheader>
                    Pages
                </v-subheader>
                <v-divider/>
                <template v-for="item in items">
                    <v-layout
                            v-if="item.heading"
                            :key="item.heading"
                            row
                            align-center
                    >
                        <v-flex xs6>
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-flex>
                        <v-flex xs6 class="text-xs-center">
                            <a href="#!" class="body-2 black--text">EDIT</a>
                        </v-flex>
                    </v-layout>
                    <v-list-group
                            v-else-if="item.children"
                            v-model="item.model"
                            :key="item.text"
                            :prepend-icon="item.model ? item.icon : item['icon-alt']"
                            append-icon=""
                    >
                        <v-list-tile slot="activator">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                                v-for="(child, i) in item.children"
                                :key="i"
                                @click=""
                        >
                            <v-list-tile-action v-if="child.icon">
                                <v-icon medium> {{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ child.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else :key="item.text" >
                        <v-list-tile-action>
                            <v-icon class="icon-fix" medium>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>

                <v-divider/>
                <v-subheader>
                    Recomended
                </v-subheader>
                <v-divider/>
                <div v-for="i in 10">
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-icon class="icon-fix" medium>label_important</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                Article {{ i }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </div>

            </v-list>
        </v-navigation-drawer>

        <v-toolbar
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                color="blue darken-3"
                dark
                app
                fixed
        >
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"/>
                <router-link :to="{name: 'welcome'}">
                    <span class="hidden-sm-and-down">{{ appName }}</span>
                </router-link>
            </v-toolbar-title>
            <v-toolbar-items class="hidden-sm-and-down">

                <v-btn  :to="{name: 'welcome'}" exact flat> Home</v-btn>
                <v-btn :to="{name: 'sights'}" exact flat>Sights</v-btn>
                <v-btn :to="{name: 'tours'}" flat>Tours</v-btn>
                <v-btn flat>About</v-btn>
                <v-btn flat>Contacts</v-btn>
            </v-toolbar-items>
            <v-spacer/>
                <v-btn icon :to="{name: 'login'}">
                    <v-icon medium>apps</v-icon>
                </v-btn>
                <v-btn icon :to="{name: 'register'}">
                    <v-icon medium>shopping_cart</v-icon>
                </v-btn>

            <v-menu
                    bottom
                    origin="center center"
                    transition="slide-y-transition"
                    offset-y
            >
                <v-btn slot="activator" icon>
                    <v-icon medium>translate</v-icon>
                </v-btn>

                <v-list>
                    <v-list-tile
                            v-for="(item, i) in language"
                            :key="i"
                            @click="changeLanguage(item)"
                    >
                        <v-list-tile-title><span :class="['flag-icon','flag-icon-'+ (item == 'en' ? 'gb' : item)]"/> {{
                            item.toUpperCase() }}
                        </v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
                <v-btn icon :to="{name: 'login'}">
                    <v-icon medium>person</v-icon>
                </v-btn>
        </v-toolbar>
        <v-content>

            <nuxt/>

        </v-content>
    </v-app>
</template>
import {mapGetters} from 'vuex'

<script>
    export default {
        name: 'FrontPage',
        data: () => ({
            appName: process.env.appName,
            isDarkTheme: false,

            fav: true,
            menu: false,
            message: false,
            hints: true,

            language: ['en', 'hu', 'ua'],

            dialog: false,
            drawer: false,
            items: [
                {icon: 'map', text: 'Map'},
                {icon: 'timeline', text: 'Routes'},
                {icon: 'place', text: 'Sights'},
                {icon: 'info', text: 'About'},
                {icon: 'help_outline', text: 'Help'}

            ]

        }),
        mounted() {
            // Vue.i18n.set(this.lang);
        },
        methods: {
            eventHandler(name) {
            },
            changeLanguage(lang) {

            }
        }
    }
</script>
<style lang="css">

    .icon-fix {
        width: 30px;
    }

    .container{
        width: auto;
    }
    a {
        text-decoration: none;
    }

    a:active {
        background-color: transparent;
        color: inherit;
    }

    a:visited {
        background-color: transparent;
        color: inherit;
    }
    .v-chip__content{
        cursor: pointer!important;
    }
    .v-dialog {
        text-align: center;
        box-shadow: none;
    }

</style>
