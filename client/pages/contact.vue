<template>
    <v-container pa-0 ma-0 fluid fill-height class="contact-bg">
        <v-layout row wrap justify-center align-center>
            <v-flex xs8>
                <v-slide-y-transition>
                    <v-card class="contact-card" v-show="expand">
                        <v-container fill-height align-center>
                            <v-layout row wrap pa-0 ma-0>
                                <v-flex xs12 md6 pa-0 ma-0>
                                    <v-container fill-height ma-0 pa-0>
                                        <v-layout align-center ma-0 pa-0 class="text-xs-center">
                                            <v-flex xs12 justify-center>
                                                <v-icon color="primary" size="300">contact_mail</v-icon>
                                                <p class="display-2 font-weight-bold text--accent-4">Contact US</p>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-flex>
                                <v-flex xs12 md6 pa-0 ma-0>
                                    <v-container fill-height ma-0 pa-0>
                                        <v-layout row wrap align-center ma-0 pa-0 justify-center>
                                            <v-flex pa-3 ma-0 xs10>
                                                <v-form ma-0 pa-0 @submit.prevent="sendMail"
                                                        @keydown="form.onKeydown($event)">
                                                    <v-text-field
                                                            :counter="10"
                                                            box
                                                            label="Name"
                                                            required
                                                            v-model="form.name"
                                                            v-validate="'required|min:4|max:10'"
                                                            :error-messages="errors.collect('name')"
                                                            data-vv-name="name"
                                                            clearable
                                                    ></v-text-field>
                                                    <v-text-field
                                                            :counter="40"
                                                            box
                                                            label="Email"
                                                            required
                                                            v-model="form.email"
                                                            clearable
                                                            v-validate="'required|email|max:40'"
                                                            :error-messages="errors.collect('email')"
                                                            data-vv-name="email"
                                                    ></v-text-field>
                                                    <v-text-field
                                                            :counter="30"
                                                            box
                                                            label="Subject"
                                                            required
                                                            v-model="form.subject"
                                                            clearable
                                                            v-validate="'required|min:4|max:30'"
                                                            :error-messages="errors.collect('subject')"
                                                            data-vv-name="subject"
                                                    ></v-text-field>
                                                    <v-textarea
                                                            :counter="300"
                                                            box
                                                            label="Message"
                                                            v-model="form.message"
                                                            clearable
                                                            v-validate="'required|min:10|max:300'"
                                                            :error-messages="errors.collect('message')"
                                                            data-vv-name="message"
                                                    >
                                                    </v-textarea>
                                                    <vue-recaptcha
                                                            ref="recaptcha"
                                                            @verify="onCaptchaVerified"
                                                            @expired="onCaptchaExpired"
                                                            class="g-recaptcha"
                                                            sitekey="6LfeIXYUAAAAACI0h2MIPpDZiJ9a-uAZwrVMsxJ2"
                                                    ></vue-recaptcha>

                                                    <v-btn class="ma-2" large color="primary"
                                                           right
                                                           @click="sendMail"
                                                           :loading="form.busy"
                                                    >Send Email
                                                    </v-btn>
                                                </v-form>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-flex>
                            </v-layout>
                        </v-container>
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
        name: "contact",
        head() {
            return {
                title: 'Contact',
            }
        },
        components: {VueRecaptcha},
        data: () => ({
            expand: false,
            form: new Form({
                name: '',
                email: '',
                subject: '',
                message: ''
            }),
            remember: false,
        }),
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
        methods: {
            sendMail() {
                // Send mail
                console.log('Hello');
            },

            onCaptchaVerified: function (recaptchaToken) {
                this.form.recaptchaToken = recaptchaToken
                console.log(recaptchaToken);

            },
            onCaptchaExpired: function () {
                this.$refs.recaptcha.reset()
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
            }
        },
    }
</script>

<style scoped>
    .contact-bg {
        background-image: linear-gradient(rgb(0, 0, 255), rgb(0, 0, 100));
    }
</style>
