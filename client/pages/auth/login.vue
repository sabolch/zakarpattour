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
                                              type="text"></v-text-field>
                                <v-text-field v-model="form.password" id="password" prepend-icon="lock" name="password"
                                              label="Password"
                                              type="password"></v-text-field>

                                <vue-recaptcha
                                        ref="recaptcha"
                                        sitekey="6LfeIXYUAAAAACI0h2MIPpDZiJ9a-uAZwrVMsxJ2"
                                        @verify="onCaptchaVerified"
                                        @expired="onCaptchaExpired"
                                        class="g-recaptcha"
                                ></vue-recaptcha>
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
                                            <v-btn icon class="blue--text text--darken-4">
                                                <v-icon medium>sb-facebook</v-icon>
                                            </v-btn>
                                            <v-btn icon class="purple--text text--darken-4">
                                                <v-icon medium>sb-instagram</v-icon>
                                            </v-btn>
                                            <v-btn icon class="blue--text">
                                                <v-icon medium>sb-twitter</v-icon>
                                            </v-btn>
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
    import VueRecaptcha from "vue-recaptcha"

    export default {
        head() {
            return {title: this.$t('login')}
        },
        components: {VueRecaptcha},
        data: () => ({
            expand: false,
            form: new Form({
                email: '',
                password: '',
                recaptchaToken: ''
            }),
            remember: false
        }),
        mounted() {
            this.expand = true

        },
        methods: {
            async login() {
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
                this.$router.push({name: 'settings.profile'})
            },

            onCaptchaVerified: function (recaptchaToken) {
                this.form.recaptchaToken = recaptchaToken
                console.log(recaptchaToken);

            },
            onCaptchaExpired: function () {
                this.$refs.recaptcha.reset()
            }
        }
    }
</script>
<style>
    .g-recaptcha div {
        margin: auto;
    }
</style>
