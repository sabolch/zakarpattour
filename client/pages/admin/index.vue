<template>
    <v-flex xs12>
        <v-card>
            <v-container fill-height fluid>
                <v-layout row>
                    <v-flex xs12>

                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
    </v-flex>
</template>

<script>
    export default {
        layout: 'admin',
        name: "index",

        data: () => ({
            dark: true,
            drawers: ['Default (no property)', 'Permanent', 'Temporary'],
            primaryDrawer: {
                model: null,
                type: 'default (no property)',
                clipped: false,
                floating: false,
                mini: false
            },
            footer: {
                inset: false
            }
        })
    }
</script>

<style scoped>

</style>
