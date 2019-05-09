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
                            justify-center
                            class="text-xs-center"
                    >
                        <v-flex>
                            <v-layout row wrap>
                                <v-flex xs12 :class="[{'display-1': breakpoint},'pa-2',{'display-3': !breakpoint}]">
                                    {{ $t('index.title') }}
                                </v-flex>
                                <v-flex xs12 :class="[{'subheading': breakpoint},'pa-2',{'title': !breakpoint}]">
                                    {{ $t('index.subtitle') }}
                                </v-flex>
                            </v-layout>
                        </v-flex>
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
                        <v-flex xs4 md2 v-for="(tile,index) in tiles" :key="tile.icon">
                            <v-card dark :color="tile.color" class="text-xs-center pa-2">
                                <v-icon :size="breakpoint ? 40 : 50">{{ tile.icon }}</v-icon><br>
                                <div :class="[{'subtitle': breakpoint},'mt-2 mb-2',{'title': !breakpoint}]">{{ $t(`index.tiles[${index}]`) }}</div>
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
                                <h1 class="ml-4 font-weight-bold" :class="[{'title': breakpoint},{'headline': !breakpoint}]">{{ $t('index.subtitles.recommended_slights')
                                    }}</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'sights'}" outline>
                                    <v-icon>list</v-icon> &nbsp; {{ $t('btns.all') }}
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
                <v-flex v-for="(item,index) in posts.recommended_slights" :key="`${item.image}-${index}-o`" xs12 md6
                        lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="item.image"
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
                            > {{ $t('btns.read_more') }}
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
                        <v-layout fill-height column ma-0>
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
                                <h1 class="ml-4 font-weight-bold" :class="[{'title': breakpoint},{'headline': !breakpoint}]">{{ $t('index.subtitles.recommended_tours')
                                    }}</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'tours'}" outline>
                                    <v-icon>list</v-icon> &nbsp; {{ $t('btns.all') }}
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>


                <v-flex v-for="(item,index ) in posts.recommended_tours" :key="index" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="item.image"
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
                            > {{ $t('btns.read_more') }}
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
                        <v-layout fill-height column ma-0>
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
                                <h1 class="ml-4 font-weight-bold" :class="[{'title': breakpoint},{'headline': !breakpoint}]">{{ $t('index.subtitles.popular_slights')
                                    }}</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'sights'}" outline>
                                    <v-icon>list</v-icon> &nbsp; {{ $t('btns.all') }}
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>


                <v-flex v-for="(item,index) in posts.popular_slights" :key="`${index}-${item.image}`" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="item.image"
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
                            > {{ $t('btns.read_more') }}
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
                        <v-layout fill-height column ma-0>
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
                                <h1 class="ml-4 font-weight-bold" :class="[{'title': breakpoint},{'headline': !breakpoint}]">{{ $t('index.subtitles.popular_tours')
                                    }}</h1>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" class="mr-4" :to="{name: 'tours'}" outline>
                                    <v-icon>list</v-icon> &nbsp; {{ $t('btns.all') }}
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>


                <v-flex v-for="(item,index) in posts.popular_slights" :key="`${item.image}-${index}`" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="item.image"
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
                            > {{ $t('btns.read_more') }}
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>
                <v-flex xs12>
                    <v-card dark class="text-xs-center indigo darken-4">
                        <v-flex xs12 pb-5 pt-5>
                            <v-layout row wrap justify-center>
                                <v-flex xs12 md3 v-for="(item,index) in footer" :key="item.icon">
                                    <v-container fluid fill-height>
                                        <v-layout column>
                                            <v-flex xs12 align-center>
                                                <v-icon size="72">{{ item.icon }}</v-icon>
                                                <h1 class="headline text-xs-center font-weight-bold ma-2">{{
                                                    $t(`footer.tiles[${index}].title`) }}</h1>
                                                <v-card-text class="title">
                                                    {{ $t(`footer.tiles[${index}].text`) }}
                                                </v-card-text>
                                            </v-flex>
                                        </v-layout>
                                        <v-divider class="hidden-md-and-down" v-if="item.divider" :vertical="true"></v-divider>
                                    </v-container>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-divider></v-divider>
                        <v-flex xs12 class="text-xs-center pa-5">
                            <h1 class="headline font-weight-bold mb-2">{{ $nuxt.$moment().format('Y')}} &copy;
                                {{$t('footer.title')}} </h1>
                        </v-flex>
                    </v-card>
                </v-flex>

            </v-layout>
        </v-layout>
    </v-container>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name: 'indexpage',
        head() {
            return {
                title: this.$t("index.title")
            }
        },
        components: {},

        data: () => ({
            appName: process.env.appName,
            datetime: '',
            rating: 4,

            tiles: [
                {icon: "landscape", color: "red accent-4"},
                {icon: "account_balance", color: "orange darken-4"},
                {icon: "local_hotel", color: "yellow accent-4"},
                {icon: "local_dining", color: "green accent-4"},
                {icon: "card_travel", color: "light-blue darken-4"},
                {icon: "local_activity", color: "deep-purple accent-4"},
            ],
            footer: [
                {icon: "my_location", divider: true},
                {icon: "shopping_cart", divider: true},
                {icon: "explore", divider: false},
            ],
            posts: {
                recommended_slights: [
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'}
                ],
                recommended_tours: [
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'}
                ],
                popular_slights: [
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'}
                ],
                popular_tours: [
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'},
                    {image: '/images/Munkacs_vara.jpg'}
                ]

            }

        }),

        computed: {
            breakpoint() {
                return this.$vuetify.breakpoint.xs
            }
        },
        mounted() {
            window.$nuxt.$moment.locale('uk');
            this.datetime = window.$nuxt.$moment().locale(this.$i18n.locale).format('ll')
        },
        watch: {
            '$i18n.locale': function (value) {
                this.datetime = window.$nuxt.$moment().locale(value).format('ll')
            }
        },
        methods: {}
    }
</script>
<style lang="css">
</style>
