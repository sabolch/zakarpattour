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
                <v-flex xs12 v-if="sights.length <= 0" >
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
                <v-flex v-if="sights.length > 0" v-for="item in sights" :key="item.title" xs12 md6 lg3>
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
                                <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#{{getName(item.category.translations)}}</span>
                                <v-spacer></v-spacer>
                                <btn-favorite type="sight" :itemID="item.id"></btn-favorite>
                            </v-card-actions>
                        </v-img>
                        <v-card-text class="align-center text-xs-justify pa-2">
                            <h1 class="headline">{{ getTitle(item.translations) }}</h1>
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
                                   :to="{name:'sight.show',params: {slug:item.slug}}"
                                   outline
                            >
                                {{$t('btns.read_more')}}
                            </v-btn>
                        </v-card-actions>

                    </v-card>
                </v-flex>


                <v-flex xs12 v-if="sights.length > 0">
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
                        right
                >
                    <v-list class="pa-1">
                        <v-layout row pa-2>
                            <v-subheader class="font-weight-black headline">
                                {{ $t('form.data_panel.title') }}
                            </v-subheader>
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
                            <v-autocomplete
                                    v-model="types"
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
                                    {{ getName(data.item.translations) }}
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
                                        <strong> {{ getName(data.item.translations) }}</strong>&nbsp;
                                    </v-chip>
                                </template>
                            </v-autocomplete>
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
    import BtnFavorite from "../../components/global/btn-favorite";
    import ShareBtns from "../../components/global/share-btns";

    export default {
        name: "markerindex",
        components: {ShareBtns, BtnFavorite},
        async asyncData({params, $axios, $router}) {
            try {
                const {data} = await $axios.get('marker?limit=12')
                return {
                    sights: data.data,
                    page :data.meta.current_page,
                    total : data.meta.last_page
                }
            } catch (e) {
                $router.push({name: 'error'})
            }
        },

        head() {
            return {
                title: this.$t('navbar.sights'),
            }
        },

        data() {
            return {
                rating: 4,
                rate: [0, 5],
                filters: false,

                sights: [],

                items: [],
                search: null,
                select: null,

                page: 1,
                total: 1,
                loading: false,


                typeItems: [],
                types: [],
                typeDelay: {},

                sortBy: null,
            }
        },
        async mounted() {
            try {
                const {data} = await this.$axios.get('marker/category')
                this.typeItems = data.data
            } catch (e) {
                console.log('Failed to load categoties')
            }


        },
        watch: {
            types: function (v) {
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
                    this.sights = response.data.data
                    this.page = response.data.meta.current_page
                    this.total = response.data.meta.last_page
                    this.loading = false
                }).catch((response) => {
                    this.loading = false
                });
                // .finaly(()=>{}) on IE not works
            },
            sendRequest() {
                let url = `marker?page=${this.page}&limit=12&category=${JSON.stringify(this.types)}`
                if (this.search) {
                    url += `&q=${this.search}`
                }
                if (this.order) {
                    url += `&order=${this.sortBy}`
                }
                this.getDataFromApi(url)

            },
            querySelections(v) {
                // // Simulated ajax query
                // this.typeItems = this.typeItems.filter(e => {
                //     return (e || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
                // })
            },
            getCategoryName(id) {
                if(typeof  this.typeItems.find(obj => obj.id ===  id) == 'undefined')
                    return 'category'

               return this.typeItems.find(obj => obj.id ===  id)
                     .translations.find(obj => obj.locale ===  this.getLocal).name
            },
            getTitle(item) {
                return item.find(obj => obj.locale ===  this.getLocal).title
            },
            getName(item) {
                return item.find(obj => obj.locale ===  this.getLocal).name
            },

            autoFilter(item, queryText, itemText) {
                return this.getName(item.translations).toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1
            },
            autoValue(value) {
                return value.id
            },
        },
        computed: {
            getLocal(){
                return this.$i18n.locale === 'uk' ? 'ua' : this.$i18n.locale
            }

        }
    }
</script>

<style scoped>
.no-data{
    width: 100vw !important;
}
</style>
