<template>
    <v-flex xs12>
        <v-container fluid fill-height pt-2>
            <v-layout row wrap justify-center>
                <v-flex xs12 md10>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-carousel>
                                <v-carousel-item
                                        v-for="(item,i) in items"
                                        :key="i"
                                        :src="item.src"
                                >
                                    <v-toolbar style="background-color: transparent; box-shadow: none;">
                                        <v-toolbar-title class="white--text"><span
                                                class="amber darken-4 pa-1 white--text font-weight-bold title">#category</span>
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
                                            <h1 class="font-weight-bold display-1">Some title test</h1>
                                        </v-flex>
                                        <v-flex xs12 pt-2>
                                            <v-layout
                                                    wrap
                                                    align-center
                                            >
                                                <v-flex xs6 md3 class=" align-center">
                                                    <v-layout align-center>
                                                        <v-icon color="indigo" class="mr-1">visibility</v-icon>
                                                        <span class="subheading mr-2"> Views : 501</span>
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
                            </v-speed-dial>
                            <div id="gmap_container"></div>
                            </v-card>
                        </v-flex>
                        <v-flex xs12 mt-2>
                            <v-card>
                                <v-container fill-height>
                                    <v-layout row wrap>

                                        <v-flex xs12 class="text-xs-justify">
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vestibulum, libero ac laoreet sodales, felis nulla dictum sapien, non ultrices enim urna et ligula. Suspendisse lobortis lacinia iaculis. Sed et lacinia lacus. Nullam porta ligula vel risus iaculis, eget consectetur augue fermentum. Sed luctus lorem maximus, porta mauris vel, eleifend enim. Donec sed facilisis eros, in vulputate diam. Donec ullamcorper augue at ex aliquam, malesuada convallis metus scelerisque. Nunc fermentum, nisi vel varius blandit, lectus nibh volutpat metus, eget pellentesque nulla orci ac ex. Aenean tincidunt risus id arcu fringilla, vel auctor nisi lacinia. Donec sapien nunc, suscipit in risus non, facilisis iaculis felis. Donec blandit, nibh nec lacinia hendrerit, sapien turpis maximus ipsum, a ornare tortor odio a elit.
                                            </p>
                                            <ul>
                                                <li>
                                                    fdsgs
                                                </li>
                                                <li>
                                                    vdsgsd
                                                </li>
                                            </ul>
                                           <p>
                                               Donec eget bibendum dui. Pellentesque tempus sed diam vel viverra. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Integer pretium, dolor vel egestas fringilla, magna magna euismod lorem, vitae dapibus risus nulla sed nisl. Donec ac euismod ex, in mattis mauris. Phasellus pellentesque at magna quis gravida. Morbi gravida tellus in velit hendrerit dapibus. Fusce at sollicitudin diam. Duis gravida neque porta massa vulputate posuere. Curabitur eget ultrices metus. Maecenas a semper est. Donec ac eros velit. Fusce dolor nibh, mattis sed porta id, volutpat eu turpis. Nulla non lacinia nibh. Donec luctus massa sollicitudin eros venenatis rutrum.
                                           </p>
                                        </v-flex>
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
                            {{ marker.position }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-input type="text" v-model="marker.position" hide-details readonly ref="clipboard"></v-input>

                            <v-btn color="green darken-1" flat @click="copyTextArea">Copy</v-btn>
                            <v-btn color="red darken-1" flat @click="dialog = false">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>


                <!--snackbar-->
                <v-snackbar
                        v-model="snackbar"
                        top
                        :timeout="timeout"
                >
                    {{ snackbarText }}
                    <v-btn
                            color="pink"
                            flat
                            @click="snackbar = false"
                    >
                        Close
                    </v-btn>
                </v-snackbar>
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
        head: {
            script: [
                {src: `https://maps.googleapis.com/maps/api/js?key=${process.env.GOOGLE_MAP_API_KEY}&libraries=places`,body: true},
            ]
        },

        async asyncData ({ params, $axios, $router }) {
            // try {
            //     let {data} = await  $axios.$get(`/marker/show/${params.slug}`)
            //     if (data.success) return {marker: data.data}
            // }catch (e) {
            //     $router.push({name:'error'})
            // }
        },

        data() {
            return {
                marker:{},

                dialog:false,
                snackbar:false,
                snackbarText:'Copied to clipboard',
                timeout:4000,

                showmenu:false,
                fab:false,
                right:false,
                alert:false,

                items: [
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
                map: {},
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


            this.generateMarker

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
            addToFavorites(id){
                console.log(id);
            },
            copyTextArea() {
                let self = this
                navigator.clipboard.writeText(this.marker.position).then(function() {
                    self.snackbarText='Copied to clipboard'
                    self.snackbar = true
                }, function(err) {
                    self.snackbarText='Copy not supported'
                    // console.error('Async: Could not copy text: ', err);
                });
                this.dialog = false
            }
        },
        computed: {

            generateMarker:function () {
                let locations = [
                    {lat: 48.496582, lng: 23.5212107}
                ];

                this.marker =  new google.maps.Marker({
                    position: locations[0],
                    label: 'A',
                    map: this.map
                });
            },
        }
    }
</script>

<style scoped>
    #gmap_container {
        width: 100%;
        min-height: 400px;
    }
    #d_panel{
        /*overflow-x: scroll;*/
        word-wrap: break-word;
    }
    .adp-step, .adp-substep {
        max-width: 200px;
    }
    .v-speed-dial__list{
        top:-170px;
    }
    .v-speed-dial--absolute, .v-speed-dial--fixed {
        z-index: 1
    }
</style>
