<template>
    <v-flex xs12>
        <v-container fluid fill-height pt-2>
            <v-layout row wrap justify-center>
                <v-flex xs12 md10>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-carousel>
                                <v-carousel-item
                                        v-for="item in tourImages"
                                        :key="item.path"
                                        :src="`${item.url}/1440x500/${item.name}`"
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
                                                <v-chip color="purple darken-4" outline>
                                                    <v-icon left>visibility</v-icon>
                                                    Views : {{ tour.views }}
                                                </v-chip>
                                                <v-chip color="blue darken-4" outline>
                                                    <v-icon left>access_time</v-icon>
                                                    Duration : {{tour.duration}} days
                                                </v-chip>
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-chip color="red darken-1" v-on="on" outline>
                                                            <span class="title">₴</span>&nbsp;&nbsp;&nbsp;Price : {{tour.price}}
                                                        </v-chip>
                                                    </template>
                                                    <span>Ціна у грн</span>
                                                </v-tooltip>

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
                                                    <v-flex xs12>
                                                        <v-layout row wrap justify-center class="text-xs-center">
                                                            <v-flex xs12>
                                                                <v-card class="card-bord">
                                                                    <v-toolbar color="primary" dark>
                                                                        <v-toolbar-title>Available dates
                                                                        </v-toolbar-title>
                                                                    </v-toolbar>
                                                                    <v-list>
                                                                        <v-list-group
                                                                                v-for="item in tour.available_dates"
                                                                                :key="item"
                                                                                prepend-icon="event"
                                                                                no-action
                                                                        >
                                                                            <template v-slot:activator>
                                                                                <v-list-tile>
                                                                                    <v-list-tile-content>
                                                                                        <v-tooltip bottom :disabled="notAvailable(item)">
                                                                                            <template v-slot:activator="{ on }">
                                                                                                <v-chip :color="notAvailable(item) ? 'red' : 'green'" v-on="on" text-color="white">{{item}}</v-chip>
                                                                                            </template>
                                                                                            <span>No more ticket</span>
                                                                                        </v-tooltip>
                                                                                    </v-list-tile-content>
                                                                                </v-list-tile>
                                                                            </template>

                                                                            <v-list-tile>
                                                                                <v-list-tile-content>
                                                                                    <v-list-tile-title class="green--text">
                                                                                        <span class="title ">₴</span>&nbsp;&nbsp;&nbsp;
                                                                                       <strong>{{tour.price}}</strong>
                                                                                    </v-list-tile-title>
                                                                                </v-list-tile-content>
                                                                                <v-list-tile-action>
                                                                                    <add-to-cart
                                                                                            :tour="tour"
                                                                                            :date="item"
                                                                                            :disabled="notAvailable(item)"
                                                                                            :max-person-count="20"
                                                                                    ></add-to-cart>
                                                                                </v-list-tile-action>
                                                                            </v-list-tile>
                                                                        </v-list-group>
                                                                    </v-list>
                                                                </v-card>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-flex>
                                        <v-flex xs12 md6 mb-2>

                                            <v-timeline dense>
                                                <v-timeline-item
                                                        v-for="item in tour.markers"
                                                        :color="`${getRandomColor()}`"
                                                        :key="item.slug"
                                                        small
                                                >


                                                    <v-card class="elevation-2 card-bord" elevation="5">
                                                        <v-card-title class="title">
                                                            <v-icon color="primary" left>location_city</v-icon>
                                                            {{ getTitle(item.settlement) }}
                                                        </v-card-title>
                                                        <v-card-text>
                                                            <v-chip @click="$router.push({name:'sight.show',params: {slug:item.slug}})"
                                                                    color="blue darken-4" outline>
                                                                <v-icon left>location_on</v-icon>
                                                                {{ item.title }}
                                                            </v-chip>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-timeline-item>
                                            </v-timeline>


                                        </v-flex>
                                        <v-flex xs12 class="ql-editor" v-html="getDescription(tour)"></v-flex>
                                        <v-flex xs12 class="align-content-end mt-2">
                                            <v-btn color="primary"
                                                   @click="$router.go(-1)"
                                            >
                                                <v-icon>arrow_back</v-icon>&nbsp;{{$t('btns.back')}}
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
                title: this.title,
            }
        },
        async asyncData({params, $axios, redirect}) {
            try {
                let {data} = await $axios.$get(`/tour/show/${params.slug}`)
                return {tour: data}
            } catch (e) {
                redirect('/error')
            }
        },
        data() {
            return {
                showmenu: false,
                fab: false,
                right: false,

                title:"",

                tour: {},
                map: {},

                alert: false,
                travelMode: 'DRIVING',
                travelModes: ['DRIVING', 'WALKING', 'BICYCLING', 'TRANSIT'],

                colors: [
                    'red', 'purple darken-1', 'green lighten-1',
                    'pink lighten-1', 'indigo', 'teal darken-2', 'deep-purple darken-2', 'teal darken-2',
                    'yellow accent-2', 'blue-grey darken-1', 'amber darken-3', 'green darken-4', 'lime accent-2', 'brown darken-2'

                ],

                tourImages: [],
                directionsDisplay: {},
            }
        },
        async mounted() {
            try {
                let {data} = await this.$axios.get(`image/collect/tour/${this.tour.id}`)
                this.tourImages = data;
            } catch (e) {
                this.tourImages = [{url: '/images/post', name: 'default.jpg'}];
            }

            this.map = new google.maps.Map(document.getElementById('gmap_container'), {
                center: {lat: 48.496582, lng: 23.5212107},
                zoom: 8.7,
                minZoom: 8,
                maxZoom: 18,
                disableDefaultUI: true
            })

            this.directionsDisplay = new google.maps.DirectionsRenderer({
                draggable: false,
                map: this.map,
                panel: document.getElementById('d_panel')
            });
            // this.directionsDisplay.setMap(this.map);
            // console.log(JSON.parse(this.tour.directions))
            this.directionsDisplay.setDirections(this.tour.directions)

            let self = this;
            if (google && google.maps) {
                var boundaries = new google.maps.FusionTablesLayer({
                    query: {
                        select: 'geometry',
                        from: '1g6jCZjMjMz-OzUItTvfEpTjon2MkEmPI8_Dl6RfA',
                        where: "'o_name' = 'Zakarpatska'"
                    },
                    location: 'region',
                    styles: self.$store.state.gMapStyles.stylesZoomIn,
                    map: self.map,
                    suppressInfoWindows: true
                })
                google.maps.event.addListener(self.map, 'zoom_changed', function () {
                    if ((self.map.getZoom() > 10) || (self.map.getZoom() < 4)) {
                        boundaries.set('styles', self.$store.state.gMapStyles.stylesZoomOut);
                    } else {
                        boundaries.set('styles', self.$store.state.gMapStyles.stylesZoomIn);
                    }
                })
            }
            this.title = this.getTitle(this.tour)
        },
        methods: {
            notAvailable(item){
                return false;
            },

            addToCart(id) {
                console.log(id);
            },
            addToFavorites(id) {
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
            setMapCenter() {
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

    #d_panel {
        /*overflow-x: scroll;*/
        word-wrap: break-word;
    }

    .card-bord{
        border-radius: 5px;
    }
    .text--strickout{
        text-decoration: line-through !important;
    }
</style>
