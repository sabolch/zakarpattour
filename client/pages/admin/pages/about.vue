<template>
    <v-container fill-height fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-parallax
                        dark
                        src="/images/bg/about.jpg"
                        height="490"
                >
                    <v-layout fill-height column>
                        <v-spacer></v-spacer>
                        <v-flex class="text-xs-center" shrink>
                            <h1 class="font-weight-light mb-4"
                            >{{ $t('navbar.about')
                                }}</h1>
                        </v-flex>
                    </v-layout>
                </v-parallax>
            </v-flex>
            <v-flex xs12>
                <v-card>
                    <v-toolbar color="red darken-4" dark>
                        <v-toolbar-title>{{$t('navbar.about')}}</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn color="red lighteen-4" icon @click="add">
                            <v-icon>add</v-icon>
                        </v-btn>
                        <v-btn color="blue lighteen-4" :loading="form.busy" @click="save">
                            {{$t('btns.save')}}
                            <v-icon right dark>save</v-icon>
                        </v-btn>

                    </v-toolbar>

                    <v-list two-line>
                        <template v-for="(item, index) in about.data">
                            <v-list-tile
                                    :key="`${index}-${getTitle(item)}`"
                                    avatar
                                    ripple
                            >
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ getTitle(item) }}</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ item.translations[0].content }}</v-list-tile-sub-title>
                                </v-list-tile-content>

                                <v-list-tile-action>
                                    <v-list-tile-action-text></v-list-tile-action-text>
                                    <v-btn flat icon @click="remove(index)">
                                        <v-icon
                                                color="red lighten-1"

                                        >
                                            delete
                                        </v-icon>
                                    </v-btn>

                                    <v-btn flat icon @click="edit(index)">
                                        <v-icon
                                                color="primary lighten-1"
                                        >
                                            edit
                                        </v-icon>
                                    </v-btn>
                                </v-list-tile-action>

                            </v-list-tile>
                            <v-divider
                                    v-if="index + 1 < about.data.length"
                                    :key="`${(index+1)*(index+1)}-${getTitle(item)}`"
                            ></v-divider>
                        </template>
                    </v-list>
                </v-card>
            </v-flex>

            <v-flex xs12>
                <v-card>
                    <v-container fluid ma-2>
                        <v-text-field label="" id="g-searchbox"></v-text-field>
                        <v-layout row wrap>
                            <v-flex>
                                <v-btn @click="createMarker" color="success">Add Marker</v-btn>
                            </v-flex>
                            <v-flex xs12 mt-4>
                                <div id="gmap_container"></div>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="dialog" persistent max-width="800px">
            <v-card>
                <v-card-title>
                    <v-layout justify-center pa-0 ma-0>
                        <v-flex xs12 class="headline">
                            <v-card dark class="pa-3" color="red darken-4">{{$t('navbar.about')}}</v-card>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md pa-0 ma-0>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-tabs
                                        fixed-tabs
                                        color="primary"
                                        dark
                                        slider-color="white"
                                >
                                    <v-tab
                                            v-for="item in about.data[item].translations"
                                            :key="item.locale"
                                            ripple
                                    >
                                        {{ item.locale.toUpperCase() }}
                                    </v-tab>
                                    <v-tab-item
                                            v-for="item in about.data[item].translations"
                                            :key="item.locale"
                                    >
                                        <v-card flat>
                                            <v-card-text>
                                                <v-text-field
                                                        v-model="item.title"
                                                        :maxlength="50"
                                                        counter
                                                ></v-text-field>
                                                <v-textarea
                                                        v-model="item.content"
                                                        box
                                                        counter
                                                        :maxlength="600"
                                                ></v-textarea>
                                            </v-card-text>
                                        </v-card>
                                    </v-tab-item>
                                </v-tabs>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outline color="blue darken-1" flat @click="dialog = false">{{$t('btns.close')}}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
    import Form from 'vform'

    export default {
        name: "about",
        layout: "admin",

        head() {
            return {
                title: `Admin - ${this.$t('navbar.about')}`,
            }
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
        mounted() {
            this.map = new google.maps.Map(document.getElementById('gmap_container'), {
                center: this.about.position,
                zoom: 8.7,
                minZoom: 8,
                maxZoom: 18,
                disableDefaultUI: true
            })

            this.mapMarker = new google.maps.Marker({
                map: this.map,
                position: this.about.position,
                draggable: true
            });
            this.searchBox(this.map)
        },
        data() {
            return {
                dialog: false,
                map: {},
                mapMarker: {},
                about: {
                    position: {lat: 0, lng: 0},
                    data: [
                        {
                            translations: [
                                {locale: 'en', title: '', content: ''},
                                {locale: 'hu', title: '', content: ''},
                                {locale: 'ua', title: '', content: ''}
                            ]
                        }
                    ],
                },

                form: new Form(),
                item: 0
            }
        },

        methods: {
            async save() {
                try {
                    this.about.position = {lat:this.mapMarker.position.lat(),lng:this.mapMarker.position.lng()}
                    this.form.about = this.about
                    await this.form.put('pages/put')
                } catch (e) {
                    console.log(e)
                }
            },
            add() {
                this.about.data.push(
                    {
                        translations: [
                            {locale: 'en', title: '', content: ''},
                            {locale: 'hu', title: '', content: ''},
                            {locale: 'ua', title: '', content: ''}
                        ]
                    }
                );
            },
            remove(index) {
                this.item = 0
                if (this.about.data.length < 2) return
                this.about.data.splice(index, 1);
            },
            edit(index) {
                this.item = index
                this.dialog = true
            },
            getTitle: function (x) {
                return x.translations.find(obj => obj.locale === this.getLocal).title
            },

            searchBox(map) {
                let input = document.getElementById('g-searchbox');
                let searchBox = new google.maps.places.SearchBox(input);
                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });
                let markers = [];
                searchBox.addListener('places_changed', function () {
                    let places = searchBox.getPlaces();
                    if (places.length == 0) {
                        return;
                    }
                    markers.forEach(function (marker) {
                        marker.setMap(null);
                    });
                    markers = [];
                    let bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        let icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25)
                        };
                        markers.push(new google.maps.Marker({
                            map: map,
                            icon: icon,
                            title: place.name,
                            position: place.geometry.location
                        }));
                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    input.value = "";
                    map.fitBounds(bounds);
                });
            },
            createMarker: function () {
                if (typeof this.mapMarker.position === 'undefined') {
                    this.mapMarker = new google.maps.Marker({
                        map: this.map,
                        position: this.map.center,
                        draggable: true
                    });
                    return;
                }
                this.mapMarker.set('position', this.map.center);

                let self = this;
                google.maps.event.addListener(this.mapMarker, 'dragend', function (event) {
                    self.about.position.lat = event.latLng.lat();
                    self.about.position.lng = event.latLng.lng();
                });
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
        min-height: 400px;
    }
</style>
