<template>
    <v-app id="login" class="primary">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md6 lg4>
                        <v-card class="elevation-1 pa-3">
                            <v-card-text class="text-xs-center">
                                <div class="layout column align-center">
                                    <img src="/images/logo.png" alt="Vue Material Admin" width="320" height="240">
                                    <h1 class="flex my-4 primary--text">Admin Login</h1>
                                </div>
                                <v-form>
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
                                    <vue-recaptcha
                                            ref="recaptcha"
                                            :sitekey="sitekey"
                                            @verify="onCaptchaVerified"
                                            @expired="onCaptchaExpired"
                                            id="g-recaptcha"
                                    ></vue-recaptcha>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                        :disabled="form.recaptchaToken.length < 1"
                                        color="primary"
                                        @click="login"
                                        :loading="form.busy"
                                >Login</v-btn>
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
        // middleware:'admin',
        name: "login",
        components: {VueRecaptcha},
        data: () => ({
            showPassword: false,
            sitekey:'6LfeIXYUAAAAACI0h2MIPpDZiJ9a-uAZwrVMsxJ2',

            form: new Form({
                email: '',
                password: '',
                recaptchaToken: ''
            })
        }),
        async mounted() {
            this.$refs.recaptcha.sitekey = '6LfeIXYUAAAAACI0h2MIPpDZiJ9a-uAZwrVMsxJ2'
        },
        methods: {
            login() {
               this.form.busy = true
            },
            onCaptchaVerified: function (recaptchaToken) {
                this.form.recaptchaToken = recaptchaToken
            },
            onCaptchaExpired: function () {
                this.$refs.recaptcha.reset()
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
    #g-recaptcha div{
        margin: auto !important;
    }
</style>

