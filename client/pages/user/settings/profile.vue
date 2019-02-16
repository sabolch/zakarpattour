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
                            <v-icon size="120">account_circle</v-icon>
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
                                    v-validate="'required|'"
                                    :error-messages="errors.collect('telephone')"
                                    label="Telephone"
                                    data-vv-name="telephone"
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
        name: "profile",
        data: () => ({
            expand: false,
            form: new Form({
                name: 'User',
                email: 'user@gmail.com',
                telephone: '0689051401'
            }),
        }),
    }
</script>

<style scoped>

</style>
