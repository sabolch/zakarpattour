<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-stepper v-model="e6" vertical>
                    <v-stepper-step editable :complete="e6 > 1" step="1">
                        Marker
                        <small>Select the location</small>
                    </v-stepper-step>
                    <v-stepper-content step="1">


                        <v-tabs
                                fixed-tabs
                                color="primary"
                                dark
                                slider-color="white"
                        >
                            <v-tab
                                    v-for="item in form.translations"
                                    :key="item.locale"
                                    ripple
                            >
                                {{ item.locale.toUpperCase() }}
                            </v-tab>
                            <v-tab-item
                                    v-for="item in form.translations"
                                    :key="item.locale"
                            >
                                <v-card flat>
                                    <v-card-text>
                                        <v-text-field
                                                v-model="item.title"
                                                :label="`Title ${item.locale}`"
                                                clearable
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                        <v-autocomplete
                                class="pl-3 pr-3"
                                :items="categories"
                                v-model="form.category"
                                label="Select category"
                                persistent-hint
                                :filter="autoFilter"
                                :item-value="autoValue"
                        >
                            <template slot="selection"
                                      slot-scope="{ item, index }"
                            >
                                                <span style="color:blue;font-size: 28px;" class="v-icon mki"
                                                      :class="`mki-${item.icon}`"></span>
                                <span>&nbsp;&nbsp; {{ item.translations.find(obj => obj.locale ===  getLocal).name }}</span>
                            </template>

                            <template slot="item"
                                      slot-scope="{ item, index }"
                            >
                                            <span style="color:blue;font-size: 28px;" class="v-icon mki"
                                                  :class="`mki-${item.icon}`"></span>
                                <span>&nbsp;&nbsp;{{ item.translations.find(obj => obj.locale ===  getLocal).name  }}</span>
                            </template>
                        </v-autocomplete>
                        <v-btn color="primary" @click="e6 = 2">{{$t('btns.continue')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step editable :complete="e6 > 2" step="2">Title & category
                        <small>Give a title and translate it also set the category</small>
                    </v-stepper-step>

                    <v-stepper-content step="2">
                        <v-container fluid pa-0 ma-0 pb-2>
                            <v-text-field label="" id="g-searchbox"></v-text-field>
                            <v-layout row wrap>
                                <v-flex m6>
                                    <v-btn @click="createMarker" color="success">Add Marker</v-btn>
                                </v-flex>
                                <v-flex m6>
                                    <v-switch
                                            v-model="toogleMapStyle"
                                            label="Show map labels"
                                            color="indigo"
                                            value="indigo"
                                            hide-details
                                            @change="styleHandler"
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 mt-4>
                                    <div id="gmap_container"></div>
                                </v-flex>
                            </v-layout>
                        </v-container>

                        <v-btn color="primary" @click="e6 = 3">{{$t('btns.continue')}}</v-btn>
                        <v-btn @click="e6 = e6-1" flat>{{$t('btns.back')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step editable :complete="e6 > 3" step="3">Description
                        <small>Set the description and translate</small>
                    </v-stepper-step>

                    <v-stepper-content step="3">
                        <v-tabs
                                fixed-tabs
                                color="primary"
                                dark
                                slider-color="white"
                        >
                            <v-tab
                                    v-for="item in form.translations"
                                    :key="item.locale"
                                    ripple
                            >
                                {{ item.locale.toUpperCase() }}
                            </v-tab>
                            <v-tab-item
                                    v-for="(item,index) in form.translations"
                                    :key="item.locale"
                            >
                                <v-card flat>
                                    <v-card-text>
                                        <quill-editor :content="item.description" v-model="item.description"></quill-editor>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                        <v-btn color="primary" @click="e6 = 4">{{$t('btns.continue')}}</v-btn>
                        <v-btn @click="e6 = e6-1" flat>{{$t('btns.back')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step step="4">Also done!
                        <small>Finish</small>
                    </v-stepper-step>
                    <v-stepper-content step="4">
                        <v-btn dark color="green" :loading="form.busy" @click="store">Save</v-btn>
                        <v-btn dark color="orange" @click="e6 = e6-1">{{$t('btns.back')}}</v-btn>
                    </v-stepper-content>
                </v-stepper>
            </v-flex>

            <v-snackbar
                    v-model="snackbar.status"
                    :color="snackbar.color"
                    :timeout="4000"
            >
                {{ $t(snackbar.message) }}
                <v-btn
                        dark
                        flat
                        @click="snackbar.status = false"
                >
                    {{$t('btns.close')}}
                </v-btn>
            </v-snackbar>

        </v-layout>
    </v-container>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "adminMarkerIndex",
        layout: "admin",
        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },
        validate({params}) {
            return /^[a-zA-Z0-9._-]+$/.test(params.slug)
        },
        async asyncData({ params, $axios, $router}) {
            try {
                let categories = await $axios.get('marker/category/list')
                return {
                    categories:categories.data.data
                }
            } catch (e) {
                $router.push({name: 'error'})
            }
        },
        data() {
            return {
                snackbar:{
                    status:false,
                    timeout:4000,
                    color:'success',
                    message:'',
                },
                e6: 1,
                categories: [],
                tabs: null,
                map: {},
                mapMarker: {},
                toogleMapStyle: false,
                form: new Form({
                    id: -1,
                    category: '',
                    lat: 0,
                    lng: 0,
                    translations: [
                        {locale: 'en', title: '', description: 'test'},
                        {locale: 'hu', title: '', description: 'test'},
                        {locale: 'ua', title: '', description: 'tes'},
                    ],
                }),
                formData:null
            }
        },
       async  mounted() {

           this.map = new google.maps.Map(document.getElementById('gmap_container'), {
                center: {lat: 48.496582, lng: 23.5212107},
                zoom: 8.7,
                minZoom: 8,
                maxZoom: 18,
                disableDefaultUI: true
            })

            this.mapMarker = new google.maps.Marker({
                map: this.map,
                position: {lat: 48, lng: 22.5},
                draggable: true
            });
            this.searchBox(this.map)

           // Load data
           this.formData = await this.$axios.get(`marker/show/${this.$route.params.slug}`)
           if(this.formData.data.data){
               this.form = new Form(this.formData.data.data)
               this.form.category = this.form.marker_category_id
               this.mapMarker.set('position',new google.maps.LatLng(this.form.lat, this.form.lng))
           }
           this.formData = null

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
        },

        methods: {
            styleHandler() {
                let mystyle = this.toogleMapStyle ? this.$store.state.gMapStyles.showLabels : this.$store.state.gMapStyles.hideLabels;
                this.map.set('styles', mystyle);
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
                    self.form.lat = event.latLng.lat();
                    self.form.lng = event.latLng.lng();
                });
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

            autoFilter(item, queryText, itemText) {
                return item.name.toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1
            },
            autoValue(value) {
                return value.id
            },

            async store() {
                console.log(this.form)
                let url = 'marker/store'
                if(this.form.id > 0) url = 'marker/edit'
                await this.form.put(url).then((data)=>{
                    console.log(data.data)
                    this.form.id = data.data.data.id
                    this.snackbar={
                        status:true,
                        timeout:4000,
                        color:'success',
                        message:'messages.saved',
                    }
                }).catch((e)=>{
                    this.snackbar={
                            status:true,
                            timeout:4000,
                            color:'error',
                            message:'messages.not_saved',
                    }
                    console.log(e)
                })
            },
        },
        computed:{
            getLocal(){
                return this.$i18n.locale === 'uk' ? 'ua' : this.$i18n.locale
            }
        }
    }
</script>
<style lang="scss" scoped>
    #gmap_container {
        width: 100%;
        min-height: 400px;
    }
</style>
