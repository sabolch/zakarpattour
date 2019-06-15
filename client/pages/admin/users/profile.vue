<template>
    <v-container fluid>
        <v-layout justify-center>
            <v-flex xs12 md6>
            <v-card>
                <v-card-text>
                    <v-flex class="mb-4 text-xs-center">
                        <v-avatar size="150" class="mr-4">
                            <img src="https://img.icons8.com/bubbles/2x/admin-settings-male.png">
                        </v-avatar>
                    </v-flex>
                    <v-text-field
                            readonly
                            v-model="getAdmin.name"
                            label="Name"></v-text-field>
                    <v-text-field
                            readonly
                            v-model="getAdmin.email"
                            label="Email"></v-text-field>
                    <v-text-field
                            readonly
                            v-model="getAdmin.telephone"
                            label="Telephone"></v-text-field>
                    <v-text-field
                            readonly
                            v-model="getAdmin.created_at"
                            label="Created"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary">
                        {{$t('btns.save')}}

                    </v-btn>
                </v-card-actions>
            </v-card>
            </v-flex>
        </v-layout>

    </v-container>
</template>

<script>

    export default {
        name: "profile",
        layout: "admin",

        head() {
            return {
                title: 'Admin Profile'
            }
        },

        data () {
            return {
                loading: false,
            }
        },
        computed:{
            getAdmin(){
                return this.$store.state.admin.user;
            }
        }
    }
</script>

<style scoped>

</style>
