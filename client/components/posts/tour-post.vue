<template>
    <v-card class="my-1" :dark="dark">
        <lazy-item-img
                :itemID="0"
                type="tour"
                :src="item.image"
                category="category"
        ></lazy-item-img>
        <v-card-text class="align-center text-xs-justify pa-2">
            <h1 class="headline">Lorem ipsum dolor set amit</h1>
            <v-rating
                    color="blue darken-3"
                    readonly
                    background-color="grey darken-1"
                    medium
                    v-model="rating"
            ></v-rating>
            <v-chip v-for="i in 3" :key="i" color="blue darken-4" outline>
                <v-icon left>location_on</v-icon>
                Location {{i}}
            </v-chip>
        </v-card-text>
        <v-card-actions>
            <share-btns></share-btns>
            <v-spacer/>
            <v-btn flat class="blue--text"
                   @click="readMore(item.slug)"
                   outline
            > {{ $t('btns.read_more') }}
            </v-btn>
        </v-card-actions>

    </v-card>
</template>

<script>
    import LazyItemImg from "../lazy-load/lazy-item-img";
    import ShareBtns from "../global/share-btns";
    export default {
        name: "tour-post",
        components: {ShareBtns, LazyItemImg},
        props: {
            dark: {
                type: Boolean,
                default: false
            },
            item: {
                type: Object,
                default: {

                }
            }

        },
        data (){
            return{
                rating:4,
            }
        }
    }
</script>

<style scoped>

</style>
