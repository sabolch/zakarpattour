<template>
    <v-container>
        <v-card-title>
            <v-tooltip bottom>
                <v-btn
                        fab
                        outline
                        slot="activator"
                        color="primary"
                        @click="createNew"
                >
                    <v-icon medium dark>add</v-icon>

                </v-btn>
                <span>Create new settlement</span>
            </v-tooltip>
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
                :items="settlements"
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
                <td>{{ getTitle(props.item) }}</td>
                <td>
                    <v-flex xs12 class="text-xs-center">
                        <v-btn
                                outline
                                small
                                fab
                                color="indigo"
                                @click="edit(props.item)"
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
                    Sorry, nothing to display here :(
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
                    Remove settlement
                </v-card-title>
                <v-card-text> Are you sure remove this settlement?</v-card-text>
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
        <!--remove d end-->

        <!--edit dialog-->
        <v-dialog v-model="savedialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <v-layout justify-center>
                        <span class="headline">Adding settlement</span>
                    </v-layout>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-form>
                                    <v-text-field

                                            label="Name Eng"
                                            v-model="form.translations[0].title"
                                            required
                                            :counter="30"
                                            :maxlength="30"
                                            clearable
                                    ></v-text-field>
                                    <v-text-field label="Name Hun"
                                                  v-model="form.translations[1].title"
                                                  :counter="30"
                                                  :maxlength="30"
                                                  clearable

                                    ></v-text-field>
                                    <v-text-field label="Name Ukr"
                                                  v-model="form.translations[2].title"
                                                  :counter="30"
                                                  :maxlength="30"
                                                  clearable

                                    ></v-text-field>
                                </v-form>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outline color="blue darken-1" flat @click="savedialog = false">Close</v-btn>
                    <v-btn :loading="form.busy" outline color="green darken-1" flat @click="saveCategory">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--end edit dialog-->
    </v-container>
</template>
<script>
    import Form from 'vform'

    export default {
        name: "SettlementsIndex",
        layout: "admin",

        head() {
            return {
                title: 'Admin - Settlements',
            }
        },

        async asyncData({$axios, redirect}) {
            try {
                let settlement = await $axios.get('settlement')

                return {
                    pagination: settlement.data.meta,
                    settlements: settlement.data.data
                }
            } catch (e) {
                redirect('/error')
            }
        },

        data() {
            return {
                first: true,
                loading: false,
                icons: {},
                settlements: {},
                page: 1,
                tablePagination: {},
                pagination: {},

                search: '',
                editing: false,
                dialog: false,
                savedialog: false,

                formObj: {
                    id: '',
                    lat: 48.22,
                    lng: 22.48,
                    translations: [
                        {locale: 'en', title: '', description: 'description'},
                        {locale: 'hu', title: '', description: 'description'},
                        {locale: 'ua', title: '', description: 'description'},

                    ],
                },
                form: new Form({
                    id: '',
                    lat: 48.22,
                    lng: 22.48,
                    translations: [
                        {locale: 'en', title: '', description: 'description'},
                        {locale: 'hu', title: '', description: 'description'},
                        {locale: 'ua', title: '', description: 'description'},

                    ],
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
                    this.responseSettlement()
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
        mounted() {
        },
        methods: {
            async responseSettlement() {
                let url = `settlement?page=${this.page}&per_page=${this.tablePagination.rowsPerPage}`
                if (this.search) url += `&q=${this.search}`

                this.loading = true
                const {data} = await this.$axios.get(url)
                this.pagination = data.meta
                this.settlements = data.data
                this.loading = false

            },

            async store(url) {
                const {data} = await this.form.put(url)
                this.form = new Form(this.formObj)
                await this.responseSettlement()
                this.savedialog = false
            },

            async trash() {
                const {data} = await this.form.delete('settlement/trash')
                this.form = new Form(this.formObj)
                await this.responseSettlement()
                this.dialog = false
            },
            doPaginate() {
                if (this.page == 1) this.responseSettlement()
                this.page = 1
            },
            saveCategory() {
                let url = 'settlement/store';
                if (this.editing) url = 'settlement/edit';
                this.store(url)
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

            createNew() {
                this.form = new Form(this.formObj)
                this.savedialog = true
                this.editing = false

            },
            edit(item) {
                this.form = new Form(item)
                this.editing = true
                this.savedialog = true
            },
            trashing(item) {
                this.form = new Form(item)
                this.dialog = true
            },
            getTitle(item) {
                console.log(item)
                if (!item.translations[0]) {
                    return '';
                }
                return item.translations.find(obj => obj.locale === this.getLocal).title
            },
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            }
        }
    }
</script>
<style scoped>
    .v-icon {
        font-size: 16px;
    }
</style>
