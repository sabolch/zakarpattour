<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-stepper v-model="e6" vertical>
                    <v-stepper-step editable :complete="e6 > 1" step="1" color="green">
                        {{$t('tours_steps[0].title')}}
                        <small>{{$t('tours_steps[0].subtitle')}}</small>
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
                                                :label="`${$t('form.title')} ${item.locale}`"
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
                                :label="$t('form.category')"
                                persistent-hint
                                item-value="id"
                                :filter="autoFilter"
                        >
                            <template slot="selection"
                                      slot-scope="{ item, index }"
                            >
                                <v-icon color="primary">label</v-icon>
                                <span>&nbsp;&nbsp; {{ item.translations.find(obj => obj.locale ===  getLocal).name }}</span>
                            </template>

                            <template slot="item"
                                      slot-scope="{ item, index }"
                            >
                                <v-icon color="primary">label</v-icon>
                                <span>&nbsp;&nbsp;{{ item.translations.find(obj => obj.locale ===  getLocal).name }}</span>
                            </template>

                        </v-autocomplete>


                        <v-flex xs12>
                            <v-text-field
                                    class="mx-3"
                                    label="Price"
                                    :label="$t('form.data_panel.price')"
                                    v-model="form.price"
                                    type="number"
                                    :max="50000"
                                    :min="1"
                            ></v-text-field>
                            <v-text-field
                                    class="mx-3"
                                    :label="$t('form.data_panel.duration')"
                                    v-model="form.duration"
                                    type="number"
                                    :max="100"
                                    :min="1"
                            ></v-text-field>
                            <v-text-field
                                    class="mx-3"
                                    :label="$t('form.person')"
                                    v-model="form.person"
                                    type="number"
                                    :max="100"
                                    :min="1"
                            ></v-text-field>
                            <v-dialog
                                    ref="dialog1"
                                    v-model="modal"
                                    :return-value.sync="form.start_date"
                                    persistent
                                    lazy
                                    full-width
                            >
                                <v-text-field
                                        :label="$t('form.data_panel.start_date')"
                                        slot="activator"
                                        class="mx-3"
                                        v-model="form.start_date"
                                        prepend-inner-icon="event"
                                        readonly
                                ></v-text-field>
                                <v-date-picker :disabled="loading" v-model="form.start_date" :locale="$i18n.locale"
                                               scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="modal = false">{{ $t('btns.cancel') }}</v-btn>
                                    <v-btn flat color="primary" @click="$refs.dialog1.save(form.start_date)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>

                        <v-flex xs12>
                            <v-dialog
                                    ref="dialog"
                                    v-model="modal2"
                                    :return-value.sync="form.end_date"
                                    persistent
                                    lazy
                                    full-width
                            >
                                <v-text-field
                                        :label="$t('form.data_panel.end_date')"
                                        slot="activator"
                                        class="mx-3"
                                        v-model="form.end_date"
                                        prepend-inner-icon="event"
                                        readonly
                                ></v-text-field>
                                <v-date-picker :disabled="loading" v-model="form.end_date" :locale="$i18n.locale"
                                               scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="modal2 = false">{{ $t('btns.cancel') }}</v-btn>
                                    <v-btn flat color="primary" @click="$refs.dialog.save(form.end_date)">{{ $t('btns.ok') }}</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>
                        <v-flex xs12 class="pl-3 pr-3">
                                    <v-combobox
                                            v-model="form.available_dates"
                                            multiple
                                            chips
                                            :label="$t('form.available_dates')"
                                            prepend-icon="event"
                                            readonly
                                    >
                                        <template slot="selection" slot-scope="data">
                                            <v-chip
                                                    close
                                                    outline
                                                    color="indigo"
                                                    @input="removeDates(data.index)"
                                            >
                                                <v-icon left>event</v-icon>
                                                <strong>{{data.item}}</strong>&nbsp;
                                            </v-chip>
                                        </template>
                                    </v-combobox>
                        </v-flex>
                        <v-flex xs12 class="text-xs-center">
                            <v-date-picker
                                    v-model="form.available_dates"
                                    :min="form.start_date"
                                    :max="form.end_date"
                                    multiple
                                    :locale="$i18n.locale"
                            ></v-date-picker>
                        </v-flex>

                        <v-btn color="primary" @click="e6 = 2">{{$t('btns.continue')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step  :complete="e6 > 2" step="2" color="purple">  {{$t('tours_steps[1].title')}}
                        <small>{{$t('tours_steps[1].subtitle')}}</small>
                    </v-stepper-step>

                    <v-stepper-content step="2">
                        <v-container fluid pa-0 ma-0 pb-2>
                            <v-layout row wrap>

                                <v-flex xs12>
                                    <v-autocomplete
                                            class="pl-3 pr-3"
                                            v-model="form.markers"
                                            :loading="loading"
                                            :items="sights"
                                            :search-input.sync="sightSearch"
                                            chips
                                            clearable
                                            hide-details
                                            item-text="title"
                                            :label="$t('sights_search')"
                                            multiple
                                            single-line
                                            :disabled="loading"
                                            return-object
                                            :filter="autoFilterSights"
                                    >
                                        <template v-slot:no-data>
                                            <v-list-tile>
                                                <v-list-tile-title>
                                                    {{$t('sights_search')}}
                                                </v-list-tile-title>
                                            </v-list-tile>
                                        </template>
                                        <template v-slot:item="{ item }">
                                            <v-list-tile-avatar
                                                    color="indigo"
                                                    class="headline font-weight-light white--text"
                                            >
                                                <v-icon dark>place</v-icon>
                                            </v-list-tile-avatar>
                                            <v-list-tile-content>
                                                <v-list-tile-title v-text="titleTrim(item)"></v-list-tile-title>
                                                <v-list-tile-sub-title
                                                        v-text="subtitleTrim(item)"></v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </template>
                                        <template slot="selection" slot-scope="data">
                                            <v-chip
                                                    :selected="data.selected.title"
                                                    close
                                                    outline
                                                    color="indigo"
                                                    @input="removeFromSights(data.item.id)"

                                            >
                                                <v-icon left>place</v-icon>
                                                <strong>{{ titleTrim(data.item) }}</strong>&nbsp;
                                            </v-chip>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>


                                <v-flex xs12 mt-4 mb-4>
                                    <v-expansion-panel focusable>
                                        <v-expansion-panel-content
                                                v-for="(item,i) in form.markers"
                                                :key="item.slug"
                                        >
                                            <template v-slot:header>
                                                <div>{{(i+1)}}. <v-icon small color="primary">location_on</v-icon> {{ getTitle(item)}}</div>
                                            </template>
                                            <v-card>

                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
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
                                                    {{$t('no_directions')}}
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
                                                    color="amber"
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
                            </v-layout>
                        </v-container>
                        <v-btn color="primary" @click="setDirectionData">{{$t('btns.continue')}}</v-btn>
                        <v-btn @click="e6 = e6-1" flat>{{$t('btns.back')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step color="amber" :complete="e6 > 3" step="3">  {{$t('tours_steps[2].title')}}
                        <small>{{$t('tours_steps[2].subtitle')}}</small>
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
                                        <quill-editor :content="item.description"
                                                      v-model="item.description"></quill-editor>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                        <v-btn color="primary" @click="e6 = 4">{{$t('btns.continue')}}</v-btn>
                        <v-btn @click="e6 = e6-1" flat>{{$t('btns.back')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step color="red lighten-1" :complete="e6 > 4" step="4">{{$t('tours_steps[3].title')}}
                        <small>{{$t('tours_steps[3].subtitle')}}</small>
                    </v-stepper-step>
                    <v-stepper-content step="4">
                        <v-btn dark color="green" :loading="form.busy" @click="store">{{$t('btns.save')}}</v-btn>
                        <v-btn
                                v-if="form.slug"
                                dark
                                color="purple"
                                :to="{name:'tour.show',params: {slug:form.slug}}"
                                target="_blank"
                        >{{$t('btns.show')}}</v-btn>
                                <v-btn
                                        :disabled="form.id < 1"
                                        color="primary"
                                        @click="e6 = 5"
                                >
                                    {{$t('btns.continue')}}
                                </v-btn>
                        <v-btn dark color="orange" @click="e6 = e6-1">{{$t('btns.back')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step step="5">  {{$t('tours_steps[4].title')}}
                        <small>{{$t('tours_steps[4].subtitle')}}</small>
                    </v-stepper-step>
                    <v-stepper-content step="5">
                        <v-flex>
                         <image-upload
                                 :itemID="form.id"
                                 type="tour"
                                 ref="imgUploadComponent"
                         ></image-upload>
                        </v-flex>
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
    import VButton from "../../../components/global/Button";
    import ImageUpload from "../../../components/admin/image-upload";


    export default {
        name: "adminTourIndex",
        components: {ImageUpload, VButton},
        layout: "admin",
        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },
        validate({params}) {
            return /^[a-zA-Z0-9._-]+$/.test(params.slug)
        },
        async asyncData({params, $axios, redirect}) {
            try {
                let categories = await $axios.get('tour/category/list')
                return {
                    categories: categories.data.data
                }
            } catch (e) {
                redirect('/error')
            }
        },
        data() {
            return {
                menu: false,

                modal: false,
                modal2: false,

                snackbar: {
                    status: false,
                    timeout: 4000,
                    color: 'success',
                    message: '',
                },

                e6: 1,
                categories: [],
                tabs: null,

                map: {},
                toogleMapStyle: false,

                form: new Form({
                    id: -1,
                    category: '',
                    price:0,
                    duration:0,
                    available_dates:[],
                    markers:[],
                    marker_ids:[],
                    directions:{},
                    start_date: new Date().toISOString().substr(0, 10),
                    end_date: new Date().toISOString().substr(0, 10),
                    translations: [
                        {locale: 'en', title: '', description: 'test'},
                        {locale: 'hu', title: '', description: 'test'},
                        {locale: 'ua', title: '', description: 'tes'},
                    ],
                }),
                formData: null,

                loading: false,
                sightItems: [],
                sightSearch: null,

                selectedSights: [],
                sights: [],
                timeoutId:{},


                directionsService: {},
                directionsDisplay: {},
                travelMode: 'DRIVING',
                travelModes: ['DRIVING', 'WALKING', 'BICYCLING', 'TRANSIT'],
                showmenu: false,
                fab: false,
                right: false,
                alert: false,
                drawTimer: {},
            }
        },
        watch: {
            sightSearch(val) {
                clearTimeout(this.timeoutId);
                this.timeoutId = setTimeout(()=>{
                    val && this.loadSights()
                }, 800);

            },
            'form.markers': function (v) {
                clearTimeout(this.drawTimer)
                this.drawTimer = setTimeout(() => {
                    this.drawDirection

                }, 800)
            },
            'form.id':function (v) {
                this.$refs.imgUploadComponent.loadData(v)
            }
        },
        async mounted() {
            this.map = new google.maps.Map(document.getElementById('gmap_container'), {
                center: {lat: 48.496582, lng: 23.5212107},
                zoom: 8.7,
                minZoom: 8,
                maxZoom: 18,
                disableDefaultUI: true
            })


            // Load data
            if (this.$route.params.slug) {
                this.formData = await this.$axios.get(`tour/show/${this.$route.params.slug}`)
                this.form = new Form(this.formData.data.data)
                this.form.category = this.form.tour_category_id
                this.map.set('position', new google.maps.LatLng(this.form.lat, this.form.lng))
            }
            this.formData = null

            let self = this;
            if (google && google.maps) {

                this.directionsService = new google.maps.DirectionsService;
                this.directionsDisplay = new google.maps.DirectionsRenderer({
                    draggable: true,
                    map: self.map,
                    panel: document.getElementById('d_panel')
                });

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
            setDirectionData(){
               this.form.directions = JSON.stringify(this.directionsDisplay.directions)
                this.e6 = 3
            },

            async loadSights() {
                this.loading = true
                let url = `marker?page=1&limit=10&q=${this.sightSearch.trim()}`
                const {data} = await this.$axios.get(url)
                this.sights = [...this.sights, ...data.data]
                this.loading = false
            },

            async querySelections(v) {
                // clearTimeout(this.timeoutId);
                // this.timeoutId = setTimeout(this.loadSights(v), 800);
            },

            titleTrim(value) {
                return value.translations.find(obj => obj.locale === this.$i18n.locale).title.substr(0, 20)
            },
            subtitleTrim(value) {
                return value.translations.find(obj => obj.locale === this.$i18n.locale).title
            },
            removeFromSights(itemID) {
                let index = this.form.markers.findIndex(obj => obj.id === itemID)
                this.form.markers.splice(index, 1);
            },
            removeDates(index) {
                this.form.available_dates.splice(index, 1);
            },
            styleHandler() {
                let mystyle = this.toogleMapStyle ? this.$store.state.gMapStyles.showLabels : this.$store.state.gMapStyles.hideLabels;
                this.map.set('styles', mystyle);
            },

            autoFilter(item, queryText, itemText) {
                return item.name.toLocaleLowerCase().indexOf(queryText.trim().toLocaleLowerCase()) > -1
            },

            autoValue(value) {
                return value.id
            },

            async store() {
                this.form.marker_ids = JSON.stringify(this.form.markers.map(item => {return item.id}))
                this.form.dates = JSON.stringify(this.form.available_dates)
                let url = 'tour/store'
                if (this.form.id > 0) url = 'tour/edit'
                await this.form.put(url).then((data) => {
                    this.form.id = data.data.data.id
                    this.snackbar = {
                        status: true,
                        timeout: 4000,
                        color: 'success',
                        message: 'messages.saved',
                    }
                }).catch((e) => {
                    this.snackbar = {
                        status: true,
                        timeout: 4000,
                        color: 'error',
                        message: 'messages.not_saved',
                    }
                    // console.log(e)
                })
            },

            generateMarkers: function () {
                return this.form.markers.map(item => {
                    return {
                        location: new google.maps.LatLng(item.lat,item.lng),
                        stopover: true
                    }
                });
            },

            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            },
            setMapCenter(){
                this.map.setCenter({lat: 48.496582, lng: 23.5212107});
            },
            autoFilterSights(item, queryText, itemText) {
                // console.log(item,queryText,itemText)
                return this.getTitle(item).toLocaleLowerCase().indexOf(queryText.trim().toLocaleLowerCase()) > -1
            },
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            },

            drawDirection: function () {
                let waypts = this.generateMarkers();

                if (waypts.length < 2) return


                let self = this;
                this.directionsService.route({
                    origin: waypts.shift().location,
                    destination: waypts.pop().location,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    provideRouteAlternatives: true,
                    travelMode: self.travelMode
                }, function (response, status) {
                    if (status === 'OK') {
                        self.directionsDisplay.setDirections(response);
                        this.DIRECTION = response
                        // let route = response.routes[0];
                        self.alert = false;
                    } else {
                        // console.log(response, status)
                        // window.alert('Directions request failed due to ' + status);
                        self.alert = true;
                    }
                });
            }
        }
    }
</script>
<style lang="scss" scoped>
    #gmap_container {
        width: 100%;
        min-height: 600px;
    }
    #d_panel {
        /*overflow-x: scroll;*/
        word-wrap: break-word;
    }
    .adp-step, .adp-substep {
        max-width: 200px;
    }
    .v-speed-dial__list {
        top: -170px;
    }
    .v-speed-dial--absolute, .v-speed-dial--fixed {
        z-index: 1
    }
</style>

