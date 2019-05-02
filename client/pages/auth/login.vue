<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-slide-y-transition>
                    <v-card class="elevation-12" v-show="expand">
                        <v-toolbar color="primary">
                            <v-toolbar-title class="white--text">Login</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text class="mb-0 pb-0 text-xs-center">
                            <v-form @submit.prevent="login()" @keydown="form.onKeydown($event)">
                                <v-text-field v-model="form.email" prepend-icon="person" name="login" label="Login"
                                              type="text"
                                              :error-messages="ErrList"
                                ></v-text-field>
                                <v-text-field v-model="form.password" id="password" prepend-icon="lock" name="password"
                                              label="Password"
                                              :type="showPassword ? 'text' : 'password'"
                                              :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                                              @click:append="showPassword = !showPassword"
                                ></v-text-field>

                                <recaptcha
                                        @error="onError"
                                />

                            </v-form>
                            <v-flex xs12>
                                <v-layout row>
                                    <v-flex mt-2 class="text-xs-center">
                                        <router-link class="black--text" :to="{name:'password.request'}">Forgot your
                                            password?
                                        </router-link>&nbsp;
                                        <router-link class="blue--text" :to="{name:'register'}">Create an account
                                        </router-link>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-card-text>
                        <v-card-actions class="mt-2 pt-0">
                            <v-layout row wrap>

                                <v-flex xs12>
                                    <v-layout row wrap>
                                        <v-flex ml-2>
                                             <login-with-facebook/>
                                             <login-with-twitter/>
                                             <login-with-instagram/>
                                             <login-with-google/>
                                            <login-with-github/>

                                        </v-flex>
                                        <v-flex class="text-xs-right">
                                            <v-btn
                                                    color="primary"
                                                    @click="login"
                                                    :loading="form.busy"
                                            >Login
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>

                            </v-layout>
                        </v-card-actions>
                    </v-card>
                </v-slide-y-transition>
            </v-flex>
        </v-layout>
    </v-container>

</template>
<script>
    import Form from 'vform'

    export default {
        middleware: 'loggedIn',
        head() {
            return {title: this.$t('login')}
        },
        data: () => ({
            expand: false,
            showPassword: false,
            form: new Form({
                email: '',
                password: '',
                recaptchaToken: ''
            }),
            remember: false,
            ErrList: null
        }),
        mounted() {
            this.expand = true
            if (this.$store.state.auth.loggedIn) {
                this.$router.push({name: 'settings.profile'})
            }
        },
        methods: {
            onError(error) {
                console.log('ReCaptcha erorr:', error)
            },
            async login() {
                try {
                    this.ErrList = []
                    this.form.recaptchaToken = await this.$recaptcha.getResponse()
                    // Submit the form.
                    const {data} = await this.form.post('/login')

                    // Save the token.
                    this.$store.dispatch('auth/saveToken', {
                        token: data.token,
                        remember: this.remember
                    })

                    // Fetch the user.
                    await this.$store.dispatch('auth/fetchUser')

                    // Redirect home.
                    // this.$router.go(-1)
                    this.$router.push({name: 'settings.profile'})
                } catch (e) {
                    this.ErrList.push(e.response.data.message)
                }
                // this.$refs.recaptcha.reset()

            }
        }
    }
</script>
<style>
    .g-recaptcha div {
        margin: auto;
    }
</style>
