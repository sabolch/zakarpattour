<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-slide-y-transition>
                    <v-card class="elevation-12" v-show="expand">
                        <v-toolbar color="primary">
                            <v-toolbar-title class="white--text">Register</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text class="mb-0 pb-0">
                            <v-form @submit.prevent="login" @keydown="form.onKeydown($event)">
                                <v-text-field v-model="form.name" prepend-icon="person" name="name" label="Name"
                                              type="text" clearable
                                              :counter="10"
                                              v-validate="'required|min:4|max:10'"
                                              :error-messages="errors.collect('name')"
                                              data-vv-name="name"
                                ></v-text-field>
                                <v-text-field v-model="form.email" prepend-icon="email" name="login" label="Email"
                                              type="text" clearable
                                              :counter="40"
                                              v-validate="'required|email|max:40'"
                                              :error-messages="errors.collect('email')"
                                              data-vv-name="email"
                                ></v-text-field>
                                <v-text-field v-model="form.password" id="password" prepend-icon="lock" name="password"
                                              label="Password"
                                              :type="showPassword ? 'text' : 'password'"
                                              :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                                              @click:append="showPassword = !showPassword"
                                              v-validate="'required|min:7|max:15'"
                                              :error-messages="errors.collect('password')"
                                              data-vv-name="password"

                                ></v-text-field>
                                <v-text-field v-model="form.password_confirmation" id="password_confirmation"
                                              prepend-icon="repeat" name="password_confirmation"
                                              label="Password Confirmation"
                                              :type="showConfPassword ? 'text' : 'password'"
                                              :append-icon="showConfPassword ? 'visibility_off' : 'visibility'"
                                              @click:append="showConfPassword = !showConfPassword"
                                              v-validate="'confirmed:password'"
                                              :error-messages="errors.collect('password_confirmation')"
                                              data-vv-name="password_confirmation"
                                ></v-text-field>
                                <vue-recaptcha
                                        ref="recaptcha"
                                        @verify="onCaptchaVerified"
                                        @expired="onCaptchaExpired"
                                        class="g-recaptcha"
                                        sitekey="6LfeIXYUAAAAACI0h2MIPpDZiJ9a-uAZwrVMsxJ2"
                                ></vue-recaptcha>
                            </v-form>
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
                                                    @click="register()"
                                                    :loading="form.busy"
                                            >Register
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
    import hu from 'vee-validate/dist/locale/hu';
    import uk from 'vee-validate/dist/locale/uk';
    import Form from 'vform'
    import VueRecaptcha from "vue-recaptcha"

    export default {
        head() {
            return {title: this.$t('register')}
        },
        components: {VueRecaptcha},
        data: () => ({
            showPassword:false,
            showConfPassword:false,
            expand: false,
            form: new Form({
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            })
        }),

        methods: {
            async register() {
                // Register the user.
                const {data} = await this.form.post('/register')

                // Log in the user.
                const {data: {token}} = await this.form.post('/login')

                // Save the token.
                this.$store.dispatch('auth/saveToken', {token})

                // Update the user.
                await this.$store.dispatch('auth/updateUser', {user: data})

                // Redirect home.
                this.$router.push({name: 'home'})
            },
            setLanguage(locale) {
                switch (locale) {
                    case 'en':
                        this.$validator.localize(locale)
                        break
                    case 'uk':
                        this.$validator.localize(locale, uk)
                        break
                    case 'hu':
                        this.$validator.localize(locale, hu)
                        break
                }
            },
            onCaptchaVerified: function (recaptchaToken) {
                this.form.recaptchaToken = recaptchaToken
                console.log(recaptchaToken);

            },
            onCaptchaExpired: function () {
                this.$refs.recaptcha.reset()
            }
        },
        watch: {
            '$nuxt.$i18n.locale': {
                handler(value) {
                    this.setLanguage($nuxt.$i18n.locale)
                },
                deep: true
            }
        },
        mounted() {
            this.setLanguage($nuxt.$i18n.locale)
            this.expand = true
        },

    }
</script>
<style>
    .g-recaptcha div {
        margin: auto;
    }
</style>
