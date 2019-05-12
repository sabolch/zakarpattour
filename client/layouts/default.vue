<template>
    <v-app id="inspire" :dark="isDarkTheme" v-scroll="onScroll">
        <v-navigation-drawer
                :clipped="$vuetify.breakpoint.lgAndUp"
                v-model="drawer"
                app
                :disable-resize-watcher="true"
        >
            <v-list dense>
                <v-subheader>
                    Pages
                </v-subheader>
                <v-divider/>
                <template>
                    <v-list-tile v-for="item in items" :key="item.text" exact :to="{name: `${item.href}`}" >
                        <v-list-tile-action  >
                            <v-icon class="icon-fix" medium>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ $t(item.text) }}
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
                <v-toolbar-side-icon class="hidden-md-and-up" @click.stop="drawer = !drawer"/>
                <router-link :to="{name: 'welcome'}">
                    <span class="hidden-sm-and-down">{{ appName }}</span>
                </router-link>
            </v-toolbar-title>
            <v-toolbar-items class="hidden-sm-and-down" >
                <v-btn v-for="(item,key) in items" :key="key" :to="{name: `${item.href}`}" exact flat> {{ $t(item.text) }}</v-btn>
            </v-toolbar-items>
            <v-spacer/>
            <v-btn icon :to="{name: 'user.favorites'}">
                <v-icon medium>favorite</v-icon>
            </v-btn>
            <v-btn icon :to="{name: 'cart.cart'}">
                <v-icon medium>shopping_cart</v-icon>
            </v-btn>

            <v-menu
                    bottom
                    origin="center center"
                    transition="slide-y-transition"
                    offset-y
            >
                <v-btn slot="activator" icon>
                    <v-icon class="flag-language flag-icon ">{{ `flag-icon-${getLang}` }}</v-icon>
                </v-btn>

                <v-list>
                    <v-list-tile
                            v-for="(item, i) in language"
                            :key="i"
                            @click="changeLanguage(item)"
                    >
                        <v-list-tile-title>
                            <v-icon class="flag-icon ">{{ `flag-icon-${item}` }}</v-icon>

                            {{item.toUpperCase() }}
                        </v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-btn icon :to="{name: 'settings.profile'}">
                <v-icon medium>person</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <nuxt/>
        </v-content>
        <!--scroll btn-->
        <v-fab-transition>
            <v-btn
                    v-show="scroll"
                    fab
                    bottom
                    right
                    color="pink"
                    dark
                    fixed
                    @click="$vuetify.goTo(target, options)"
            >
                <v-icon medium>keyboard_arrow_up</v-icon>
            </v-btn>
        </v-fab-transition>
    </v-app>
</template>
<script>
    import {mapGetters} from 'vuex'
    import {loadMessages} from '~/plugins/i18n'

    export default {
        name: 'FrontPage',
        middleware:'user-route',
        data: () => ({
            appName: process.env.appName,
            isDarkTheme: false,

            scroll: false,
            offsetTop: 0,

            fav: true,
            menu: false,
            message: false,
            hints: true,

            language: ['en', 'hu', 'ua'],

            dialog: false,
            drawer: false,
            items: [
                {icon: 'home', text:'navbar.home',href:'welcome'},
                {icon: 'place', text: 'navbar.sights',href:'sights'},
                {icon: 'timeline', text: 'navbar.tours',href:'tours'},
                {icon: 'info', text: 'navbar.about',href:'about'},
                {icon: 'contact_mail', text: 'navbar.contact',href:'contact'}
            ]

        }),
        mounted() {},
        watch: {
            offsetTop: function (value) {
                if (value > 500) {
                    this.scroll = true;
                    return;
                }
                this.scroll = false;
            }
        },
        methods: {
            eventHandler(name) {
            },
            setLocale(locale) {
                if (this.$i18n.locale !== locale) {
                    loadMessages(locale)
                    this.$store.dispatch('lang/setLocale', {locale})
                }
            },
            changeLanguage(lang) {
                switch (lang) {
                    case 'en':
                        this.setLocale('en')
                        break
                    case 'ua':
                        this.setLocale('uk')
                        break
                    case 'hu':
                        this.setLocale('hu')
                        break
                }
            },
            onScroll(e) {
                this.offsetTop = window.pageYOffset || document.documentElement.scrollTop
            }
        },

        computed: {
            target() {
                return 0
            },
            getLang() {
              switch (this.$i18n.locale) {
                  case 'hu':
                      return 'hu'
                  case  'uk':
                      return 'ua'
                  default:
                      return 'en'
              }
            },
            options() {
                return {
                    duration: 800,
                    offset: 0,
                    easing: 'easeInQuint'
                }
            }
        }
    }
</script>
<style lang="css">

    .icon-fix {
        width: 30px;
    }

    .container {
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

    .v-chip__content {
        cursor: pointer !important;
    }

    .v-dialog {
        text-align: center;
        box-shadow: none;
    }
    .flag-language{
        font-size: 30px;
    }
</style>
