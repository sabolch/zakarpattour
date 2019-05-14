<template>
    <!--<v-card class="my-1">-->
        <!--<v-img-->
                <!--:src="'/images/Munkacs_vara.jpg'"-->
                <!--aspect-ratio="1"-->
                <!--height="300px"-->
                <!--class="grey lighten-2"-->
        <!--&gt;-->
            <!--<v-layout-->
                    <!--slot="placeholder"-->
                    <!--fill-height-->
                    <!--align-center-->
                    <!--justify-center-->
                    <!--ma-0-->
            <!--&gt;-->
                <!--<v-progress-circular indeterminate color="grey lighten-5"/>-->
            <!--</v-layout>-->

            <!--<v-card-actions>-->
                <!--<span class="white&#45;&#45;text amber darken-4 pa-1 font-weight-black subheading">#{{getName(item.category.translations)}}</span>-->
                <!--<v-spacer></v-spacer>-->
                <!--<btn-favorite type="sight" :itemID="item.id"></btn-favorite>-->
            <!--</v-card-actions>-->
        <!--</v-img>-->
        <!--<v-card-text class="align-center text-xs-justify pa-2">-->
            <!--<h1 class="headline">{{ getTitle(item.translations) }}</h1>-->
            <!--<v-rating-->
                    <!--color="blue darken-3"-->
                    <!--readonly-->
                    <!--background-color="grey darken-1"-->
                    <!--medium-->
                    <!--v-model="rating"-->
            <!--&gt;</v-rating>-->
        <!--</v-card-text>-->
        <!--<v-card-actions>-->
            <!--<share-btns></share-btns>-->
            <!--<v-spacer/>-->
            <!--<v-btn flat class="blue&#45;&#45;text"-->
                   <!--:to="{name:'sight.show',params: {slug:item.slug}}"-->
                   <!--outline-->
            <!--&gt;-->
                <!--{{$t('btns.read_more')}}-->
            <!--</v-btn>-->
        <!--</v-card-actions>-->

    <!--</v-card>-->


    <v-card class="my-1">
        <lazy-item-img
                :itemID="item.id"
                type="sight"
                :src="'/images/Munkacs_vara.jpg'"
                category="category"
        >

        </lazy-item-img>
        <v-card-text class="align-center text-xs-justify pa-2">
            <h1 class="headline">{{ getTitle(item.translations) }}</h1>
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
                default: {}
            }

        },
        data() {
            return {
                rating: 4,
            }
        },
        computed: {
            getLocal() {
                return this.$i18n.locale === 'uk' ? 'ua' : this.$i18n.locale
            }
        },
        methods: {
            getTitle(item) {
                return item.find(obj => obj.locale ===  this.getLocal).title
            },
          }
    }
</script>

<style scoped>

</style>
