<template>
    <v-card class="my-1">
        <lazy-item-img
                :itemID="item.id"
                type="sight"
                :src="imgUrl"
                :category="`${getName(item.category.translations)}`"
        >

        </lazy-item-img>
        <v-card-text class="align-center text-xs-justify pa-2">
            <h1 class="headline">{{ getTitle(item.translations) }}</h1>
            <v-chip color="blue darken-4" outline>
                <v-icon left>location_city</v-icon>
                {{ getTitle(item.settlement.translations) }}
            </v-chip>
            <v-rating
                    color="blue darken-3"
                    readonly
                    background-color="grey darken-1"
                    medium
                    v-model="rating"
            ></v-rating>
        </v-card-text>
        <v-card-actions>
            <share-btns></share-btns>
            <v-spacer/>
            <v-btn flat class="blue--text"
                   :to="{name:'sight.show',params: {slug:item.slug}}"
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
        name: "sight-post",
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
            }
        },
        computed: {
            getLocal() {
                return this.$i18n.locale
            },
             imgUrl(){
                return `${this.$axios.defaults.baseURL}/image/title/sight/${this.item.id}`;
            }
        },
        methods: {
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
