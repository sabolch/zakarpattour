<template>
    <v-container fluid pa-0 ma-0>
        <v-layout row wrap>
            <v-flex xs12>
                Hello admin profile
            </v-flex>
            <image-upload></image-upload>
        </v-layout>
    </v-container>
</template>

<script>
    import ImageUpload from "../../../components/admin/image-upload";
    export default {
        name: "profile",
        components: {ImageUpload},
        layout: "admin",

        head() {
            return {
                title: 'Admin Profile'
            }
        },
    }
</script>

<style scoped>

</style>
