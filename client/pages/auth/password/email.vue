<template>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md4>
        <v-slide-y-transition>
          <v-card class="elevation-12" v-show="expand">
            <v-toolbar color="primary">
              <v-toolbar-title class="white--text">Send reset password email</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text class="mb-0 pb-0">
              <v-form @submit.prevent="send" @keydown="form.onKeydown($event)">
                <v-alert
                        :value="alert.status"
                        :type="alert.type"
                >
                  {{ alert.status }}
                </v-alert>
                <v-text-field v-model="form.email" prepend-icon="mail" name="email"
                              v-validate="'required|email|max:60'"
                              :error-messages="errors.collect('email')"
                              data-vv-name="email"
                              clearable
                              label="Email"
                              type="text"></v-text-field>
                        </v-form>
            </v-card-text>
            <v-card-actions class="mt-0 pt-0">
              <v-layout row wrap>

                <v-flex xs12>
                  <v-layout row wrap>
                    <v-flex class="text-xs-right">
                      <v-btn
                              color="primary"
                              @click="send"
                              :loading="form.busy"
                      >Send
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
import hu from 'vee-validate/dist/locale/hu';
import uk from 'vee-validate/dist/locale/uk';

export default {
  head () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    expand:false,
    alert:{
      status: '',
      type:'success',
      timeout:6000,
    },
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      try{
        const { data } = await this.form.post('/password/email')
        this.alert.type = 'success'
        this.alert.status = data.status
        setTimeout(()=>{
          this.alert.status = ''
        },this.alert.timeout)
        this.form.reset()
      }catch (e) {
        this.alert.type = 'error'
        this.alert.status = 'Somethings went wrong! :( Be sure the email address already registered'
        setTimeout(()=>{
          this.alert.status = ''
        },this.alert.timeout)
        // console.log(e)
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
    },
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
