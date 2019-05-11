<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-stepper v-model="e6" vertical>
                    <v-stepper-step editable :complete="e6 > 1" step="1">
                        Tour
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
                                <span>&nbsp;&nbsp;{{ item.translations.find(obj => obj.locale ===  getLocal).name }}</span>
                            </template>

                        </v-autocomplete>

                        <v-btn color="primary" @click="e6 = 2">{{$t('btns.continue')}}</v-btn>
                    </v-stepper-content>

                    <v-stepper-step editable :complete="e6 > 2" step="2">Title & category
                        <small>Give a title and translate it also set the category</small>
                    </v-stepper-step>

                    <v-stepper-content step="2">
                        <v-container fluid pa-0 ma-0 pb-2>
                            <v-layout row wrap>
                                <v-flex m6>
                                    <v-btn  color="success">Add Tour</v-btn>
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
                                <v-flex xs12>
                                    <v-autocomplete
                                            v-model="sightSelects"
                                            :loading="sightLoading"
                                            :items="sightItems"
                                            :search-input.sync="sightSearch"
                                            chips
                                            clearable
                                            hide-details
                                            hide-selected
                                            item-text="title"
                                            item-value="id"
                                            label="Search for a sight.."
                                            multiple
                                            single-line
                                            :disabled="sightLoading"
                                    >
                                        <template v-slot:no-data>
                                            <v-list-tile>
                                                <v-list-tile-title>
                                                    Search for sights ..
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
                                                <v-list-tile-sub-title v-text="subtitleTrim(item)"></v-list-tile-sub-title>
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
        name: "adminTourIndex",
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
                let categories = await $axios.get('tour/category/list')
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
                mapTour: {},
                toogleMapStyle: false,
                form: new Form({
                    id: -1,
                    category: '',
                    translations: [
                        {locale: 'en', title: '', description: 'test'},
                        {locale: 'hu', title: '', description: 'test'},
                        {locale: 'ua', title: '', description: 'tes'},
                    ],
                }),
                formData:null,

                sightLoading: false,
                sightItems: [],
                sightSearch: null,
                sightSelects: null,

                states: [
                    'Alabama',
                    'Alaska',
                    'American Samoa',
                    'Arizona',
                    'Arkansas',
                    'California',
                    'Colorado',
                    'Connecticut',
                    'Delaware',
                    'District of Columbia',
                    'Federated States of Micronesia',
                    'Florida',
                    'Georgia',
                    'Guam',
                    'Hawaii',
                    'Idaho'
                ],
            }
        },
        watch: {
            sightSearch (val) {
                val &&  this.loadSights()
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

            this.mapTour = new google.maps.Marker({
                map: this.map,
                position: {lat: 48, lng: 22.5},
                draggable: true
            });

            // Load data
            this.formData = await this.$axios.get(`tour/show/${this.$route.params.slug}`)
            if(this.formData.data.data){
                this.form = new Form(this.formData.data.data)
                this.form.category = this.form.tour_category_id
                this.mapTour.set('position',new google.maps.LatLng(this.form.lat, this.form.lng))
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

            async loadSights(){
                this.sightLoading = true
                let url = `marker?page=1&per_page=10&q=${this.sightSearch.trim()}`
                const {data} = await this.$axios.get(url)
                this.sightItems = data.data.slice(0)
                this.sightLoading = false
            },

            async querySelections (v) {
                clearTimeout(this.timeoutId);
                this.timeoutId = setTimeout(this.loadSights(v), 700);
            },

            titleTrim(value){
                return  value.translations.find(obj => obj.locale ===  this.$i18n.locale).title.substr(0,20)
            },
            subtitleTrim(value){
                return  value.translations.find(obj => obj.locale ===  this.$i18n.locale).title
            },
            removeFromSights(itemID) {
                this.sightSelects.splice(this.sightSelects.indexOf(itemID), 1);
            },

            styleHandler() {
                let mystyle = this.toogleMapStyle ? this.$store.state.gMapStyles.showLabels : this.$store.state.gMapStyles.hideLabels;
                this.map.set('styles', mystyle);
            },

            autoFilter(item, queryText, itemText) {
                return item.name.toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1
            },
            autoValue(value) {
                return value.id
            },

            async store() {
                console.log(this.form)
                let url = 'tour/store'
                if(this.form.id > 0) url = 'tour/edit'
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
                return this.$i18n.locale
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
