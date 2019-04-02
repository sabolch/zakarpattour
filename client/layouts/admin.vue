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
            <v-list class="pa-1 indigo darken-2" dark >
                <v-list-tile v-if="primaryDrawer.mini" @click.stop="primaryDrawer.mini = !primaryDrawer.mini">
                    <v-list-tile-action>
                        <v-icon>chevron_right</v-icon>
                    </v-list-tile-action>
                </v-list-tile>


                <v-list-tile avatar tag="div">
                    <v-list-tile-avatar>
                        <v-icon medium>dashboard</v-icon>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>Dashboard</v-list-tile-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                        <v-btn icon @click.stop="primaryDrawer.mini = !primaryDrawer.mini">
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
                        :to="{name:item.href}"
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
                <v-btn icon flat slot="activator">
                    <v-badge color="red" overlap>
                        <span slot="badge">3</span>
                        <v-icon medium>notifications</v-icon>
                    </v-badge>
                </v-btn>
                <v-card class="elevation-0">
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
                        <v-icon large>account_circle</v-icon>
                </v-btn>
                <v-list class="pa-0">
                    <v-list-tile v-for="(item,index) in itemss"  @click="item.click" ripple="ripple" :disabled="item.disabled" :target="item.target" rel="noopener" :key="index">
                        <v-list-tile-action v-if="item.icon">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile ripple="ripple" rel="noopener" :key="'logout'" @click="logout">
                        <v-list-tile-action>
                            <v-icon>logout</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Logout</v-list-tile-title>
                        </v-list-tile-content>
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
                   {{ new Date().getFullYear() }} &copy; ZakarpatTour. All rights reserved.
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
    export default {
        middleware:['admin-route','admin'],
        data: () => ({
            fav: true,
            menu: false,
            message: false,
            hints: true,

            dark: false,

            scroll:null,
            offsetTop:0,

            drawers: ['Default (no property)', 'Permanent', 'Temporary'],
            primaryDrawer: {
                model: null,
                type: 'default (no property)',
                clipped: false,
                floating: false,
                mini: false
            },

            items: [
                { title: 'Dash', icon: 'dashboard',color:'indigo',href:'admin.dash' },
                { title: 'Marker', icon: 'dashboard',color:'indigo',href:'admin.marker' },
                { title: 'Marker Category', icon: 'dashboard',color:'indigo',href:'admin.marker.category' },
                { title: 'Tour', icon: 'question_answer',color:'red',href:'admin.tour' },
                { title: 'Tour Category', icon: 'question_answer',color:'red',href:'admin.tour.category' }
            ],

            itemss: [
                {
                    icon: 'account_circle',
                    href: '#',
                    title: 'Profile',
                    click: (e) => {
                        console.log(e);
                    }
                },
                {
                    icon: 'settings',
                    href: '#',
                    title: 'Settings',
                    click: (e) => {
                        console.log(e);
                    }
                },
                {
                    icon: 'logout',
                    href: '#',
                    title: 'Logout',
                    click: (e) => {
                        this.logout()
                    }
                }
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
            }
        },
        methods:{
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
