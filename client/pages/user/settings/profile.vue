<template>
    <v-flex xs12>
        <v-card height="550">
            <v-form>
                <v-container fill-height fluid>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <h1 class="headline font-weight-bold">Profile</h1>
                        </v-flex>

                        <v-flex xs12 class="text-xs-center">
                            <v-avatar
                                    :size="size"
                            >
                                <v-img
                                        :src="avatar" alt="avatar"
                                        @mouseover="changeActive = true"
                                        @mouseout="changeActive = false"
                                >
                                    <v-layout
                                            slot="placeholder"
                                            fill-height
                                            align-center
                                            justify-center
                                            ma-0
                                    >
                                        <v-progress-circular indeterminate color="blue lighten-5"></v-progress-circular>
                                    </v-layout>
                                    <v-layout
                                            v-show="changeActive"
                                            fill-height
                                            align-end
                                            justify-center
                                    >
                                        <v-flex>
                                            <v-card
                                                    hover
                                                    dark
                                                    class="pl-1 pr-1 pt-2 pb-3 font-weight-light title indigo"
                                                    @click="pickFile"
                                            >
                                                Change
                                            </v-card>
                                            <input
                                                    type="file"
                                                    style="display: none"
                                                    ref="image"
                                                    accept="image/jpeg"
                                                    @change="onFilePicked"
                                            >
                                        </v-flex>
                                    </v-layout>
                                </v-img>

                            </v-avatar>
                        </v-flex>
                        <v-flex xs12 class="pa-4">
                            <v-text-field
                                    v-model="form.name"
                                    v-validate="'required'"
                                    :error-messages="errors.collect('name')"
                                    label="Username"
                                    data-vv-name="username"
                                    clearable
                                    required
                            ></v-text-field>
                            <v-text-field
                                    disabled
                                    v-model="form.email"
                                    v-validate="'required|email'"
                                    :error-messages="errors.collect('email')"
                                    label="Email"
                                    data-vv-name="email"
                                    clearable
                                    required
                            ></v-text-field>
                            <v-text-field
                                    mask="phone"
                                    v-model="form.telephone"
                                    v-validate="'required|min:10'"
                                    :error-messages="errors.collect('telephone')"
                                    label="Telephone"
                                    data-vv-name="telephone"
                                    clearable
                                    required
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 class="text-xs-right">
                            <v-btn color="primary"
                                   @click="updateUser"
                                   :loading="form.busy"
                            >
                                Save
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>

        <v-snackbar
                v-model="infoSnackbar.active"
                :timeout="infoSnackbar.timeout"
                :color="infoSnackbar.success ? 'success' : 'error'"
                top
                >
            {{ infoSnackbar.success ? 'Successfully saved' : 'Somethings went wrong :( Try again later'}}
            <v-btn
                    flat
                    dark
                    @click="infoSnackbar.active = false"
            >
                <v-icon>close</v-icon>
            </v-btn>
        </v-snackbar>

    </v-flex>
</template>

<script>
    import Form from 'vform'
    import { mapGetters } from 'vuex'

    export default {
        name: "profile",
        scrollToTop: false,
        head() {
            return {
                title: 'Profile Settings',
            }
        },
        data: () => ({
            avatar: '',
            tile: true,
            size: 140,
            changeActive: false,

            infoSnackbar:{
                active:false,
                timeout:3000,
                success:true
            },

            title: "Image Upload",
            dialog: false,
            imageName: '',
            imageUrl: '',
            imageFile: '',

            formData: {},
            expand: false,

            form: new Form({
                name: 'User',
                email: 'user@gmail.com',
                telephone: '0689051401'
            }),
            formErrors:{}
        }),
        computed: mapGetters({
            user: 'auth/user'
        }),
        created () {
            // Fill the form with user data.
            this.form.keys().forEach(key => {
                this.form[key] = this.user[key]
            })
        },
        mounted() {
            this.formData = new FormData()
            if (this.$store.state.auth.user) {
                this.avatar = `${this.$store.state.auth.user.photo_url}/256x256`;
            }
            //     this.form.name = this.$store.state.auth.user.name;
            //     this.form.email = this.$store.state.auth.user.email;
            //     this.form.telephone = this.$store.state.auth.user.telephone;
            // }
        },
        methods: {
            async updateUser() {
                try{
                    const { data } = await this.form.patch('/settings/profile')
                    this.$store.dispatch('auth/updateUser', { user: data })

                    this.infoSnackbar.success = true
                    this.infoSnackbar.active = true
                }catch (e) {
                    this.formErrors = e.response.data.errors
                    this.infoSnackbar.success = false
                    this.infoSnackbar.active = true
                    setTimeout(()=>{
                        this.formErrors = {}
                    },6000);
                }
            },
            async updateAvatare(image) {
                try {
                    const foobar = await this.$axios.$post('/user/store/avatar', this.formData, {
                            headers: {
                                'accept': 'application/json',
                                'Accept-Language': 'en-US,en;q=0.8',
                                'Content-Type': `multipart/form-data; boundary=${this.formData._boundary}`,
                            }
                        }
                    )
                    this.infoSnackbar.success = true
                    this.infoSnackbar.active = true
                }catch (e) {
                    this.infoSnackbar.success = false
                    this.infoSnackbar.active = true
                }
            },
            pickFile() {
                this.$refs.image.click()
            },

            async onFilePicked(e) {
                const files = e.target.files
                if (files[0] !== undefined) {
                    this.imageName = files[0].name
                    if (this.imageName.lastIndexOf('.') <= 0) {
                        return
                    }
                    const fr = new FileReader()
                    fr.readAsDataURL(files[0])
                    fr.addEventListener('load', () => {
                        this.imageUrl = fr.result
                        this.imageFile = files[0] // this is an image file that can be sent to server...

                        this.formData.append('avatar', files[0], files[0].fileName)
                        this.formData.append('user_id', this.$store.state.auth.user.id)

                        this.updateAvatare()
                        this.avatar = this.imageUrl

                    })
                } else {
                    this.imageName = ''
                    this.imageFile = ''
                    this.imageUrl = ''
                }
            }
        }
    }
</script>

<style scoped>

</style>
