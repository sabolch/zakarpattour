<template>
    <v-container>
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
                :items="users"
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
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>
                    <v-flex xs12 class="text-xs-center">
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
                    {{$t('btns.remove')}}
                </v-card-title>
                <v-card-text>{{ $t('conf_remove') }}</v-card-text>
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
                        {{$t('btns.remove')}}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--remove d end-->

    </v-container>
</template>
<script>
    import Form from 'vform'
    import { mapGetters } from 'vuex'

    export default {
        name: "users",
        layout: "admin",

        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },

        async asyncData({store,$axios, redirect}) {
            try {
                $axios.setToken(store.state.admin.token,'Bearer')
                let users = await $axios.get('admin/users')
                return {
                    pagination: users.data.meta,
                    users: users.data.data
                }
            } catch (e) {
                redirect('/error')
            }
        },

        data() {
            return {
                first: true,
                loading: false,
                users: {},
                page: 1,
                tablePagination: {},
                pagination: {},

                search: '',
                dialog: false,
                savedialog: false,

                formObj: {
                    id: '',
                    type: 'user',
                    name:'',
                    email:'',
                    telephone:'',
                    password:'',
                    password_confirmation:''
                },
                form: new Form({
                    id: '',
                    type: 'user',
                    name:'',
                    email:'',
                    telephone:'',
                    password:'',
                    password_confirmation:''
                }),

                headers: [
                    {text: 'Name', align: 'left', sortable: false, value: 'name'},
                    {text: 'Email', align: 'left', sortable: false, value: 'email'},
                    {text: 'Action', value: 'action', sortable: false, align: 'center'},
                ],
                rowsPerPageItems: [5, 10, 20],
            }
        },
        watch: {
            page: {
                handler() {
                    this.responseUser()
                }
            },
            'tablePagination.rowsPerPage': {
                handler(val) {
                    if (this.first) {this.first = !this.first} else {
                        this.doPaginate()
                    }
                },
            }
        },
        mounted() {
           this.authrize()
        },
        computed: mapGetters({
            admin: 'admin/user'
        }),
        methods: {
            authrize(){
                this.form.keys().forEach(key => {
                    this.form[key] = this.admin[key]
                })
            },
            async responseUser() {
                let url = `admin/users?page=${this.page}&per_page=${this.tablePagination.rowsPerPage}`
                if (this.search) url += `&q=${this.search}`
                this.loading = true

                this.$axios.setToken(this.$store.state.admin.token,'Bearer')
                const {data} = await this.$axios.get(url)
                this.pagination = data.meta
                this.users = data.data
                this.loading = false
            },


            async trash() {
                const {data} = await this.form.delete('admin/delete')
                this.authrize();
                await this.responseUser()
                this.dialog = false
            },
            doPaginate() {
                if (this.page == 1) this.responseUser()
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

            trashing(item) {
                this.form = new Form(item)
                this.form.type = 'user';
                this.dialog = true
            }
        },
    }
</script>
<style scoped>
    .v-icon {
        font-size: 16px;
    }
</style>
