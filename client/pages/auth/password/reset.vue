<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <v-flex xs12 sm8 md4>
                <v-slide-y-transition>
                    <v-card class="elevation-12" v-show="expand">
                        <v-toolbar color="primary">
                            <v-toolbar-title class="white--text">{{$t('reset_password')}}</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text class="mb-0 pb-0">
                            <v-form @submit.prevent="reset" @keydown="form.onKeydown($event)">
                                <v-alert
                                        :value="alert.status"
                                        :type="alert.type"
                                >
                                    {{ alert.status }}
                                </v-alert>
                                <alert-success :form="form" :message="status"/>

                                <v-text-field v-model="form.email" prepend-icon="mail" name="email"
                                              v-validate="'required|email|max:60'"
                                              :error-messages="errors.collect('email')"
                                              data-vv-name="email"
                                              disabled
                                              :label="$t('form.email')"
                                              type="text"></v-text-field>
                                <v-text-field v-model="form.password" id="password" prepend-icon="lock" name="password"
                                              ref="password"
                                              :label="$t('password')"
                                              :type="showPassword ? 'text' : 'password'"
                                              :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                                              @click:append="showPassword = !showPassword"
                                              v-validate="'required|min:7|max:15'"
                                              :error-messages="errors.collect('password')"
                                              data-vv-name="password"

                                ></v-text-field>
                                <v-text-field v-model="form.password_confirmation" id="password_confirmation"
                                              prepend-icon="repeat" name="password_confirmation"
                                              :label="$t('confirm_password')"
                                              :type="showConfPassword ? 'text' : 'password'"
                                              :append-icon="showConfPassword ? 'visibility_off' : 'visibility'"
                                              @click:append="showConfPassword = !showConfPassword"
                                              v-validate="'confirmed:password'"
                                              :error-messages="errors.collect('password_confirmation')"
                                              data-vv-name="password_confirmation"
                                ></v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions class="mt-0 pt-0">
                            <v-layout row wrap>

                                <v-flex xs12>
                                    <v-layout row wrap>
                                        <v-flex class="text-xs-right">
                                            <v-btn
                                                    color="primary"
                                                    @click="reset"
                                                    :loading="form.busy"
                                            >
                                                {{ $t('reset_password') }}
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
        head() {
            return {title: this.$t('reset_password')}
        },

        data: () => ({
            expand:true,
            status: '',
            showPassword:false,
            showConfPassword:false,
            alert:{
                status: '',
                type:'success',
                timeout:6000,
            },

            form: new Form({
                token: '',
                email: '',
                password: '',
                password_confirmation: ''
            })
        }),

        created() {
            this.form.email = this.$route.query.email
            this.form.token = this.$route.params.token
        },

        methods: {
            async reset() {

                try{
                    const {data} = await this.form.post('/password/reset')

                    this.status = data.status
                    this.form.reset()

                    this.alert.type = 'success'
                    this.alert.status = data.status
                    setTimeout(()=>{
                        this.alert.status = ''
                    },this.alert.timeout)
                    this.form.reset()
                }catch (e) {
                    this.alert.type = 'error'
                    this.alert.status = 'Somethings went wrong! :( Be sure the access token is valid'
                    setTimeout(()=>{
                        this.alert.status = ''
                    },this.alert.timeout)
                    // console.log(e)
                }

            }
        }
    }
</script>
