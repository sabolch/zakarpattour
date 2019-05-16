<template>
    <div>
        <v-container pa-2 fluid fill-height grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card>
                        <v-container
                                fluid
                                grid-list-md
                                pb-0 mb-0
                        >
                            <v-layout
                                    row
                                    wrap
                                    align-center
                            >
                                <v-flex xs12 md12 lg5 class="mr-2">
                                    <v-text-field
                                            outline
                                            v-model="search"
                                            clearable
                                            :label="` ${$t('form.search')}..`"
                                            type="text"
                                            @keyup="keyupHandle"

                                            @click:clear="keyupHandle"
                                            :disabled="loading"
                                    >
                                        <v-tooltip
                                                slot="prepend"
                                                bottom
                                        >
                                            <v-icon slot="activator">
                                                mdi-help-circle-outline
                                            </v-icon>
                                            $t('form.search')
                                        </v-tooltip>
                                        <v-fade-transition slot="append">
                                            <v-progress-circular
                                                    v-if="loading"
                                                    size="24"
                                                    color="info"
                                                    indeterminate
                                            ></v-progress-circular>
                                            <v-icon
                                                    v-else
                                            >
                                                search
                                            </v-icon>
                                        </v-fade-transition>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs8 md10 lg4>
                                    <v-select
                                            v-model="sortBy"
                                            :label="$t('form.sort_by')"
                                            prepend-inner-icon="filter_list"
                                            :items="$t('marker_sort')"
                                            item-text="name"
                                            outline
                                            solo
                                            :disabled="loading"
                                    ></v-select>
                                </v-flex>
                                <v-spacer class="hidden-md-and-down"></v-spacer>
                                <v-flex xs2 md1 class="mb-4">
                                    <v-tooltip bottom>
                                        <v-btn   slot="activator" flat outline @click="filters = !filters" active-class="">
                                            <v-icon>tune</v-icon>
                                        </v-btn>
                                        <span>{{$t('form.data_panel.hint')}}</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
                <v-flex xs12 v-if="tours.length <= 0" >
                    <v-layout row wrap justify-center>
                        <v-flex xs12 md10 lg6>
                            <v-container fluid>
                                <v-img
                                        :src="'/images/nodata-found.png'"
                                        min-height="250"
                                >
                                    <v-layout
                                            slot="placeholder"
                                            fill-height
                                            align-center
                                            justify-center
                                            ma-0
                                    >
                                        <v-progress-circular indeterminate color="grey lighten-5"/>
                                    </v-layout>
                                </v-img>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex v-if="tours.length > 0" v-for="item in tours" :key="item.slug" xs12 md6 lg3>
                    <v-card class="my-1">
                        <v-img
                                :src="'/images/Munkacs_vara.jpg'"
                                aspect-ratio="1"
                                height="300px"
                                class="grey lighten-2"
                        >
                            <v-layout
                                    slot="placeholder"
                                    fill-height
                                    align-center
                                    justify-center
                                    ma-0
                            >
                                <v-progress-circular indeterminate color="grey lighten-5"/>
                            </v-layout>

                            <v-card-actions>
                                <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#{{getName(item.category)}}</span>
                                <v-spacer></v-spacer>
                                <btn-favorite type="tour" :itemID="item.id"></btn-favorite>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">{{getTitle(item) }}</h1>
                            <v-rating
                                    color="blue darken-3"
                                    readonly
                                    background-color="grey darken-1"
                                    medium
                                    v-model="rating"
                            ></v-rating>
                        </v-card-text>
                        <v-card-actions>
                            <share-btns></share-btns>
                            <v-spacer/>
                            <v-btn flat class="blue--text"
                                   :to="{name:'tour.show',params: {slug:item.slug}}"
                                   outline
                            >
                                {{$t('btns.read_more')}}
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>


                <v-flex xs12 v-if="tours.length > 0">
                    <v-card style="padding:10px">
                        <v-layout justify-center>
                            <v-pagination
                                    v-model="page"
                                    :length="total"
                                    :total-visible="7"
                                    :disabled="loading"
                            ></v-pagination>
                        </v-layout>
                    </v-card>
                </v-flex>


                <!--Result Filter-->
                <v-navigation-drawer
                        v-model="filters"
                        :disable-resize-watcher="true"
                        :hide-overlay="true"
                        :width="breakpoint ? 300 : 400"
                        absolute
                        temporary
                        right
                >
                    <v-list class="pa-1">
                        <v-flex>
                            <v-layout row pa-2>
                                <v-subheader class="font-weight-black headline text-xs-center">Filtering results</v-subheader>
                                <v-spacer></v-spacer>
                                <v-btn
                                        icon
                                        small
                                        fab
                                        @click="filters = false"
                                >
                                    <v-icon>
                                        close
                                    </v-icon>
                                </v-btn>
                            </v-layout>
                        </v-flex>
                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">Category</v-subheader>
                        <v-flex xs12>
                            <v-autocomplete
                                    class="pl-3 pr-3"
                                    v-model="types"
                                    label="Filter by categories"
                                    :items="typeItems"
                                    multiple
                                    chips
                                    :disabled="loading"
                                    hide-selected
                                    persistent-hint
                                    :filter="autoFilter"
                                    :item-value="autoValue"
                            >
                                <template slot="item" slot-scope="data">
                                    <v-icon left>label</v-icon>
                                    {{ getName(data.item) }}
                                </template>
                                <template slot="selection" slot-scope="data">
                                    <v-chip
                                            :selected="data.selected"
                                            close
                                            outline
                                            color="indigo"
                                            @input="remove(data.item)"

                                    >
                                        <v-icon left>label</v-icon>
                                        <strong> {{ getName(data.item) }}</strong>&nbsp;
                                    </v-chip>
                                </template>
                            </v-autocomplete>
                        </v-flex>

                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{$t('navbar.sights')}}</v-subheader>
                        <v-flex xs12>
                            <v-autocomplete
                                    class="pl-3 pr-3"
                                    v-model="selectedSights"
                                    :loading="loading"
                                    :items="sights"
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
                                    :disabled="loading"
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
                            <v-divider></v-divider>
                            <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{$t('form.data_panel.price')}}</v-subheader>
                            <v-layout row pa-2>
                                <v-flex shrink xs2>
                                    <v-text-field
                                            v-model="price[0]"
                                            class="mt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex class="px-3" xs7>
                                    <v-range-slider
                                            v-model="price"
                                            :max="15000"
                                            :min="10"
                                            :step="10"
                                            thumb-size="100"
                                    ></v-range-slider>
                                </v-flex>

                                <v-flex shrink xs3>
                                    <v-text-field
                                            v-model="price[1]"
                                            class="mt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>


                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">Duartion </v-subheader>
                        <v-layout row pa-2>
                            <v-flex shrink xs2>
                                <v-text-field
                                        v-model="duration[0]"
                                        class="mt-0"
                                        hide-details
                                        single-line
                                        type="number"
                                ></v-text-field>
                            </v-flex>

                            <v-flex class="px-3" xs8>
                                <v-range-slider
                                        v-model="duration"
                                        :max="50"
                                        :min="1"
                                        :step="1"
                                ></v-range-slider>
                            </v-flex>

                            <v-flex shrink xs2>
                                <v-text-field
                                        v-model="duration[1]"
                                        class="mt-0"
                                        hide-details
                                        single-line
                                        type="number"
                                ></v-text-field>
                            </v-flex>
                        </v-layout>



                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{$t('form.data_panel.start_date')}}</v-subheader>
                        <v-flex xs12>
                            <v-dialog
                                    ref="dialog1"
                                    v-model="modal"
                                    :return-value.sync="start_date"
                                    persistent
                                    lazy
                                    full-width
                            >
                                <v-text-field
                                        slot="activator"
                                        class="mx-3"
                                        v-model="start_date"
                                        prepend-inner-icon="event"
                                        readonly
                                ></v-text-field>
                                <v-date-picker :disabled="loading" v-model="start_date" :locale="$i18n.locale" scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="modal = false">{{ $t('btns.cancel') }}</v-btn>
                                    <v-btn flat color="primary" @click="$refs.dialog1.save(start_date)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>

                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{ $t('form.data_panel.end_date') }}</v-subheader>
                        <v-flex xs12>
                            <v-dialog
                                    ref="dialog"
                                    v-model="modal2"
                                    :return-value.sync="end_date"
                                    persistent
                                    lazy
                                    full-width
                            >
                                <v-text-field
                                        slot="activator"
                                        class="mx-3"
                                        v-model="end_date"
                                        prepend-inner-icon="event"
                                        readonly
                                ></v-text-field>
                                <v-date-picker :disabled="loading" v-model="end_date" :locale="$i18n.locale" scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="modal2 = false">{{ $t('btns.cancel') }}</v-btn>
                                    <v-btn flat color="primary" @click="$refs.dialog.save(end_date)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>



                            <v-divider></v-divider>
                            <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{$t('form.data_panel.rate')}}</v-subheader>
                            <v-layout row pa-2>
                                <v-flex shrink xs2>
                                    <v-text-field
                                            v-model="rate[0]"
                                            class="mt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex class="px-3" xs8>
                                    <v-range-slider
                                            v-model="rate"
                                            :max="5"
                                            :min="0"
                                            :step="1"
                                    ></v-range-slider>
                                </v-flex>

                                <v-flex shrink xs2>
                                    <v-text-field
                                            v-model="rate[1]"
                                            class="mt-0"
                                            hide-details
                                            single-line
                                            type="number"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                    </v-list>
                </v-navigation-drawer>
            </v-layout>

        </v-container>
        <v-content v-show="false" style="min-height: 90vh; margin:0; padding: 0;">
            <v-container bg fill-height grid-list-md text-xs-center>
                <v-layout row wrap align-center>
                    <v-flex>
                        <div class="display-1 font-weight-black">
                            <p class="display-3 font-weight-black">404</p>
                            {{ $t('errors.something') }}
                        </div>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

    </div>
