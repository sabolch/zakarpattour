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
                <span>Create new admin</span>
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
                :items="admins"
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
                <td>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn  v-on="on"
                                    :color="props.item.deleted_at ? 'red darken-2' : 'green darken-2'" outline flat>
                                <v-icon left color="red" medium v-if="props.item.deleted_at">delete</v-icon>
                                {{ props.item.name }}
                            </v-btn>
                        </template>
                        <span>{{ props.item.deleted_at ? `Deactivated at ${props.item.deleted_at}` : 'Active' }}</span>
                    </v-tooltip>

                </td>
                <td>{{ props.item.email }}</td>
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
                                v-if="!props.item.deleted_at"
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
                    Remove admin
                </v-card-title>
                <v-card-text> Are you sure remove this user?</v-card-text>
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
                        <span class="headline">Create admin</span>
                    </v-layout>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-form>
                                    <v-text-field
                                            v-model="form.name"
                                            v-validate="'required|max:30'"
                                            :error-messages="errors.collect('name')"
                                            label="Name"
                                            data-vv-name="username"
                                            clearable
                                            required
                                    ></v-text-field>
                                    <v-text-field
                                            v-model="form.email"
                                            v-validate="'required|email'"
                                            :error-messages="errors.collect('email')"
                                            label="E-mail"
                                            data-vv-name="email"
                                    ></v-text-field>
                                    <v-text-field
                                            mask="phone"
                                            v-model="form.telephone"
                                            v-validate="'required|min:10'"
                                            :error-messages="errors.collect('telephone')"
                                            label="Telephone"
                                            data-vv-name="telephone"
                                            clearable
                                            required
                                    ></v-text-field>
                                    <v-text-field
                                            :type="show1 ? 'text' : 'password'"
                                            :append-icon="show1 ? 'visibility_off' : 'visibility'"
                                            @click:append="show1 = !show1"
                                            ref="password"
                                            v-model="form.password"
                                            v-validate="'required|min:5|max:15'"
                                            :error-messages="errors.collect('password')"
                                            label="Password"
                                            data-vv-name="password"
                                            clearable
                                            required
                                    ></v-text-field>
                                    <v-text-field
                                            :type="show1 ? 'text' : 'password'"
                                            :append-icon="show1 ? 'visibility_off' : 'visibility'"
                                            @click:append="show1 = !show1"
                                            v-model="form.password_confirmation"
                                            v-validate="'required|confirmed:password'"
                                            :error-messages="errors.collect('password_confirmation')"
                                            label="Confirm Password"
                                            data-vv-name="password_confirmation"
                                            required
                                    ></v-text-field>
                                </v-form>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outline color="blue darken-1" flat @click="savedialog = false">Close</v-btn>
                    <v-btn v-if="form.deleted_at" outline color="amber darken-4" flat @click="activate">Activate</v-btn>
                    <v-btn :loading="form.busy" outline color="green darken-1" flat @click="saveCategory">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--end edit dialog-->
    </v-container>
</template>
<script>
    import Form from 'vform'
    import { mapGetters } from 'vuex'

    export default {
        name: "admins",
        layout: "admin",

        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },

        async asyncData({store,$axios, redirect}) {
            try {
                $axios.setToken(store.state.admin.token,'Bearer')
                let admins = await $axios.get('admin/admins')
                return {
                    pagination: admins.data.meta,
                    admins: admins.data.data
                }
            } catch (e) {
                redirect('/error')
            }
        },

        data() {
            return {
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 6 || 'Min 6 characters',
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'Invalid e-mail.'
                    }
                },

                first: true,
                loading: false,
                admins: {},
                page: 1,
                tablePagination: {},
                pagination: {},

                show1:false,
                search: '',
                editing: false,
                dialog: false,
                savedialog: false,

                formObj: {
                    id: '',
                    type: 'admin',
                    name:'',
                    email:'',
                    telephone:'',
                    password:'',
                    password_confirmation:''
                },
                form: new Form({
                    id: '',
                    type: 'admin',
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
                    this.responseCategory()
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
                this.form.type = 'admin'
                this.form.telephone = ''
                this.form.password = ''
                this.form.password_confirmation = ''
            },
            async responseCategory() {
                let url = `admin/admins?page=${this.page}&per_page=${this.tablePagination.rowsPerPage}`
                if (this.search) url += `&q=${this.search}`
                this.loading = true
                this.$axios.setToken(this.$store.state.admin.token,'Bearer')
                const {data} = await this.$axios.get(url)
                this.pagination = data.meta
                this.admins = data.data
                this.loading = false
            },

            async store(url) {
                await this.form.put(url).then(res =>{
                    this.authrize();
                    this.responseCategory()
                    this.savedialog = false
                })
            },

            async trash() {
                const {data} = await this.form.delete('admin/delete')
                this.authrize();
                await this.responseCategory()
                this.dialog = false
            },

            async activate() {
                const {data} = await this.form.post('admin/activate')
                this.authrize();
                await this.responseCategory()
                this.savedialog = false
            },

            doPaginate() {
                if (this.page == 1) this.responseCategory()
                this.page = 1
            },
            saveCategory() {
                let url = 'admin/create';
                if (this.editing) url = 'admin/edit';
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
                this.authrize()
                this.savedialog = true
                this.editing = false

            },
            edit(item) {
                this.form = new Form(item)
                this.form.type = 'admin'
                this.form.password = ''
                this.form.password_confirmation = ''
                this.editing = true
                this.savedialog = true
            },
            trashing(item) {
                this.form = new Form(item)
                this.form.type = 'admin'
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
