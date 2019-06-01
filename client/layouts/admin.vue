<template>
    <v-app id="sandbox"  :dark="dark" v-scroll="onScroll">
        <v-navigation-drawer
                v-model="primaryDrawer.model"
                :permanent="primaryDrawer.type === 'permanent'"
                :temporary="primaryDrawer.type === 'temporary'"
                :clipped="primaryDrawer.clipped"
                :floating="primaryDrawer.floating"
                :mini-variant="primaryDrawer.mini"
                absolute
                overflow
                hide-overlay
                app
        >
            <v-list class="pa-1">
                <v-list-tile v-if="primaryDrawer.mini" @click.stop="primaryDrawer.mini = !primaryDrawer.mini">
                    <v-list-tile-action>
                        <v-icon>chevron_right</v-icon>
                    </v-list-tile-action>
                </v-list-tile>

                <v-list-tile avatar tag="div">
                    <v-list-tile-avatar>
                        <img src="https://randomuser.me/api/portraits/men/85.jpg">
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>{{ getAdmin }}</v-list-tile-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                        <v-btn icon @click.stop="primaryDrawer.mini = !primaryDrawer.mini">
                            <v-icon>chevron_left</v-icon>
                        </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>

            <v-list dense>

                <template v-for="item in items">
                    <v-layout
                            row
                            v-if="item.heading"
                            align-center
                            :key="item.heading"
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
                                    {{ $t(item.text) }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>

                        <v-list-tile
                                v-for="(child, i) in item.children"
                                :key="i"
                                @click=""
                                :to="{name: child.href}"
                        >
                            <v-list-tile-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ $t(child.text) }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else @click="" :key="item.text" :to="{name: item.href}">
                        <v-list-tile-action >
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ $t(item.text) }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar :clipped-left="primaryDrawer.clipped" app dark absolute color="indigo">
            <v-toolbar-side-icon
                    v-if="primaryDrawer.type !== 'permanent'"
                    @click.stop="primaryDrawer.model = !primaryDrawer.model"
            ></v-toolbar-side-icon>
            <v-toolbar-title>ZakarpatTour</v-toolbar-title>

            <v-spacer></v-spacer>
            <v-btn icon @click="handleFullScreen()">
                <v-icon>fullscreen</v-icon>
            </v-btn>

            <v-menu offset-y origin="center center" class="elelvation-1" :nudge-bottom="14" transition="scale-transition">
                <v-btn icon flat slot="activator" @click="$router.push({name:'admin.profile'})">
                    <v-badge color="red" overlap>
                        <span slot="badge">3</span>
                        <v-icon medium>notifications</v-icon>
                    </v-badge>
                </v-btn>
                <v-card class="elevation-0" v-if="false">
                    <v-toolbar card dense color="transparent">
                        <v-toolbar-title><h4>Notification</h4></v-toolbar-title>
                    </v-toolbar>
                    <v-divider></v-divider>
                    <v-card-text class="pa-0">
                        <v-list two-line class="pa-0">
                            <template v-for="(item, index) in items">
                                <v-subheader v-if="item.header" :key="item.header">{{ item.header }}</v-subheader>
                                <v-divider v-else-if="item.divider" :key="index"></v-divider>
                                <v-list-tile :to="{name:'admin.marker.category'}" avatar v-else :key="item.title">
                                    <v-list-tile-avatar :color="item.color">
                                        <v-icon dark>{{item.icon}}</v-icon>
                                    </v-list-tile-avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-sub-title v-html="item.title"></v-list-tile-sub-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action class="caption">
                                        {{item.timeLabel}}
                                    </v-list-tile-action>
                                </v-list-tile>
                            </template>
                        </v-list>
                        <v-divider></v-divider>
                        <v-btn block flat class="ma-0">All</v-btn>
                        <v-divider></v-divider>
                    </v-card-text>
                </v-card>
            </v-menu>
            <v-menu offset-y origin="center center" :nudge-bottom="14" transition="scale-transition">
                <v-btn icon flat slot="activator">
                        <v-icon large>more_vert</v-icon>
                </v-btn>
                <v-list class="pa-0">
                    <v-list-tile  ripple="ripple" @click="$router.push({name:'admin.profile'})" rel="noopener" >
                        <v-list-tile-action>
                            <v-icon>account_circle</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{$t('profile')}}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile  ripple="ripple" @click="logout" rel="noopener" >
                        <v-list-tile-action>
                            <v-icon>logout</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ $t('logout') }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-menu>
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


        </v-toolbar>
        <v-content>
            <v-container fluid>
                <v-layout align-center justify-center>
                    <v-flex xs12>
                        <nuxt/>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
        <v-footer  app color="indigo" dark>
            <v-flex xs12 class="text-xs-center">
                   {{ new Date().getFullYear() }} &copy; {{$t('footer.title')}}
            </v-flex>
        </v-footer>
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
        middleware:['admin-route','admin'],

        data: () => ({
            fav: true,
            menu: false,
            message: false,
            hints: true,
            language: ['en', 'hu', 'ua'],
            dark: false,

            scroll:null,
            offsetTop:0,

            drawers: ['Default (no property)', 'Permanent', 'Temporary'],
            primaryDrawer: {
                model: null,
                type: 'default (no property)',
                clipped: true,
                floating: false,
                mini: false
            },

            items: [
                { text: 'navbar.dashboard', icon: 'dashboard',href:'admin.dash' },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'notifications_active',
                    text: 'navbar.notifications',
                    model: false,
                    children: [
                        { icon: 'markunread', text: 'navbar.contact', href:'admin.notifications.message' },
                    ]
                },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'people',
                    text: 'navbar.users',
                    model: false,
                    children: [
                        { icon: 'face', text: 'navbar.profile', href:'admin.profile' },
                        { icon: 'build', text: 'navbar.admins', href:'admin.admins' },
                        { icon: 'people', text: 'navbar.users', href:'admin.users' },
                    ]
                },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'location_city',
                    text: 'navbar.settlements',
                    model: false,
                    children: [
                        { icon: 'location_city', text: 'navbar.settlements', href:'admin.settlement' },
                    ]
                },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'location_on',
                    text: 'navbar.sights',
                    model: false,
                    children: [
                        { icon: 'category', text: 'navbar.categories', href:'admin.marker.category' },
                        { icon: 'add', text: 'navbar.create', href:'admin.marker' },
                        { icon: 'location_on', text: 'navbar.sights', href:'admin.marker.list' },
                    ]
                },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'timeline',
                    text: 'navbar.tours',
                    model: false,
                    children: [
                        { icon: 'category', text: 'navbar.categories', href:'admin.tour.category' },
                        { icon: 'add', text: 'navbar.create', href:'admin.tour' },
                        { icon: 'timeline', text: 'navbar.tours', href:'admin.tour.list' },
                    ]
                },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'pages',
                    text: 'navbar.pages',
                    model: false,
                    children: [
                        { icon: 'info', text: 'navbar.about', href:'admin.about' },
                    ]
                },
            ],


            mini: false,
            footer: {
                inset: false
            }
        }),
        watch: {
            offsetTop: function (value) {
                if (value > 400) {
                    this.scroll = true;
                    return;
                }
                this.scroll = false;
            },
        },
        methods:{
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
                        this.setLocale('ua')
                        break
                    case 'hu':
                        this.setLocale('hu')
                        break
                }
            },
            toggleFullScreen()  {
                let doc = window.document;
                let docEl = doc.documentElement;

                let requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
                let cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

                if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
                    requestFullScreen.call(docEl);
                }
                else {
                    cancelFullScreen.call(doc);
                }
            },
            onScroll(e) {
                this.offsetTop = window.pageYOffset || document.documentElement.scrollTop
            },
            handleFullScreen () {
                this.toggleFullScreen();
            },
            handleClick: (e) => {
                console.log(e);
            },
            async logout() {
                // Log out the user.
                await this.$store.dispatch('admin/logout')
                // Redirect to login.
                this.$router.push({name: 'admin.login'})
            }
        },
        computed: {
            target() {
                return 0
            },
            getAdmin(){
              return this.$store.state.admin.user.name
            },

            getLang() {
                return this.$i18n.locale
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

<style lang="scss">
    #sandbox{
        font-family: "Monospaced";
        font-weight: lighter;
        font-size: 16px;
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
</style>
