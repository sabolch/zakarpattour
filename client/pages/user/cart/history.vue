<template>
    <v-flex xs12>
        <v-layout row wrap>
            <v-flex xs12>
                <v-container grid-list-md pa-0 ma-0 style="min-height: 550px" fill-height>
                    <v-layout row wrap ma-0 pa-0 align-start>
                        <v-flex v-for="item in 5" :key="item" xs12 md4>
                            <v-card>
                                <v-img
                                        :src="'/images/Munkacs_vara.jpg'"
                                        aspect-ratio="1"
                                        height="200"
                                        class="grey lighten-2"
                                >
                                    <v-layout
                                            slot="placeholder"
                                            fill-height
                                            align-center
                                            justify-center
                                            ma-0
                                    >
                                        <v-progress-circular indeterminate color="grey lighten-5"/>
                                    </v-layout>

                                    <v-card-actions>
                                        <span class="white--text amber darken-4 pa-1 subheading font-weight-bold">Price: &euro;255</span>
                                        <v-spacer></v-spacer>

                                    </v-card-actions>
                                </v-img>
                                <v-card-text class="align-center text-xs-justify pa-2">
                                    <h1 class="title">Lorem ipsum dolor set amit</h1>
                                    <v-rating
                                            color="blue darken-3"
                                            readonly
                                            background-color="grey darken-1"
                                            v-model="rating"
                                    ></v-rating>
                                    <h1 class="subheading font-weight-bold"><v-icon color="red lighteen-4">date_range</v-icon> 2019-05-11</h1>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn icon class="purple--text">
                                        <v-icon medium>sb-instagram</v-icon>
                                    </v-btn>
                                    <v-btn icon class="light-blue--text">
                                        <v-icon medium>sb-twitter</v-icon>
                                    </v-btn>
                                    <v-btn icon class="blue--text text--darken-4">
                                        <v-icon medium>sb-facebook</v-icon>
                                    </v-btn>
                                    <v-spacer/>
                                    <v-btn flat class="blue--text"
                                           @click="readMore(item.slug)"
                                           outline
                                    >Read More
                                    </v-btn>
                                </v-card-actions>

                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs12>
                <v-container fluid pa-0 ma-0 fill-height>
                    <v-layout ma-0 pa-0>
                        <v-flex xs12 class="text-xs-center">
                            <v-card class="pa-3">
                                <v-pagination
                                        v-model="page"
                                        :length="6"
                                ></v-pagination>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-flex>
</template>

<script>
    export default {
        name: "history",
        data() {
            return {
                page:1,
                rating: 4,
                available: true,

            }
        },
        methods: {
            remove(item) {
                console.log(item)
            }
        }

    }
</script>

<style scoped>

</style>
