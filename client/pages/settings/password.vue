<template>
    <v-card :title="$t('your_password')">
        <form @submit.prevent="update" @keydown="form.onKeydown($event)">
            <alert-success :form="form" :message="$t('password_updated')"/>

            <!-- Password -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('new_password') }}</label>
                <div class="col-md-7">
                    <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }"
                           type="password" name="password"
                           class="form-control"
                           :type="showPassword ? 'text' : 'password'"
                           :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                    >
                    <has-error :form="form" field="password"/>
                </div>
            </div>

            <!-- Password Confirmation -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">{{ $t('confirm_password') }}</label>
                <div class="col-md-7">
                    <input v-model="form.password_confirmation"
                           :class="{ 'is-invalid': form.errors.has('password_confirmation') }" type="password"
                           name="password_confirmation"
                           class="form-control">
                    <has-error :form="form" field="password_confirmation"/>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-9 ml-md-auto">
                    <v-button :loading="form.busy" type="success">{{ $t('update') }}</v-button>
                </div>
            </div>
        </form>
    </v-card>
</template>

<script>
    import Form from 'vform'

    export default {
        scrollToTop: false,
        show1:false,
        show2:false,
        show3:false,

        head() {
            return {
                title: 'Password Settings',
            }
        },

        data: () => ({
            infoSnackbar:{
                active:false,
                timeout:3000,
                success:true
            },

            form: new Form({
                password: '',
                password_confirmation: ''
            }),
            formDestroy: new Form(),
        }),

        methods: {
            async update() {
                try {
                    await this.form.patch('/settings/password')
                    this.form.reset()
                } catch (e) {
                    console.log(e.response)
                }
            },
            async destroy() {
                try {
                    await this.formDestroy.delete('/settings/destroy')
                    await this.$store.dispatch('auth/logout')
                    // Redirect to login.
                    this.$router.push({name: 'login'})
                } catch (e) {
                    console.log(e.response)
                }
            }
        }
    }
</script>