</template>
<script>
    export default {
        name: "tourindex",
        async asyncData({params, $axios, $router}) {
            try {
                const {data} = await $axios.get('tour?limit=12')
                return {
                    tours: data.data,
                    page :data.meta.current_page,
                    total : data.meta.last_page
                }
            } catch (e) {
                $router.push({name: 'error'})
            }
        },

        head() {
            return {
                title: this.$t('navbar.tours'),
            }
        },
        data() {
            return {
                rating: 4,
                rate: [0, 5],
                price: [10, 15000],
                duration: [1, 50],
                filters: false,

                start_date: new Date().toISOString().substr(0, 10),
                modal: false,

                end_date: new Date(),
                modal2: false,

                tours: [],
                select: null,

                page: 1,
                total: 8,
                loading: false,

                search: null,
                items: [],

                typeItems: [],
                types: [],
                typeDelay: {},

                sortByItems: [],
                sortBy: '',

                selectedSights:[],
                sights:[],

                sightSearch:null,
                priceDelay:{},
                durationDelay:{},
                selectedSightsDelay:{}
            }
        },
       async mounted() {
            this.end_date = new Date(this.end_date.getFullYear(),this.end_date.getMonth()+7,this.end_date.getDay())
                .toISOString().substr(0, 10)

            try {
                const {data} = await this.$axios.get('tour/category')
                this.typeItems = data.data
            } catch (e) {
                console.log('Failed to load categoties')
            }
        },
        watch: {
            types: function () {
                clearTimeout(this.typeDelay);
                this.typeDelay = setTimeout(() => {
                    this.page = 1;
                    this.sendRequest();
                }, 800);
            },
            page: function () {
                this.sendRequest();
            },
            search(val) {
                val && val !== this.select && this.querySelections(val)
            },
            sortBy: function () {
                this.sendRequest();
            },
            sightSearch (val) {
                val &&  this.loadSights()
            },
            price(val) {
                clearTimeout(this.priceDelay);
                this.priceDelay = setTimeout(() => {
                    this.page = 1;
                    this.sendRequest();
                }, 700);
            },
            duration(val) {
                clearTimeout(this.durationDelay);
                this.durationDelay = setTimeout(() => {
                    this.page = 1;
                    this.sendRequest();
                }, 700);
            },
            selectedSights(v){
                clearTimeout(this.selectedSightsDelay);
                this.selectedSightsDelay = setTimeout(() => {
                    this.page = 1;
                    this.sendRequest();
                }, 700);
            },
            start_date(v){
                this.sendRequest();
            },
            end_date(v){
                this.sendRequest();
            }
        },
        methods: {
            keyupHandle(event) {
                clearTimeout(this.timeoutId);
                if (event.key == 'Enter') {
                    this.page = 1;
                    this.sendRequest();
                    return;
                }
                this.timeoutId = setTimeout(() => {
                    this.page = 1;
                    this.sendRequest();
                }, 700);
            },
            remove(item) {
                this.types.splice(this.types.indexOf(item), 1);
                this.types = [...this.types]
            },
            getDataFromApi(url) {
                this.loading = true
                this.$axios.get(url).then((response) => {
                    this.tours = response.data.data
                    this.page = response.data.meta.current_page
                    this.total = response.data.meta.last_page
                    this.loading = false
                }).catch((response) => {
                    this.loading = false
                });
                // .finaly(()=>{}) on IE not works
            },


            sendRequest() {
                let url = `tour?page=${this.page}&limit=12&category=${JSON.stringify(this.types)}&price=${JSON.stringify(this.price)}&duration=${JSON.stringify(this.duration)}&local=${this.getLocal}&start_date=${this.start_date}&end_date=${this.end_date}`
                if (this.search) {
                    url += `&q=${this.search}`
                }
                if (this.order) {
                    url += `&order=${this.sortBy}`
                }
                if(this.selectedSights.length > 0){
                    url += `&sights=${JSON.stringify(this.selectedSights)}`
                }
                this.getDataFromApi(url)
            },

            async loadSights(){
                this.loading = true
                let url = `marker?page=1&limit=10&q=${this.sightSearch.trim()}`
                const {data} = await this.$axios.get(url)
                this.sights = data.data.slice(0)
                this.loading = false
            },

            querySelections(v) {
                // // Simulated ajax query
                // setTimeout(() => {
                //     this.items = this.states.filter(e => {
                //         return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
                //     })
                // }, 500)
            },

            getTitle(item) {
                return item.translations.find(obj => obj.locale === this.getLocal).title
            },
            getName(item) {
                return item.translations.find(obj => obj.locale ===  this.getLocal).name
            },
            autoFilter(item, queryText, itemText) {
                return this.getName(item).toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1
            },
            titleTrim(value){
                return  value.translations.find(obj => obj.locale ===  this.$i18n.locale).title.substr(0,20)
            },
            subtitleTrim(value){
                return  value.translations.find(obj => obj.locale ===  this.$i18n.locale).title
            },
            removeFromSights(itemID) {
                this.selectedSights.splice(this.selectedSights.indexOf(itemID), 1);
            },
            autoValue(value) {
                return value.id
            },

        },
        computed: {
            getLocal(){
                return this.$i18n.locale === 'uk' ? 'ua' : this.$i18n.locale
            },
            breakpoint() {
                return this.$vuetify.breakpoint.xs
            }
        }
    }
</script>

<style scoped>

</style>
