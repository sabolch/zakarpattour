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
                                <v-flex xs12 md12 lg5 class="mr-2 ">
                                    <v-text-field
                                            outline
                                            v-model="Fsearch"
                                            clearable
                                            :label="` ${$t('form.search')}..`"
                                            type="text"
                                            @keyup="keyupHandle"

                                            @click:clear="keyupHandle"
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
                                <v-flex xs10 md10 lg4>
                                    <v-select
                                            :label="$t('form.sort_by')"
                                            prepend-inner-icon="filter_list"
                                            :items="sortByItems"
                                            item-text="name"
                                            outline
                                            solo
                                    ></v-select>
                                </v-flex>
                                <v-spacer class="hidden-md-and-down"></v-spacer>
                                <v-flex xs2 md1 class="mb-4">
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" flat outline @click="filters = !filters"
                                               active-class="">
                                            <v-icon>tune</v-icon>
                                        </v-btn>
                                        <span>{{$t('form.data_panel.hint')}}</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
                <v-flex v-for="item in 12" :key="item" xs12 md6 lg3>
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
                                <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#category</span>
                                <v-spacer></v-spacer>
                                <v-btn dark color="indigo" small fab>
                                    <v-icon>favorite</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">Lorem ipsum dolor set amit</h1>
                            <v-rating
                                    color="blue darken-3"
                                    readonly
                                    background-color="grey darken-1"
                                    medium
                                    v-model="rating"
                            ></v-rating>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn icon class="purple--text">
                                <v-icon medium>sb-instagram</v-icon>
                            </v-btn>
                            <v-btn icon class="light-blue--text">
                                <v-icon medium>sb-twitter</v-icon>
                            </v-btn>
                            <v-btn icon class="blue--text text--darken-4">
                                <v-icon medium>sb-facebook</v-icon>
                            </v-btn>
                            <v-spacer/>
                            <v-btn flat class="blue--text"
                                   :to="{name:'sight.show',params: {slug:item}}"
                                   outline
                            >
                                {{$t('btns.read_more')}}
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>


                <v-flex xs12>
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
                        absolute
                        temporary
                        right
                        width="400"
                >
                    <v-list class="pa-1">
                        <v-flex>
                            <v-subheader class="font-weight-black headline text-xs-center">
                                {{ $t('form.data_panel.title') }}
                            </v-subheader>
                        </v-flex>

                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{
                            $t('form.data_panel.rate') }}
                        </v-subheader>
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

                        <v-divider></v-divider>
                        <v-subheader style="margin-bottom: -20px;" class="font-weight-black">{{ $t('form.category') }}
                        </v-subheader>
                        <v-flex xs12>
                            <v-combobox
                                    v-model="types"
                                    :items="typeItems"
                                    :label="$t('form.select')"
                                    hide-selected
                                    chips
                                    clearable
                                    multiple
                                    single-line
                                    class="mx-3"
                            >
                                <template slot="selection" slot-scope="data">
                                    <v-chip
                                            :selected="data.selected"
                                            close
                                            outline
                                            color="indigo"
                                            @input="remove(data.item)"

                                    >
                                        <v-icon left>label</v-icon>
                                        <strong>{{ data.item }}</strong>&nbsp;
                                    </v-chip>
                                </template>
                            </v-combobox>
                        </v-flex>

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
        name: "markerindex",
        head() {
            return {
                title: 'Sights',
            }
        },
        data() {
            return {
                rating: 4,
                rate: [0, 5],
                price: [10, 600],
                filters: false,


                date: new Date().toISOString().substr(0, 10),
                modal: false,

                date2: new Date().toISOString().substr(0, 10),
                modal2: false,

                items: [],
                search: null,
                select: null,
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
                    'Idaho',
                    'Illinois',
                    'Indiana',
                    'Iowa',
                    'Kansas',
                    'Kentucky',
                    'Louisiana',
                    'Maine',
                    'Marshall Islands',
                    'Maryland',
                    'Massachusetts',
                    'Michigan',
                    'Minnesota',
                    'Mississippi',
                    'Missouri',
                    'Montana',
                    'Nebraska',
                    'Nevada',
                    'New Hampshire',
                    'New Jersey',
                    'New Mexico',
                    'New York',
                    'North Carolina',
                    'North Dakota',
                    'Northern Mariana Islands',
                    'Ohio',
                    'Oklahoma',
                    'Oregon',
                    'Palau',
                    'Pennsylvania',
                    'Puerto Rico',
                    'Rhode Island',
                    'South Carolina',
                    'South Dakota',
                    'Tennessee',
                    'Texas',
                    'Utah',
                    'Vermont',
                    'Virgin Island',
                    'Virginia',
                    'Washington',
                    'West Virginia',
                    'Wisconsin',
                    'Wyoming'
                ],


                page: 1,
                total: 8,
                loading: false,

                Fsearch: '',

                typeItems: ['Test 1', 'Test2', 'Test 1', 'Test2', 'Test 1', 'Test2'],
                types: [],
                typeDelay: {},

                sortByItems: [],
                SortBy: '',

                markers: [
                    {
                        id: 5,
                        slug: 'Okey fdksl',
                        translations: [
                            {
                                locale: 'en',
                                title: 'Hello vilag fjdsklfjskld fsdfsd',
                                description: 'dhsjkvnklsd vndkvskdnjksd bsdjkbfs'
                            },
                            {
                                locale: 'hu',
                                title: 'Hello vilag fjdsklfjskld fsdfsd',
                                description: 'dhsjkvnklsd vndkvskdnjksd bsdjkbfs'
                            },
                            {
                                locale: 'ua',
                                title: 'Hello vilag fjdsklfjskld fsdfsd',
                                description: 'dhsjkvnklsd vndkvskdnjksd bsdjkbfs'
                            },
                        ]
                    }
                ],
            }
        },
        mounted() {
            this.sortByItems = $nuxt.$t('marker_sort');
            console.log(this.sortByItems);
            // axios.get('/types/all').then((response) => {
            //     this.typeItems = response.data
            // });
            // this.sendRequest();
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
            }
        },
        methods: {
            readMore: function (slug) {
                this.$emit('eventname', {name: 'markercomponent', value: slug});
                // window.location.href = '/routes/route/' + slug;
            },
            keyupHandle(event) {
                clearTimeout(this.timeoutId);
                if (event.key == 'Enter') {
                    this.page = 1;
                    this.getDataFromServer();
                    return;
                }
                this.timeoutId = setTimeout(() => {
                    this.page = 1;
                    this.getDataFromServer();
                }, 700);
            },
            remove(item) {
                this.types.splice(this.types.indexOf(item), 1);
                this.types = [...this.types]
            },
            getDataFromApi(url) {
                this.loading = true;
                axios.get(url).then((response) => {
                    this.loading = false;
                    this.markers = response.data.data;
                    this.page = response.data.current_page;
                    this.total = response.data.last_page;
                    this.drawMarkers();
                    console.log(response.data)
                }).catch((response) => {
                    this.loading = false
                });
                // .finaly(()=>{}) on IE not works
            },
            sendRequest() {
                this.getDataFromApi(`/markers/map?page=${this.page}&types=${JSON.stringify(this.types)}&per_page=9`);
            },

            querySelections(v) {
                this.loading = true
                // Simulated ajax query
                setTimeout(() => {
                    this.items = this.states.filter(e => {
                        return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
                    })
                    this.loading = false
                }, 500)
            }
        },
        computed: {}
    }
</script>

<style scoped>

</style>
