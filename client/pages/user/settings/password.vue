<template>
    <v-flex xs12>
        <v-card height="550">
            <v-form>
                <v-container fill-height fluid>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <h1 class="headline font-weight-bold">Change your password</h1>
                        </v-flex>
                        <v-flex xs12 class="pa-4">
                            <v-text-field
                                    :type="show1 ? 'text' : 'password'"
                                    :append-icon="show1 ? 'visibility_off' : 'visibility'"
                                    @click:append="show1 = !show1"
                                    v-model="form.oldPassword"
                                    v-validate="'required'"
                                    :error-messages="errors.collect('oldPassword')"
                                    label="Old Password"
                                    data-vv-name="oldPassword"
                                    clearable
                                    required

                            ></v-text-field>
                            <v-text-field
                                    :type="show2 ? 'text' : 'password'"
                                    :append-icon="show2 ? 'visibility_off' : 'visibility'"
                                    @click:append="show2 = !show2"
                                    ref="password"
                                    v-model="form.password"
                                    v-validate="'required|min:5|max:15'"
                                    :error-messages="errors.collect('password')"
                                    label="New Password"
                                    data-vv-name="password"
                                    clearable
                                    required

                            ></v-text-field>
                            <v-text-field
                                    :type="show3 ? 'text' : 'password'"
                                    :append-icon="show3 ? 'visibility_off' : 'visibility'"
                                    @click:append="show3 = !show3"

                                    v-model="form.password_confirmation"
                                    v-validate="'required|confirmed:password'"
                                    :error-messages="errors.collect('password_confirmation')"
                                    label="Confirm Password"
                                    data-vv-name="password_confirmation"
                                    clearable
                                    required

                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 class="text-xs-right">
                            <v-btn color="primary"
                                   :loading="form.busy"
                                   @click="update"
                            >
                                Save
                            </v-btn>
                        </v-flex>
                        <v-flex xs12>
                            <h1 class="headline font-weight-bold">Remove Your Account Forever</h1>
                        </v-flex>
                        <v-flex xs12 class="text-xs-right">
                            <v-btn color="error"
                                   @click="destroy"
                                   :loading="formDestroy.busy"
                            >
                                Remove
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>


        <v-snackbar
                v-model="infoSnackbar.active"
                :timeout="infoSnackbar.timeout"
                :color="infoSnackbar.success ? 'success' : 'error'"
                top
        >
            {{ infoSnackbar.success ? 'Successfully saved' : 'Somethings went wrong :( Try again later'}}
            <v-btn
                    flat
                    dark
                    @click="infoSnackbar.active = false"
            >
                <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>

    </v-flex>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "password",

        head() {
            return {
                title: 'Password Settings',
            }
        },
        data: () => ({
            expand: false,

            show1: false,
            show2: false,
            show3: false,

            infoSnackbar:{
                active:false,
                timeout:3000,
                success:true
            },

            form: new Form({
                oldPassword: '',
                password: '',
                password_confirmation: ''
            }),
            formDestroy: new Form(),
            remember: false,
        }),
        methods: {
            async update() {
                try {
                    await this.form.patch('/settings/password')
                    this.form.reset()
                    this.infoSnackbar.success = true
                    this.infoSnackbar.active = true
                } catch (e) {
                    // console.log(e.response)
                    this.infoSnackbar.success = false
                    this.infoSnackbar.active = true
                }
            },
            async destroy() {
                try {
                    await this.formDestroy.delete('/settings/destroy')
                    await this.$store.dispatch('auth/logout')
                    // Redirect to login.
                    this.$router.push({name: 'login'})
                } catch (e) {
                    // console.log(e.response)
                    this.infoSnackbar.success = false
                    this.infoSnackbar.active = true
                }
            }
        }
    }
</script>

<style scoped>

</style>
