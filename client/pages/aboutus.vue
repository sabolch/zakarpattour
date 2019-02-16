<template>
    <v-container pa-0 fluid>
        <v-layout row wrap>
            <v-flex xs12 pa-0 ma-0>
                <v-parallax
                        dark
                        src="/images/bg/about.jpg"
                        height="490"
                >
                    <v-layout fill-height column ma-0 >
                        <v-spacer></v-spacer>
                        <v-flex class="text-xs-center" shrink>
                            <h1 class="pl-4 display-3 font-weight-light mb-3">About US</h1>
                        </v-flex>
                    </v-layout>
                </v-parallax>
            </v-flex>

            <v-flex xs12>
                <v-card dark style=" background-image: linear-gradient(rgb(0,0,90),rgb(0,0,255),rgb(0,0,50));">
                    <v-container>
                        <v-layout row wrap>
                            <v-slide-y-transition>
                            <v-flex xs12 class="text-xs-left pa-4" v-show="expand">
                                <h1 class="display-1 font-weight-bold">Day Tours</h1>
                                <p style="font-size: 18px" class="text-xs-justify">Day Tours are available (every day on most routes) for all of the most popular attractions in Ireland including the Giant’s Causeway, Cliffs of Moher, Wicklow Mountains, Kilkenny, Connemara & Galway, County Kerry, Belfast City, Blarney Castle & County Cork. Enjoy the highest quality touring vehicles in the industry with wi- fi access as standard. When you climb aboard on the morning of your tour, you’ll be glad you chose the Paddywagon!</p>
                                <v-divider ></v-divider>
                            </v-flex>
                            </v-slide-y-transition>
                            <v-slide-y-transition>
                            <v-flex xs12 class="text-xs-left pa-4" v-show="expand">
                                <h1 class="display-1 font-weight-bold">Multi-day Tours</h1>
                                <p style="font-size: 18px" class="text-xs-justify">
                                    Multi-day tours allow you to experience the various regions of Ireland easily and in comfort. Departures several times a week also mean that you will likely find something to match your travel plans when time is at a premium. You can choose between 2, 3, 4, 5, 6, 9 day durations and also between B&B / Guesthouse or youth hostel accommodations. Time is split between touring, personal exploration time and leisure time in the evenings. (Read more about the accommodation options & the various kinds of people who travel with us)
                                </p>
                                <v-divider></v-divider>
                            </v-flex>
                            </v-slide-y-transition>
                            <v-slide-y-transition>
                            <v-flex xs12 class="text-xs-left pa-4" v-show="expand">
                                <h1 class="display-1 font-weight-bold">
                                    Private Tours for Couples, Families and Groups
                                </h1>
                                <p style="font-size: 18px" class="text-xs-justify">
                                    Private Tours can be arranged for any of the suggested itineraries or perhaps you’d like to create something unique for your loved ones, friends or colleagues? Together with our upscale sister company “Elegant Irish Tours” we can provide you with bespoke arrangements at every price / quality point from budget to 3 star, 4 star and 5 star. Read more about private hire.
                                </p>
                                <v-divider></v-divider>
                            </v-flex>
                            </v-slide-y-transition>
                            <v-flex xs12 class="text-xs-left pa-4">
                                <h1 class="display-1 font-weight-bold">
                                    Shore Excursions for Cruise Passengers
                                </h1>
                                <p style="font-size: 18px" class="text-xs-justify">
                                    Shore excursions for cruise passengers are available for all of the major cruise ships docking at Dublin, Dun Laoghaire (Dublin), Cork, Cobh (Cork), Belfast and Waterford. Enjoy the same great highlights and same great quality at a fraction of the price charged on board. We’ll show you the best of Ireland and get you back to the boat well ahead of sailing, guaranteed.
                                </p>
                                <v-divider></v-divider>
                            </v-flex>
                            <v-flex xs12>
                                <div id="g_map"></div>
                            </v-flex>
                            <v-flex xs12 class="text-xs-center pa-5">
                                <h1 class="headline font-weight-bold mb-2">{{ $nuxt.$moment().format('Y')}} &copy; TouristRoutes. All rights reserved. </h1>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
      </v-container>
</template>

<script>
    export default {
        name: "aboutus",
        data () {
            return {
                expand:false,
                map:{},
            }
        },
        mounted(){
            this.expand = true;

            let myLatLng = {lat: 48.624478, lng: 22.2959873};

            this.map = new google.maps.Map(document.getElementById('g_map'), {
                zoom: 7,
                minZoom:7,
                center: myLatLng,
                disableDefaultUI: true
            });

            let marker = new google.maps.Marker({
                position: myLatLng,
                map: this.map,
                title: 'We'
            });

        },
        methods: {

        }
    }
</script>

<style scoped>
    #g_map{
        min-width: 100%;
        height: 300px;
    }
</style>
