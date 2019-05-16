<template>
    <v-flex xs12>
        <v-container fluid fill-height pt-2>
            <v-layout row wrap justify-center>
                <v-flex xs12 md10>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-carousel>
                                <v-carousel-item
                                        v-for="(item,i) in tourImages"
                                        :key="i"
                                        :src="item.src"
                                >
                                    <v-toolbar style="background-color: transparent; box-shadow: none;">
                                        <v-toolbar-title class="white--text"><span
                                                class="amber darken-4 pa-1 white--text font-weight-bold title">#{{getName(tour.category)}}</span>
                                        </v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <v-btn fab small color="primary"
                                               @click="addToFavorites(5)"
                                        >
                                            <v-icon>favorite</v-icon>
                                        </v-btn>
                                    </v-toolbar>
                                </v-carousel-item>
                            </v-carousel>
                        </v-flex>
                        <v-flex xs12 pt-2 pb-2>
                            <v-card>
                                <v-container fill-height fluid>
                                    <v-layout row wrap align-center>
                                        <v-flex xs12>
                                            <h1 class="font-weight-bold display-1">
                                                {{ getTitle(tour) }}
                                            </h1>
                                        </v-flex>
                                        <v-flex xs12 pt-2>
                                            <v-layout
                                                    wrap
                                                    align-center
                                            >
                                                <v-flex xs6 md3 class=" align-center">
                                                    <v-layout align-center>
                                                        <v-icon color="indigo" class="mr-1">visibility</v-icon>
                                                        <span class="subheading mr-2"> Views : {{tour.views}}</span>
                                                    </v-layout>
                                                </v-flex>

                                                <v-flex xs6 md3>
                                                    <v-layout align-center>
                                                        <v-icon color="indigo" class="mr-1">access_time</v-icon>
                                                        <span class="subheading mr-2"> Duration : {{tour.duration}} days</span>
                                                    </v-layout>
                                                </v-flex>
                                                <v-flex xs6 md3>
                                                    <v-layout align-center>
                                                        <v-icon color="indigo" class="mr-1">date_range</v-icon>
                                                        <span class="subheading mr-2"> Start : {{ tour.start_date.substr(0,10) }}</span>
                                                    </v-layout>
                                                </v-flex>
                                                <v-flex xs6 md3>
                                                    <v-layout align-center>
                                                        <v-icon color="indigo" class="mr-1">date_range</v-icon>
                                                        <span class="subheading mr-2"> End : {{tour.end_date.substr(0,10)}}</span>
                                                    </v-layout>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                        <v-flex xs12>
                            <v-card>
                                <v-navigation-drawer
                                        :right="right"
                                        hide-overlay
                                        absolute
                                        stateless
                                        v-model="showmenu"
                                        v-show="showmenu"
                                        id="map_drawer"
                                >
                                    <v-list dense>
                                        <v-list-tile
                                                @click="showmenu = false"
                                        >
                                            <v-list-tile-action>
                                                <v-icon v-if="!right">arrow_back</v-icon>
                                                <v-icon v-else>arrow_forward</v-icon>
                                            </v-list-tile-action>

                                            <v-list-tile-content>
                                                <v-list-tile-title>Hide</v-list-tile-title>
                                            </v-list-tile-content>

                                        </v-list-tile>
                                        <v-alert
                                                v-model="alert"
                                                dismissible
                                                type="error"
                                        >
                                            Opps no directions available.
                                        </v-alert>
                                        <v-flex xs12 d-flex style="margin: 5px;">
                                            <v-select
                                                    :items="travelModes"
                                                    label="Traveling mode"
                                                    v-model="travelMode"
                                            ></v-select>
                                        </v-flex>
                                        <div id="d_panel">

                                        </div>
                                    </v-list>
                                </v-navigation-drawer>
                                <v-speed-dial
                                        top
                                        absolute
                                        v-model="fab"
                                        :right="!right"
                                        :direction="'bottom'"
                                        :transition="'slide-y-transition'"
                                        style="top:40px;margin-left:10px"
                                >
                                    <v-btn
                                            slot="activator"
                                            v-model="fab"
                                            color="blue darken-2"
                                            dark
                                            fab
                                    >
                                        <v-icon>settings</v-icon>
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                    <v-btn
                                            fab
                                            dark
                                            small
                                            color="green"
                                            @click="showmenu = !showmenu"
                                    >
                                        <v-icon>directions</v-icon>
                                    </v-btn>
                                    <v-btn
                                            fab
                                            dark
                                            small
                                            color="amber darken-2"
                                            @click="setMapCenter"
                                    >
                                        <v-icon>center_focus_strong</v-icon>
                                    </v-btn>
                                    <v-btn
                                            fab
                                            dark
                                            small
                                            color="indigo"
                                            @click="right = !right"
                                    >
                                        <v-icon>compare_arrows</v-icon>
                                    </v-btn>
                                </v-speed-dial>
                                <div id="gmap_container"></div>
                            </v-card>
                        </v-flex>
                        <v-flex xs12 mt-2>
                            <v-card>
                                <v-container fill-height>
                                    <v-layout row wrap>
                                        <v-flex xs12 md6>
                                            <v-container fill-height fluid>
                                                <v-layout row wrap align-center justify-center>
                                                    <v-flex xs4>
                                                        <v-layout row wrap justify-center class="text-xs-center">
                                                            <v-flex xs12>
                                                                <v-icon size="72" color="indigo">shopping_cart</v-icon>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <span class="title font-weight-bold">Price: &euro;{{tour.price}}</span>
                                                            </v-flex>
                                                            <v-btn box color="orange"
                                                                   @click="addToCart(5)"
                                                            >Add to cart now</v-btn>
                                                        </v-layout>

                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                        <v-flex xs12 md6 mb-2>
                                            <v-timeline
                                                    dense
                                            >
                                                <v-timeline-item
                                                        v-for="item in tour.markers"
                                                        :color="`${getRandomColor()}`"
                                                        :key="item.slug"
                                                        small
                                                >
                                                    <v-layout pt-3>
                                                        <v-flex xs3>
                                                            <strong>{{ item.item }}</strong>
                                                        </v-flex>
                                                        <v-flex>
                                                            <strong>New Icon</strong>
                                                            <div class="caption">{{ item.color}}</div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-timeline-item>
                                            </v-timeline>
                                        </v-flex>
                                        <v-flex xs12 class="ql-editor" v-html="getDescription(tour)"></v-flex>
                                        <v-flex xs12 class="align-content-end mt-2">
                                            <v-btn color="primary"
                                                   @click="$router.go(-1)"
                                            >
                                                <v-icon>arrow_back</v-icon>&nbsp;Back
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-container>
    </v-flex>
