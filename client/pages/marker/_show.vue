<template>
    <v-flex xs12>
        <v-container fluid fill-height pa-0 ma-0 pt-2>
            <v-layout row wrap justify-center>
                <v-flex xs12 md10>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-carousel>
                                <v-carousel-item
                                        v-for="item in images"
                                        :key="item.path"
                                        :src="`${item.url}/1440x500/${item.name}`"
                                >
                                    <v-toolbar style="background-color: transparent; box-shadow: none;">
                                        <v-toolbar-title class="white--text"><span
                                                class="amber darken-4 pa-1 white--text font-weight-bold title">
                                            #{{ getName(sight.category) }}</span>
                                        </v-toolbar-title>
                                        <v-spacer></v-spacer>
                                        <btn-favorite
                                                :itemID="sight.id"
                                                type="sight"
                                        ></btn-favorite>
                                    </v-toolbar>
                                </v-carousel-item>
                            </v-carousel>
                        </v-flex>
                        <v-flex xs12 pt-2 pb-2>
                            <v-card>
                                <v-container fill-height fluid>
                                    <v-layout row wrap align-center>
                                        <v-flex xs12>
                                            <h1 class="font-weight-bold display-1">{{ getTitle(sight)}}</h1>
                                        </v-flex>
                                        <v-flex xs12 pt-2>
                                            <v-layout
                                                    wrap
                                                    align-center
                                            >

                                                <v-chip color="blue darken-4" outline>
                                                    <v-icon left>location_city</v-icon>
                                                    {{ getTitle(sight.settlement) }}
                                                </v-chip>
                                                <v-chip color="blue darken-4" outline>
                                                    <v-icon left>visibility</v-icon>
                                                    Views : {{ sight.views }}
                                                </v-chip>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                        <v-flex xs12>
                            <v-card>
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
                                            color="indigo"
                                            @click="right = !right"
                                    >
                                        <v-icon>compare_arrows</v-icon>
                                    </v-btn>
                                    <v-btn
                                            fab
                                            dark
                                            small
                                            color="orange"
                                            @click="dialog = !dialog"
                                    >
                                        <v-icon>gps_fixed</v-icon>
                                    </v-btn>
                                    <v-btn
                                            fab
                                            dark
                                            small
                                            color="green"
                                            @click="setMapCenter"
                                    >
                                        <v-icon>center_focus_strong</v-icon>
                                    </v-btn>
                                </v-speed-dial>
                                <div id="gmap_container"></div>
                            </v-card>
                        </v-flex>
                        <v-flex xs12>
                            <v-card>
                                <v-flex xs12 class="pa-4 ma-2  mb-4 mt-2">
                                    <v-layout row wrap>
                                        <v-flex xs12 class="ql-editor" v-html="getDescription(sight)"></v-flex>
                                        <v-flex xs12 class="align-content-end mt-2">
                                            <v-btn color="primary"
                                                   @click="$router.go(-1)"
                                            >
                                                <v-icon>arrow_back</v-icon>&nbsp;Back
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-flex>


                <!--modal-->
                <v-dialog v-model="dialog" persistent max-width="300">
                    <v-card>
                        <v-card-title class="headline">
                            <v-flex xs12 class="text-xs-center">
                                <v-icon color="indigo" size="52">location_on</v-icon>
                            </v-flex>
                        </v-card-title>
                        <v-card-text class="font-weight-bold text-xs-center mt-0 pt-0">
                            Geocode <br>
                            {{ getPosition }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-input type="text" v-model="sight.position" hide-details readonly
                                     ref="clipboard"></v-input>

                            <v-btn color="green darken-1" flat @click="copyTextArea">Copy</v-btn>
                            <v-btn color="red darken-2" flat @click="dialog = false">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>


                <!--snackbar-->
                <v-snackbar
                        v-model="snackbar.active"
                        top
                        :timeout="snackbar.timeout"
                        :color="snackbar.color"
                >
                    {{ snackbar.text }}
                    <v-btn
                            dark
                            flat
                            @click="snackbar.active = false"
                    >
                        Close
                    </v-btn>
                </v-snackbar>
            </v-layout>

        </v-container>
    </v-flex>
</template>

<script>
    import BtnFavorite from "../../components/global/btn-favorite";

    export default {
        name: "show",
        components: {BtnFavorite},
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
                let {data} = await $axios.$get(`/marker/show/${params.slug}`)
                return {sight: data}
            } catch (e) {
                redirect('/erorr')
            }
        },

        data() {
            return {
                sight: {},
                marker: null,


                dialog: false,
                snackbar: {
                    active: false,
                    timeout: 4000,
                    text: 'Copied to clipboard',
                    color: 'success'
                },
                title:'',

                showmenu: false,
                fab: false,
                right: false,
                alert: false,

                images: [],
                map: {},
            }
        },
        async mounted() {
            try {
                let {data} = await this.$axios.get(`image/collect/sight/${this.sight.id}`)
                this.images = data

            } catch (e) {
                this.images = []
            }

            this.map = new google.maps.Map(document.getElementById('gmap_container'), {
                center: {lat: 48.496582, lng: 23.5212107},
                zoom: 8.7,
                minZoom: 8,
                maxZoom: 18,
                disableDefaultUI: true
            })


            this.generateMarker

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
            this.title = this.getTitle(this.sight)
        },
        methods: {
            copyTextArea() {
                let self = this
                navigator.clipboard.writeText(this.getPosition).then(function () {
                    self.snackbar.text = 'Copied to clipboard'
                    self.snackbar.active = true
                    self.snackbar.color = 'success'
                }, function (err) {
                    self.snackbar.color = 'erorr'
                    self.snackbar.text = 'Copy not supported'
                    self.snackbar.active = true

                    // console.error('Async: Could not copy text: ', err);
                });
                this.dialog = false
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
                this.map.setCenter(this.marker.getPosition());
            }
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            },
            getPosition() {
                return `${this.sight.lat},${this.sight.lng}`
            },
            generateMarker() {
                this.marker = new google.maps.Marker({
                    position: new google.maps.LatLng(this.sight.lat, this.sight.lng),
                    icon: {
                        url: '/svg/' + this.sight.category.icon + '.svg',
                        scaledSize: new google.maps.Size(32, 32),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 0)
                    },
                    map: this.map
                })

                let self = this.marker;
                this.marker.addListener('rightclick', function () {
                    this.map.setZoom(8);
                    this.map.setCenter(self.getPosition());
                });

                this.marker.addListener('click', function () {
                    this.map.setZoom(18);
                    this.map.setCenter(self.getPosition());
                });
            }
        }
    }
</script>

<style scoped>
    #gmap_container {
        width: 100%;
        min-height: 600px;
    }
</style>
