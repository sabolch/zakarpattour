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
                <alert-success :form="form" :message="status"/>
                <v-text-field v-model="form.email" prepend-icon="mail" name="email" label="Email"
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

export default {
  head () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    expand:false,
    status: '',
    form: new Form({
      email: ''
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/password/email')

      this.status = data.status

      this.form.reset()
    }
  },
  mounted () {
    this.expand = true
  }
}
</script>
