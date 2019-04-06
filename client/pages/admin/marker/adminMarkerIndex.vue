<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-stepper v-model="e6" vertical>
                    <v-stepper-step :complete="e6 > 1" step="1">
                        Select an app
                        <small>Summarize if needed</small>
                    </v-stepper-step>

                    <v-stepper-content step="1">
                        <v-tabs
                                fixed-tabs
                                color="primary"
                                dark
                                slider-color="white"
                        >
                            <v-tab
                                    v-for="item in form.translations"
                                    :key="item"
                                    ripple
                            >
                                {{ item.locale.toUpperCase() }}
                            </v-tab>
                            <v-tab-item
                                    v-for="item in form.translations"
                                    :key="item"
                            >
                                <v-card flat>
                                    <v-card-text>
                                        <v-text-field
                                                v-model="item.title"
                                                :label="`Title ${item.locale}`"
                                                clearable
                                        ></v-text-field>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                        <v-btn color="primary" @click="e6 = 2">Continue</v-btn>
                        <v-btn flat>Cancel</v-btn>
                    </v-stepper-content>

                    <v-stepper-step :complete="e6 > 2" step="2">Configure analytics for this app</v-stepper-step>

                    <v-stepper-content step="2">
                        <v-tabs
                                fixed-tabs
                                color="primary"
                                dark
                                slider-color="white"
                        >
                            <v-tab
                                    v-for="item in form.translations"
                                    :key="item"
                                    ripple
                            >
                                {{ item.locale.toUpperCase() }}
                            </v-tab>
                            <v-tab-item
                                    v-for="item in form.translations"
                                    :key="item"
                            >
                                <v-card flat>
                                    <v-card-text>
                                        <quill-editor @get-content="getEditorContent"></quill-editor>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>
                        </v-tabs>

                        <v-btn color="primary" @click="e6 = 3">Continue</v-btn>
                        <v-btn flat>Cancel</v-btn>
                    </v-stepper-content>

                    <v-stepper-step :complete="e6 > 3" step="3">Select an ad format and name ad unit</v-stepper-step>

                    <v-stepper-content step="3">
                        <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                        <v-btn color="primary" @click="e6 = 4">Continue</v-btn>
                        <v-btn flat>Cancel</v-btn>
                    </v-stepper-content>

                    <v-stepper-step step="4">View setup instructions</v-stepper-step>
                    <v-stepper-content step="4">
                        <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                        <v-btn color="primary" @click="e6 = 1">Continue</v-btn>
                        <v-btn flat>Cancel</v-btn>
                    </v-stepper-content>
                </v-stepper>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "adminMarkerIndex",
        // components: {QuillEditor},
        layout:"admin",
        head() {
            return {
                title: this.$t('navbar.home'),
            }
        },
        data () {
            return {
                e6: 1,
                tabs: null,
                form: new Form({
                    id: '',
                    category:'',
                    translations: [
                        {locale: 'en', title: '',description:''},
                        {locale: 'hu', title: '',description:''},
                        {locale: 'ua', title: '',description:''},
                    ],
                }),
            }
        },
        mounted() {},
        methods: {
            getEditorContent(e){
                console.log("Parent log : " + e)
            }
        }
    }
</script>
<style lang="scss" scoped>
</style>
