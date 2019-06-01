<template>
    <v-card class="my-1" :dark="dark">
        <lazy-item-img
                :itemID="5"
                type="tour"
                :src="imgUrl"
                :category="`${getName(item.category.translations)}`"
        ></lazy-item-img>
        <v-card-text class="align-center text-xs-justify pa-2">
            <h1 class="headline">{{getTitle(item.translations)}}</h1>
            <v-rating
                    color="blue darken-3"
                    readonly
                    background-color="grey darken-1"
                    medium
                    v-model="rating"
            ></v-rating>
            <v-expansion-panel
                    color="purple"
                    v-model="panel"
                    popout
            >
                <v-expansion-panel-content>
                    <template v-slot:header>
                        <div>
                            <v-icon large color="blue darken-4">timeline</v-icon>
                        </div>
                        <div class="title blue--text text--darken-4">
                            Locations
                        </div>
                    </template>
                    <v-card>
                        <v-card-text>
                            <v-chip
                                    color="blue darken-4"
                                    outline
                                    v-for="maker in item.markers"
                                    :key="maker.slug"
                                    @click="$router.push({name:'sight.show',params: {slug:maker.slug}})"
                            >
                                <v-icon left>location_city</v-icon>
                                {{ getTitle(maker.translations) }}
                            </v-chip>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>

        </v-card-text>
        <v-card-actions>
            <share-btns></share-btns>
            <v-spacer/>
            <v-btn flat class="blue--text"
                   :to="{name:'tour.show',params: {slug:item.slug}}"
                   outline
            >
                {{$t('btns.read_more')}}
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
                default: {},
                required:true
            }

        },
        data() {
            return {
                rating: 4,
                panel: [],
            }
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            },
            imgUrl(){
                return `${this.$axios.defaults.baseURL}/image/title/tour/${this.item.id}`;
            }
        },
        methods:{
            getTitle(item) {
                return item.find(obj => obj.locale ===  this.getLocal).title
            },
            getName(item) {
                return item.find(obj => obj.locale ===  this.getLocal).name
            },
        }
    }
</script>

<style scoped>

</style>
