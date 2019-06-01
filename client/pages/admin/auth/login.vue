<template>
    <v-app id="login" class="primary">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md6 lg4>
                        <v-card class="elevation-1 pa-3">
                            <v-card-text class="text-xs-center">
                                <div class="layout column align-center">
                                    <img src="/images/logo.png" alt="Vue Material Admin" height="180">
                                    <h1 v-if="!error.active" class="flex my-4 primary--text">Admin Login</h1>
                                </div>
                                <v-form>
                                    <v-alert
                                            class="mt-3 mb-3"
                                            :value="error.active"
                                            color="error"
                                            icon="warning"
                                            outline
                                    >
                                        {{error.text}}
                                    </v-alert>
                                    <v-text-field prepend-icon="person"
                                                  label="Login"
                                                  type="text"
                                                  v-model="form.email">
                                    </v-text-field>
                                    <v-text-field
                                            prepend-icon="lock"
                                            :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                                            :type="showPassword ? 'text' : 'password'"
                                            @click:append="showPassword = !showPassword"
                                            label="Password"
                                            v-model="form.password">
                                    </v-text-field>
                                    <recaptcha
                                            @error="onError"
                                    />
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                        color="primary"
                                        @click="onSubmit"
                                        :loading="form.busy"
                                >Login
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    import Form from 'vform'
    import VueRecaptcha from "vue-recaptcha"

    export default {
        layout: 'simple',
        middleware: ['admin-route', 'adminLoggedIn'],
        name: "login",
        components: {VueRecaptcha},
        data: () => ({
            showPassword: false,
            remember: false,

            form: new Form({
                email: 'admin@app.com',
                password: 'windows7',
                recaptchaToken: ''
            }),

            error: {
                active: false,
                text: ''
            }
        }),
        mounted() {
            if (this.$store.state.auth.loggedIn) {
                this.$router.push({name: 'settings.profile'})
            }
        },
        methods: {
            onError(error) {
                console.log('ReCaptcha erorr:', error)
                this.error.text = `ReCaptcha erorr: ${error}`
                this.error.active = true
                setTimeout(() => {
                    this.error.active = false
                }, 5000)
            },

            async onSubmit() {
                try {
                    // this.form.recaptchaToken = await this.$recaptcha.getResponse()
                    this.form.recaptchaToken = await this.$recaptcha.getResponse()
                    // Submit the form.
                    const {data} = await this.form.post('/admin/login')

                    // Save the token.
                    this.$store.dispatch('admin/saveToken', {
                        token: data.token,
                        remember: this.remember
                    })

                    // Fetch the user.
                    await this.$store.dispatch('admin/fetchUser')

                    // Redirect home.
                    this.$router.push({name: 'admin.dash'})

                } catch (error) {
                    console.log('Login error:', error)
                }
            }
        }
    }
</script>
<style lang="css">
    #login {
        height: 50%;
        width: 100%;
        position: absolute;
        top: 0;
        left: 0;
        content: "";
        z-index: 0;
    }

    .g-recaptcha div {
        margin: auto !important;
    }
</style>

