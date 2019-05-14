<template>
    <v-img
            :src="srcImage"
            aspect-ratio="1"
            :height="height"
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
            <span class="white--text amber darken-4 pa-1 font-weight-black subheading">#{{category}}</span>
            <v-spacer></v-spacer>
            <btn-favorite type="sight" :itemID="itemID"></btn-favorite>
        </v-card-actions>
    </v-img>
</template>

<script>
    import BtnFavorite from "../global/btn-favorite";
    export default {
        name: "lazy-item-img",
        components: {BtnFavorite},
        props: {
            dark: {
                type: Boolean,
                default: false
            },
            src: {
                type: String,
                required: true
            },
            category: {
                type: String,
                required: true
            },
            type: {
                type: String,
                required: true
            },
            height: {
                type: Number,
                default: 300,
            },
            itemID: {
                type: Number,
                required: true
            }
        },
        data: () => ({observer: null, intersected: false}),
        computed: {
            srcImage() {
                return this.intersected ? this.src : '';
            }
        },
        mounted() {
            this.observer = new IntersectionObserver(entries => {
                const image = entries[0];
                if (image.isIntersecting) {
                    this.intersected = true;
                    this.observer.disconnect();
                }
            });

            this.observer.observe(this.$el);
        },
        destroyed() {
            this.observer.disconnect();
        }
    }
</script>

<style scoped>

</style>


