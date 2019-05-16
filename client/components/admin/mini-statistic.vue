<template>
            <v-card>
                <v-list class="pa-4" >
                    <v-list-tile
                            :key="item.title"
                            avatar
                            :color="item.color"
                    >
                        <v-list-tile-avatar class="ma-2 mr-4">
                            <v-icon :color="item.color" :size="breakpoint ? 50 : 70 ">{{item.icon}}</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title class="headline font-weight-bold pb-2">{{item.title}}+</v-list-tile-title>
                            <v-list-tile-sub-title class="pt-2 subheading font-weight-regular">{{item.subTitle}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>

            </v-card>
     </template>

<script>
    export default {
        name: "mini-statistic",
        props:{
            item:{
                type:Object,
                required:true
            }
        },
        computed:{
            breakpoint() {
                return this.$vuetify.breakpoint.xs
            }
        }
    }
</script>

<style scoped>

</style>
