<template>
    <v-card>
        <v-flex xs12>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        clearable
                        :label="`${$t('form.search')}..`"
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
                    :total-items="pagination.total-1"
                    :rows-per-page-items="rowsPerPageItems"
                    :loading="loading"
                    class="elevation-1"
            >
                <template
                        slot="items"
                        slot-scope="props"
                        v-if="props.item.id !== 0"
                >
                    <td>{{ getTitle(props.item) }}</td>
                    <td>{{ props.item.deleted_at }}</td>
                    <td>
                        <v-flex xs12 class="text-xs-center">
                            <v-btn
                                    outline
                                    small
                                    fab
                                    color="green lighteen-2"
                                    @click="trashing(props.item)"
                            >
                                <v-icon>restore</v-icon>
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
            <div class="text-xs-center">
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
                        {{$t('settlement.restore')}}
                    </v-card-title>
                    <v-card-text>{{$t('settlement.restore_msg')}}</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                                color="green darken-1"
                                flat
                                @click="dialog = false"
                        >
                            {{$t('btns.close')}}
                        </v-btn>
                        <v-btn
                                :loading="form.busy"
                                color="red darken-1"
                                flat
                                @click="trash"
                        >
                            {{$t('btns.ok')}}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-card>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "trashed",
        layout: "admin",

        head() {
            return {
                title: 'Admin - Settlements Trashed',
            }
        },

        async asyncData({$axios, redirect}) {
            try {
                let settlement = await $axios.get('settlement/trashed')

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
                dialog: false,

                formObj: {},
                form: new Form({
                    id: '',
                }),

                headers: [
                    {text: 'Title', align: 'left', sortable: false, value: 'name'},
                    {text: 'Deleted', align: 'left', sortable: false, value: 'deleted_at'},
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
                let url = `settlement/trashed?page=${this.page}&limit=${this.tablePagination.rowsPerPage}`
                if (this.search) url += `&q=${this.search}`

                this.loading = true
                const {data} = await this.$axios.get(url)
                this.pagination = data.meta
                this.settlements = data.data
                this.loading = false

            },


            async trash() {
                console.log(this.form)
                const {data} = await this.form.post('settlement/restore')
                this.form = new Form(this.formObj)
                await this.responseSettlement()
                this.dialog = false
            },
            doPaginate() {
                if (this.page == 1) this.responseSettlement()
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
            },
            getTitle(item) {
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
