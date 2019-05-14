<template>
    <v-parallax
            :dark="dark"
            :src="srcImage"
            :height="height"
    >
        <v-layout fill-height column ma-0>
            <v-spacer></v-spacer>
            <v-flex class="text-xs-right title font-weight-bold mb-3" shrink>
                /{{title}}/
            </v-flex>
        </v-layout>
    </v-parallax>
</template>

<script>
    export default {
        name: "lazy-paralax",
        props: {
            dark: {
                type: Boolean,
                default: false
            },
            src: {
                type: String,
                required: true
            },
            title: {
                type: String,
                required: true
            },
            height: {
                type: Number,
                default: 300,
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
