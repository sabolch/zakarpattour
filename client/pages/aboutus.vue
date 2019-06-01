<template>
    <v-container pa-0 fluid>
        <v-layout row wrap>
            <v-flex xs12 pa-0 ma-0>
                <v-parallax
                        dark
                        src="/images/bg/about.jpg"
                        height="490"
                >
                    <v-layout fill-height column>
                        <v-spacer></v-spacer>
                        <v-flex class="text-xs-center" shrink>
                            <h1 class="font-weight-light mb-4"
                                :class="[{'display-2': breakpoint},{'display-3': !breakpoint}]">{{ $t('navbar.about')
                                }}</h1>
                        </v-flex>
                    </v-layout>
                </v-parallax>
            </v-flex>

            <v-flex xs12>
                <v-card dark class="card-bg">
                    <v-container>
                        <v-slide-y-transition>
                            <v-layout row wrap v-show="expand">
                                <v-flex xs12 class="text-xs-left pa-4"
                                        v-for="(item,index) in about.data"
                                        :key="index"
                                >
                                    <h1 class="font-weight-bold"
                                        :class="[{'headline': breakpoint},{'display-1': !breakpoint}]">
                                        {{getTitle(item)}}</h1>
                                    <p class="text-xs-justify"
                                       :class="[{'xs-text': breakpoint},{'md-upper-text': !breakpoint}]">
                                        {{getContent(item)}}</p>
                                    <v-divider></v-divider>
                                </v-flex>

                                <v-flex xs12>
                                    <div id="g_map"></div>
                                </v-flex>
                                <v-flex xs12 class="text-xs-center pa-5">
                                    <h1 :class="[{'title': breakpoint},{'headline': !breakpoint}]"
                                        class="font-weight-bold mb-2">{{ $nuxt.$moment().format('Y')}} &copy;
                                        {{$t('footer.title')}} </h1>
                                </v-flex>
                            </v-layout>
                        </v-slide-y-transition>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "aboutus",

        head() {
            return {
                title: this.$t('navbar.about'),
            };
        },
        async asyncData({$axios, redirect}) {
            try {
                let about = await $axios.get('pages')
                return {
                    about: about.data.data,
                }
            } catch (e) {
                redirect('/error')
            }
        },
        data() {
            return {
                expand: false,
                about: [],
                map: {},
            }
        },
        mounted() {
            this.expand = true;
            this.map = new google.maps.Map(document.getElementById('g_map'), {
                zoom: 7,
                minZoom: 7,
                center: this.about.position,
                disableDefaultUI: true
            });

            let marker = new google.maps.Marker({
                position: this.about.position,
                map: this.map,
                title: 'We'
            });
        },
        methods: {
            getTitle: function (x) {
                return x.translations.find(obj => obj.locale === this.getLocal).title
            },
            getContent: function (x) {
                return x.translations.find(obj => obj.locale === this.getLocal).content
            }
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            },
            breakpoint() {
                return this.$vuetify.breakpoint.xs
            }

        }
    }
</script>

<style scoped>
    #g_map {
        min-width: 100%;
        height: 300px;
    }

    .card-bg {
        background-image: linear-gradient(rgb(0, 0, 90), rgb(0, 0, 255), rgb(0, 0, 50));
    }
</style>
