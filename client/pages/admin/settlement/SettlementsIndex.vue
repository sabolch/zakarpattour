<template>
    <div>
        <v-tabs
                centered
                color="primary"
                dark
                icons-and-text
        >
            <v-tabs-slider color="red"></v-tabs-slider>

            <v-tab :to="{name:'admin.settlement'}">
                {{$t('navbar.settlements')}}
                <v-icon large>list</v-icon>
            </v-tab>

            <v-tab :to="{name:'ast'}">
                {{$t('navbar.trash')}}
                <v-icon large>history</v-icon>
            </v-tab>
        </v-tabs>
        <router-view/>
    </div>
</template>
<script>


    export default {
        name: "SettlementsIndex",
        layout: "admin",

    }
</script>

