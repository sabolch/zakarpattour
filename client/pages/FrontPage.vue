<template>
    <v-container pa-2 fluid fill-height grid-list-md>
        <v-layout row wrap justify-center>
            <v-flex xs12 pa-0>
                <v-parallax
                        dark
                        src="/images/bg/bg.jpg"
                        height="400"
                >
                    <p class="font-weight-bold mt-2 subheading text-xs-right">{{ datetime }}</p>

                    <v-layout
                            align-center
                            column
                            justify-center
                    >
                        <h1 class="display-3 font-weight-light mb-3">Kárpátaljai utazások</h1>
                        <h4 class="title">Látnivalók, természeti és történelmi emlékművek!</h4>
                    </v-layout>
                    <v-layout
                            align-top
                            column
                            rigth
                    >
                    </v-layout>
                </v-parallax>
            </v-flex>

            <v-flex xs10 offset-xs1 pa-0 ma-0>
                <v-container pa-1 style="height: auto; margin-top:-40px; opacity: .9">
                    <v-layout row wrap>
                        <v-flex xs4 md2>
                            <v-card dark color="red accent-4" class="text-xs-center pa-2">
                                <v-icon size="50">landscape</v-icon>
                                <p class="title mt-2">Táj</p>
                            </v-card>
                        </v-flex>
                        <v-flex xs4 md2>
                            <v-card dark color="orange darken-4" class="text-xs-center pa-2">
                                <v-icon size="50">account_balance</v-icon>
                                <p class="title mt-2">Épületek</p>
                            </v-card>
                        </v-flex>
                        <v-flex xs4 md2>
                            <v-card dark color="yellow accent-4" class="text-xs-center pa-2">
                                <v-icon size="50">local_hotel</v-icon>
                                <p class="title mt-2">Szállodák</p>
                            </v-card>
                        </v-flex>
                        <v-flex xs4 md2>
                            <v-card dark color="green accent-4" class="text-xs-center pa-2">
                                <v-icon size="50">local_dining</v-icon>
                                <p class="title mt-2">Kulinária</p>
                            </v-card>
                        </v-flex>
                        <v-flex xs4 md2>
                            <v-card dark color="light-blue darken-4" class="text-xs-center pa-2">
                                <v-icon size="50">card_travel</v-icon>
                                <p class="title mt-2">Utazások</p>
                            </v-card>
                        </v-flex>
                        <v-flex xs4 md2>
                            <v-card dark color="deep-purple accent-4" class="text-xs-center pa-2">
                                <v-icon size="50">local_activity</v-icon>
                                <p class="title mt-2">Programok</p>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-layout row wrap mt-2>
                <v-flex xs12>
                    <v-card>
                        <v-container ma-0 pa-3 fluid grid-list-xl>
                            <v-layout row justify-space-between class="align-center">
                                <h1 class="ml-4 font-weight-bold headline">Kiemelt úti célok</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'sights'}" outline>
                                    <v-icon>list</v-icon> &nbsp;Mind
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
                <v-flex v-for="item in 4" :key="item" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="'/images/Munkacs_vara.jpg'"
                                aspect-ratio="1"
                                height="300px"
                                class="grey lighten-2"
                        >
                            <v-layout
                                    slot="placeholder"
                                    fill-height
                                    align-center
                                    justify-center
                                    ma-0
                            >
                                <v-progress-circular indeterminate color="grey lighten-5"/>
                            </v-layout>

                             <v-card-actions>
                                <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#category</span>
                                <v-spacer></v-spacer>
                                <v-btn dark color="indigo" small fab>
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">Lorem ipsum dolor set amit</h1>
                            <v-rating
                                    color="blue darken-3"
                                    readonly
                                    background-color="grey darken-1"
                                    medium
                                    v-model="rating"
                            ></v-rating>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn icon class="purple--text">
                                <v-icon medium>sb-instagram</v-icon>
                            </v-btn>
                            <v-btn icon class="light-blue--text">
                                <v-icon medium>sb-twitter</v-icon>
                            </v-btn>
                            <v-btn icon class="blue--text text--darken-4">
                                <v-icon medium>sb-facebook</v-icon>
                            </v-btn>
                            <v-spacer/>
                            <v-btn flat class="blue--text"
                                   @click="readMore(item.slug)"
                                   outline
                            >Read More
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>
                <v-flex xs12 pa-1>
                    <v-parallax
                            dark
                            src="/images/bg/bg2.jpg"
                            height="300"
                    >
                        <v-layout fill-height column ma-0 >
                            <v-spacer></v-spacer>
                            <v-flex class="text-xs-right" shrink>
                                <h1 class=" text-xs-right title font-weight-bold mb-3">/Долина нарцисів/</h1>
                            </v-flex>
                        </v-layout>
                    </v-parallax>
                </v-flex>
                <v-flex xs12>
                    <v-card>
                        <v-container ma-0 pa-3 fluid grid-list-xl>
                            <v-layout row justify-space-between class="align-center">
                                <h1 class="ml-4 font-weight-bold headline">Kiemelt túrák</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'sights'}" outline>
                                    <v-icon>list</v-icon> &nbsp;Mind
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>


                <v-flex v-for="item in 4" :key="item" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="'/images/Munkacs_vara.jpg'"
                                aspect-ratio="1"
                                height="300px"
                                class="grey lighten-2"
                        >
                            <v-layout
                                    slot="placeholder"
                                    fill-height
                                    align-center
                                    justify-center
                                    ma-0
                            >
                                <v-progress-circular indeterminate color="grey lighten-5"/>
                            </v-layout>

                            <v-card-actions>
                                 <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#category</span>
                                <v-spacer></v-spacer>
                                <v-btn dark color="indigo" small fab>
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">Lorem ipsum dolor set amit</h1>
                            <v-rating
                                    color="blue darken-3"
                                    readonly
                                    background-color="grey darken-1"
                                    medium
                                    v-model="rating"
                            ></v-rating>
                            <v-chip v-for="i in 3" :key="i" color="blue darken-4" outline>
                                <v-icon left>location_on</v-icon>
                                Location {{i}}
                            </v-chip>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn icon class="purple--text">
                                <v-icon medium>sb-instagram</v-icon>
                            </v-btn>
                            <v-btn icon class="light-blue--text">
                                <v-icon medium>sb-twitter</v-icon>
                            </v-btn>
                            <v-btn icon class="blue--text text--darken-4">
                                <v-icon medium>sb-facebook</v-icon>
                            </v-btn>
                            <v-spacer/>
                            <v-btn flat class="blue--text"
                                   @click="readMore(item.slug)"
                                   outline
                            >Read More
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>

                <v-flex xs12 pa-1>
                    <v-parallax
                            dark
                            src="/images/bg/bg3.jpg"
                            height="300"
                    >
                        <v-layout fill-height column ma-0 >
                            <v-spacer></v-spacer>
                            <v-flex class="text-xs-right" shrink>
                                <h1 class=" text-xs-right title font-weight-bold mb-3">/Синевир/</h1>
                            </v-flex>
                        </v-layout>
                    </v-parallax>
                </v-flex>

                <v-flex xs12>
                    <v-card>
                        <v-container ma-0 pa-3 fluid grid-list-xl>
                            <v-layout row justify-space-between class="align-center">
                                <h1 class="ml-4 font-weight-bold headline">Népszerű úti célok</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'sights'}" outline>
                                    <v-icon>list</v-icon> &nbsp;Mind
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>


                <v-flex v-for="item in 4" :key="item" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="'/images/Munkacs_vara.jpg'"
                                aspect-ratio="1"
                                height="300px"
                                class="grey lighten-2"
                        >
                            <v-layout
                                    slot="placeholder"
                                    fill-height
                                    align-center
                                    justify-center
                                    ma-0
                            >
                                <v-progress-circular indeterminate color="grey lighten-5"/>
                            </v-layout>

                            <v-card-actions>
                                 <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#category</span>
                                <v-spacer></v-spacer>
                                <v-btn dark color="indigo" small fab>
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">Lorem ipsum dolor set amit</h1>
                            <v-rating
                                    color="blue darken-3"
                                    readonly
                                    background-color="grey darken-1"
                                    medium
                                    v-model="rating"
                            ></v-rating>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn icon class="purple--text">
                                <v-icon medium>sb-instagram</v-icon>
                            </v-btn>
                            <v-btn icon class="light-blue--text">
                                <v-icon medium>sb-twitter</v-icon>
                            </v-btn>
                            <v-btn icon class="blue--text text--darken-4">
                                <v-icon medium>sb-facebook</v-icon>
                            </v-btn>
                            <v-spacer/>
                            <v-btn flat class="blue--text"
                                   @click="readMore(item.slug)"
                                   outline
                            >Read More
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>


                <v-flex xs12 pa-1>
                    <v-parallax
                            dark
                            src="/images/bg/bg4.jpg"
                            height="300"
                    >
                        <v-layout fill-height column ma-0 >
                            <v-spacer></v-spacer>
                            <v-flex class="text-xs-right" shrink>
                                <h1 class=" text-xs-right title font-weight-bold mb-3">/Сакури Ужгород/</h1>
                            </v-flex>
                        </v-layout>
                    </v-parallax>
                </v-flex>



                <v-flex xs12>
                    <v-card>
                        <v-container ma-0 pa-3 fluid grid-list-xl>
                            <v-layout row justify-space-between class="align-center">
                                <h1 class="ml-4 font-weight-bold headline">Népszerű túrák</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'sights'}" outline>
                                    <v-icon>list</v-icon> &nbsp;Mind
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>


                <v-flex v-for="item in 4" :key="item" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="'/images/Munkacs_vara.jpg'"
                                aspect-ratio="1"
                                height="300px"
                                class="grey lighten-2"
                        >
                            <v-layout
                                    slot="placeholder"
                                    fill-height
                                    align-center
                                    justify-center
                                    ma-0
                            >
                                <v-progress-circular indeterminate color="grey lighten-5"/>
                            </v-layout>

                            <v-card-actions>
                                 <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#category</span>
                                <v-spacer></v-spacer>
                                <v-btn dark color="indigo" small fab>
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">Lorem ipsum dolor set amit</h1>
                            <v-rating
                                    color="blue darken-3"
                                    readonly
                                    background-color="grey darken-1"
                                    medium
                                    v-model="rating"
                            ></v-rating>
                            <v-chip v-for="i in 3" :key="i" color="blue darken-4" outline>
                                <v-icon left>location_on</v-icon>
                                Location {{i}}
                            </v-chip>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn icon class="purple--text">
                                <v-icon medium>sb-instagram</v-icon>
                            </v-btn>
                            <v-btn icon class="light-blue--text">
                                <v-icon medium>sb-twitter</v-icon>
                            </v-btn>
                            <v-btn icon class="blue--text text--darken-4">
                                <v-icon medium>sb-facebook</v-icon>
                            </v-btn>
                            <v-spacer/>
                            <v-btn flat class="blue--text"
                                   @click="readMore(item.slug)"
                                   outline
                            >Read More
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>



                <v-card dark class="text-xs-center indigo darken-4  ma-1" >
                <v-flex xs12 pb-5 pt-5>
                    <v-layout row wrap justify-center>
                        <v-flex xs3>
                                <v-container fluid fill-height>
                                    <v-layout column>
                                        <v-flex xs12 align-center>
                                            <v-icon size="72">my_location</v-icon>
                                            <h1 class="headline text-xs-center font-weight-bold ma-2"> Nagyszerű programok</h1>
                                            <v-card-text class="title"> 7 000+ látnivaló, túra és tevékenység közül
                                                választhat
                                            </v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                        </v-flex>
                        <v-divider :vertical="true"></v-divider>
                        <v-flex xs3>
                                <v-container fluid fill-height>
                                    <v-layout column>
                                        <v-flex xs12 align-center>
                                            <v-icon size="72">shopping_cart</v-icon>
                                            <h1 class="headline text-xs-center font-weight-bold ma-2">Foglaljon</h1>
                                            <v-card-text class="title">Kerülje el a sorban állást, és foglalja le
                                                programjait
                                            </v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                        </v-flex>
                        <v-divider :vertical="true"></v-divider>
                        <v-flex xs3>
                                <v-container fluid fill-height>
                                    <v-layout column>
                                        <v-flex xs12 align-center>
                                            <v-icon size="72">explore</v-icon>
                                            <h1 class="headline text-xs-center font-weight-bold ma-2">Fedezze fel</h1>
                                            <v-card-text class="title">Kárpátalja sokszínű természeti adotságait és történetét
                                            </v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                        </v-flex>
                    </v-layout>
                </v-flex>
                    <v-divider></v-divider>
                    <v-flex xs12 class="text-xs-center pa-5">
                            <h1 class="headline font-weight-bold mb-2">{{ $nuxt.$moment().format('Y')}} &copy; TouristRoutes. All rights reserved. </h1>
                    </v-flex>
                </v-card>
            </v-layout>
        </v-layout>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name:'indexpage',
        head() {
            return {
                title: 'Home',
            }
        },
        components: {},

        data: () => ({
            appName: process.env.appName,
            datetime: '',
            rating:4
        }),

        computed: mapGetters({
            user: 'auth/user'
        }),
        mounted() {
            window.$nuxt.$moment.locale('uk');
            this.datetime = window.$nuxt.$moment().locale(this.$i18n.locale).format('ll')
        },
        watch:{
            '$i18n.locale':function (value) {
                this.datetime = window.$nuxt.$moment().locale(value).format('ll')
            }
        },
        methods: {
            async logout() {
                // Log out the user.
                await this.$store.dispatch('auth/logout')
                // Redirect to login.
                this.$router.push({name: 'login'})
            }
        }
    }
</script>
<style lang="css">

</style>
