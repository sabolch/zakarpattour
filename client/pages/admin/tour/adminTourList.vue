<template>
    <v-container>
        <v-card-title>

            <v-spacer></v-spacer>
            <v-text-field
                    v-model="search"
                    clearable
                    label="Search ..."
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
                    Loading ...
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
        </v-card-title>
        <v-data-table
                :headers="headers"
                :items="tours"
                :pagination.sync="tablePagination"
                :total-items="pagination.total"
                :rows-per-page-items="rowsPerPageItems"
                :loading="loading"
                class="elevation-1"
        >
            <template
                    slot="items"
                    slot-scope="props"
            >
                <td>{{ props.item.translations.find(obj => obj.locale ===  getLocal).title }}</td>
                <td>
                    <v-flex xs12 class="text-xs-center">
                        <v-btn
                                outline
                                small
                                fab
                                color="primary"
                                exact
                                flat
                                :to="{name:'tour.show',params: {slug:props.item.slug}}"
                                target="_blank"
                        >
                            <v-icon>remove_red_eye</v-icon>
                        </v-btn>
                        <v-btn
                                outline
                                small
                                fab
                                color="green"
                                :to="{name:'admin.tour',params: {slug:props.item.slug}}"
                        >
                            <v-icon>edit</v-icon>
                        </v-btn>

                        <v-btn
                                outline
                                small
                                fab
                                color="red"
                                @click="trashing(props.item)"
                        >
                            <v-icon>delete</v-icon>
                        </v-btn>

                    </v-flex>
                </td>
            </template>

            <template slot="no-data">
                <v-alert :value="true" color="error" icon="warning">
                   {{$t('messages.no_data')}}
                </v-alert>
            </template>
        </v-data-table>
        <div class="text-xs-center pt-2">
            <v-pagination
                    v-model="page"
                    :length="pagination.last_page"
                    :total-visible="7"
                    circle
            ></v-pagination>
        </div>

        <!--remove dialog-->
        <v-dialog
                v-model="dialog"
                persistent
                max-width="290"
        >
            <v-card>
                <v-card-title class="headline">
                    Remove
                </v-card-title>
                <v-card-text> Are you sure remove this item?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="green darken-1"
                            flat
                            @click="dialog = false"
                    >
                        Close
                    </v-btn>
                    <v-btn
                            :loading="form.busy"
                            color="red darken-1"
                            flat
                            @click="trash"
                    >
                        Remove
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


    </v-container>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "adminTourList",
        layout: "admin",
        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },

        async asyncData({$axios, $router}) {
            try {
                let tour = await $axios.get('tour')

                return {
                    pagination: tour.data.meta,
                    tours: tour.data.data
                }
            } catch (e) {
                $router.push({name: 'error'})
            }
        },

        data() {
            return {
                first: true,
                loading: false,
                page: 1,
                tablePagination: {},
                pagination: {},

                dialog:false,
                search: '',
                tours:{},

                form: new Form({
                    id: -1,
                }),

                headers: [
                    {text: 'Title', align: 'left', sortable: false, value: 'name'},
                    {text: 'Action', value: 'action', sortable: false, align: 'center'},
                ],
                rowsPerPageItems: [5, 10, 20],
            }
        },
        watch: {
            page: {
                handler() {
                    this.responseMessage()
                }
            },
            'tablePagination.rowsPerPage': {
                handler(val) {
                    if (this.first) {
                        this.first = !this.first
                    } else {
                        this.doPaginate()
                    }
                },
            }
        },
        mounted() {},
        computed: {
            getLocal(){
                return this.$i18n.locale
            }
        },
        methods: {
            async responseMessage() {
                let url = `tour?page=${this.page}&per_page=${this.tablePagination.rowsPerPage}`
                if (this.search) url += `&q=${this.search}`

                this.loading = true
                const {data} = await this.$axios.get(url)
                this.pagination = data.meta
                this.tours = data.data
                this.loading = false
            },
            async trash() {
                const {data} = await this.form.delete('tour/trash')
                this.form = new Form(this.formObj)
                await this.responseMessage()
                this.dialog = false
            },

            doPaginate() {
                if (this.page == 1) this.responseMessage()
                this.page = 1
            },

            keyupHandle(event) {
                clearTimeout(this.timeoutId);
                if (event.key == 'Enter') {
                    this.doPaginate();
                    return;
                }
                this.timeoutId = setTimeout(this.doPaginate, 700);
            },

            autoFilter(item, queryText, itemText) {
                return item.name.toLocaleLowerCase().indexOf(queryText.toLocaleLowerCase()) > -1
            },

            autoValue(value) {
                return value.value
            },

            trashing(item) {
                this.form = new Form(item)
                this.dialog = true
            }
        },
    }
</script>

<style scoped>

</style>