</template>

<script>
    export default {
        name: "show",
        validate({params}) {
            return /^[a-zA-Z0-9._-]+$/.test(params.slug)
        },
        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },
        async asyncData ({ params, $axios,redirect }) {
            try {
                let {data} = await  $axios.$get(`/tour/show/${params.slug}`)
                return {tour: data}
            }catch (e) {
                redirect('/error')
            }
        },
        data() {
            return {
                showmenu:false,
                fab:false,
                right:false,

                tour: {},
                map:{},

                alert:false,
                travelMode:'DRIVING',
                travelModes:['DRIVING','WALKING','BICYCLING','TRANSIT'],

                colors: [
                    'red', 'purple darken-1', 'green lighten-1',
                    'pink lighten-1','indigo','teal darken-2','deep-purple darken-2','teal darken-2',
                    'yellow accent-2','blue-grey darken-1','amber darken-3','green darken-4','lime accent-2','brown darken-2'

                ],

                tourImages: [
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg'
                    }
                ],

                directionsDisplay:{},
            }
        },
        mounted() {
            this.map = new google.maps.Map(document.getElementById('gmap_container'), {
                center: {lat: 48.496582, lng: 23.5212107},
                zoom: 8.7,
                minZoom: 8,
                maxZoom: 18,
                disableDefaultUI: true
            })

            this.directionsDisplay = new google.maps.DirectionsRenderer({
                draggable: true,
                map: this.map,
                panel: document.getElementById('d_panel')
            });
            // this.directionsDisplay.setMap(this.map);
            // console.log(JSON.parse(this.tour.directions))
            this.directionsDisplay.setDirections(JSON.parse(this.tour.directions))

            let self = this;
            if(google && google.maps){
                var boundaries = new google.maps.FusionTablesLayer({
                    query: {
                        select: 'geometry',
                        from: '1g6jCZjMjMz-OzUItTvfEpTjon2MkEmPI8_Dl6RfA',
                        where: "'o_name' = 'Zakarpatska'"
                    },
                    location:'region',
                    styles: self.$store.state.gMapStyles.stylesZoomIn,
                    map: self.map,
                    suppressInfoWindows: true
                })
                google.maps.event.addListener(self.map, 'zoom_changed', function() {
                    if (( self.map.getZoom() > 10) || ( self.map.getZoom() < 4)) {
                        boundaries.set('styles',self.$store.state.gMapStyles.stylesZoomOut);
                    } else {
                        boundaries.set('styles',self.$store.state.gMapStyles.stylesZoomIn);
                    }
                })
            }

        },
        methods: {
            addToCart(id) {
                console.log(id);
            },
            addToFavorites(id){
                console.log(id);
            },

            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            },
            getName(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).name
            },
            getDescription(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).description
            },
            setMapCenter(){
                this.map.setCenter({lat: 48.496582, lng: 23.5212107});
            },
            getRandomColor() {
                let rnd = Math.floor(Math.random() * (this.colors.length - 0)) + 0;
                return this.colors[rnd]
            }
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            },


        }
    }
</script>

<style scoped>
    #gmap_container {
        width: 100%;
        min-height: 600px;
    }
    #d_panel{
        /*overflow-x: scroll;*/
        word-wrap: break-word;
    }
</style>
