<template>
    <v-container pa-1 fluid fill-height class="contact-bg">
        <v-layout row wrap justify-center align-center>
            <v-flex xs12 md8>
                <v-slide-y-transition>
                    <v-card class="contact-card" v-show="expand">
                        <v-container fill-height align-center>
                            <v-layout row wrap pa-0 ma-0>
                                <v-flex xs12 md6 pa-0 ma-0>
                                    <v-container fill-height ma-0 pa-0>
                                        <v-layout align-center ma-0 pa-0 class="text-xs-center">
                                            <v-flex xs12 justify-center>
                                                <v-icon color="primary" :size="breakpoint ? 150 : 300">contact_mail</v-icon>
                                                <p class="font-weight-bold text--accent-4" :class="[{'headline': breakpoint},{'display-2': !breakpoint}]">{{$t('contact_us.title')}}</p>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-flex>
                                <v-flex xs12 md6>
                                    <v-container fill-height ma-0 pa-0>
                                        <v-layout row wrap align-center  justify-center>
                                            <v-flex  xs12 md10>
                                                <v-form @submit.prevent="sendMail"
                                                        @keydown="form.onKeydown($event)">
                                                    <v-text-field
                                                            :counter="10"
                                                            box
                                                            :label="$t('form.name')"
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
                                                            :label="$t('form.email')"
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
                                                            :label="$t('form.subject')"
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
                                                            :label="$t('form.message')"
                                                            v-model="form.message"
                                                            clearable
                                                            v-validate="'required|min:10|max:300'"
                                                            :error-messages="errors.collect('message')"
                                                            data-vv-name="message"
                                                    >
                                                    </v-textarea>
                                                    <recaptcha/>
                                                    <v-btn class="ma-2" large color="primary"
                                                           right
                                                           @click="sendMail"
                                                           :loading="form.busy"
                                                    >
                                                        {{$t('btns.send')}}
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

                <v-snackbar
                        v-model="infoSnackbar.active"
                        :timeout="infoSnackbar.timeout"
                        :color="infoSnackbar.success ? 'success' : 'error'"
                        bottom
                >
                    {{ infoSnackbar.success ? 'Message sent successfully' : 'Somethings went wrong :( Try again later'}}
                    <v-btn
                            flat
                            dark
                            @click="infoSnackbar.active = false"
                    >
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-snackbar>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import hu from 'vee-validate/dist/locale/hu';
    import uk from 'vee-validate/dist/locale/uk';
    import Form from 'vform'
    export default {
        name: "contact",
        head() {
            return {
                title: this.$t('navbar.contact'),
            }
        },
        data: () => ({
            expand: false,
            form: new Form({
                name: '',
                email: '',
                subject: '',
                message: '',
                recaptchaToken:''
            }),
            infoSnackbar:{
                active:false,
                timeout:3000,
                success:true
            },
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
        async mounted() {
            this.setLanguage($nuxt.$i18n.locale)
            this.expand = true
        },
        methods: {
            async sendMail() {
                try{
                    this.form.recaptchaToken = await this.$recaptcha.getResponse()

                    await this.form.put('/contact/store')

                    this.form.reset()
                    this.errors.clear()

                    this.infoSnackbar.success = true
                    this.infoSnackbar.active = true

                }catch (e) {
                    this.infoSnackbar.success = false
                    this.infoSnackbar.active = true
                }

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
        computed: {
            breakpoint() {
                return this.$vuetify.breakpoint.xs
            }
        },
    }
</script>

<style scoped lang="css">
    .contact-bg {
        background-image: linear-gradient(rgb(0, 0, 255), rgb(0, 0, 100));
    }
    .g-recaptcha div{
        margin: auto !important;
    }
</style>
