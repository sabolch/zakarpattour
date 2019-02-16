<template>
    <v-flex xs12>
        <v-card height="550">
            <v-form>
            <v-container fill-height fluid>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-title class="headline font-weight-bold">Change your password</v-title>
                    </v-flex>
                    <v-flex xs12 class="pa-4">
                        <v-text-field
                                v-model="form.oldPassword"
                                v-validate="'required'"
                                :error-messages="errors.collect('oldPassword')"
                                label="Old Password"
                                data-vv-name="oldPassword"
                                clearable
                                required
                        ></v-text-field>
                        <v-text-field
                                ref="newPassword"
                                v-model="form.newPassword"
                                v-validate="'required|min:5|max:15'"
                                :error-messages="errors.collect('newPassword')"
                                label="New Password"
                                data-vv-name="newPassword"
                                clearable
                                required
                        ></v-text-field>
                        <v-text-field
                                v-model="form.confirmNewPasswod"
                                v-validate="'required|confirmed:newPassword'"
                                :error-messages="errors.collect('confirmNewPasswod')"
                                label="Confirm Password"
                                data-vv-name="confirmNewPasswod"
                                clearable
                                required
                        ></v-text-field>
                    </v-flex>
                    <v-flex xs12 class="text-xs-right">
                        <v-btn color="primary">
                            Save
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            </v-form>
        </v-card>
    </v-flex>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "password",
        data: () => ({
            expand: false,
            form: new Form({
                oldPassword: '',
                newPassword: '',
                confirmNewPasswod: ''
            }),
            remember: false,
        }),
    }
</script>

<style scoped>

</style>
