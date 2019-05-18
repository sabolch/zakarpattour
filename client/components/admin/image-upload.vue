<template>
    <v-flex xs12>

        <v-stepper v-model="e1">
            <v-stepper-header>
                <v-stepper-step editable :complete="e1 > 1" step="1">Upload Images</v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :complete="e1 > 2" step="2">Set cover image</v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1" :height="200">
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-container grid-list-md fluid pa-2 ma-0>
                                <v-layout row wrap>
                                    <v-flex v-if="uploades.length > 0" xs12>
                                        <v-card :elevation="2" color="green darken-2" dark
                                                class="pa-3 headline font-weight-bold">Uploaded Images
                                        </v-card>
                                    </v-flex>
                                    <v-flex
                                            v-for="(item,key) in uploades"
                                            :key="item.name"
                                            xs3
                                            d-flex
                                    >
                                        <v-card flat tile class="d-flex" :elevation="1">
                                            <v-img
                                                    :src="`${item.url}/${item.name}`"
                                                    aspect-ratio="1"
                                                    class="grey lighten-2"
                                            >
                                                <template v-slot:placeholder>
                                                    <v-layout
                                                            fill-height
                                                            align-center
                                                            justify-center
                                                            ma-0
                                                    >
                                                        <v-progress-circular indeterminate
                                                                             color="grey lighten-5"></v-progress-circular>
                                                    </v-layout>
                                                </template>
                                                <v-container fill-height fluid pa-0 ma-0>
                                                    <v-layout fill-height>
                                                        <v-flex xs12 align-end flexbox>
                                                            <v-btn
                                                                    fab
                                                                    small
                                                                    color="red darken-2"
                                                                    @click="removeImage(key,item.path)"
                                                            >
                                                                <v-icon medium class="white--text">delete_forever
                                                                </v-icon>
                                                            </v-btn>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-img>
                                        </v-card>
                                    </v-flex>


                                    <v-flex v-if="files.length > 0" xs12>
                                        <v-card :elevation="2" class="pa-3 headline font-weight-bold" color="primary"
                                                dark>Review Images
                                        </v-card>
                                        <v-alert
                                                :value="smallSize"
                                                type="error"
                                        >
                                            Small size images selected! Please select minimum
                                            {{`${this.minHeight}x${this.minWidth}`}} pixels or larger.
                                        </v-alert>
                                    </v-flex>
                                    <v-flex
                                            v-for="(item,key) in files"
                                            :key="item.name"
                                            xs3
                                            d-flex
                                    >
                                        <v-card :elevation="1" flat tile class="d-flex">
                                            <v-img
                                                    :src="item.previewUrl"
                                                    aspect-ratio="1"
                                                    ref="images"
                                                    class="grey lighten-2"
                                            >
                                                <template v-slot:placeholder>
                                                    <v-layout
                                                            fill-height
                                                            align-center
                                                            justify-center
                                                            ma-0
                                                    >
                                                        <v-progress-circular indeterminate
                                                                             color="grey lighten-5"></v-progress-circular>
                                                    </v-layout>
                                                </template>
                                                <v-container fill-height fluid pa-0 ma-0>
                                                    <v-layout fill-height>
                                                        <v-flex xs6>
                                                            <v-btn
                                                                    v-if="!item.uploaded"
                                                                    small
                                                                    fab
                                                                    color="red darken-2"
                                                                    @click="removeFile(key)"
                                                            >
                                                                <v-icon medium class="white--text">delete_forever
                                                                </v-icon>
                                                            </v-btn>

                                                        </v-flex>
                                                        <v-spacer></v-spacer>
                                                        <v-flex xs6 class="text-xs-right pa-2">
                                                            <v-tooltip bottom>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-icon v-if="item.small" large color="red"
                                                                            v-on="on">
                                                                        photo_size_select_large
                                                                    </v-icon>
                                                                </template>
                                                                <span>Small image size</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-img>
                                        </v-card>
                                    </v-flex>


                                </v-layout>
                            </v-container>
                        </v-flex>
                        <v-flex xs12>
                            <v-container pa-0 ma-0 fluid>
                                <v-progress-linear :hidden="!loading" :indeterminate="loading"></v-progress-linear>

                                <v-btn
                                        @click="selectFiles"
                                        color="primary"
                                        :disabled="loading"
                                >
                                    Select files
                                    <v-icon right dark>add_to_photos</v-icon>
                                </v-btn>
                                <v-btn
                                        :loading="loading"
                                        :disabled="loading"
                                        color="green darken-2"
                                        class="white--text"
                                        @click="submitFiles"
                                >
                                    Upload
                                    <v-icon right dark>cloud_upload</v-icon>
                                </v-btn>
                                <input hidden type="file" id="files" ref="files" multiple
                                       v-on:change="handleFileUploads()"
                                       accept="image/x-png,image/gif,image/jpeg"/>
                            </v-container>
                        </v-flex>
                    </v-layout>


                    <v-btn
                            color="primary"
                            @click="e1 = 2"
                            :disabled="uploades.length < 1"
                    >
                        Continue
                    </v-btn>

                </v-stepper-content>

                <v-stepper-content step="2">
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-container grid-list-md fluid pa-2 ma-0>
                                <v-layout row wrap>
                                    <v-flex v-if="uploades.length > 0" xs12>
                                        <v-card :elevation="2" color="green darken-2" dark
                                                class="pa-3 headline font-weight-bold">Uploaded Images
                                        </v-card>
                                    </v-flex>
                                    <v-flex
                                            v-for="(item,key) in uploades"
                                            :key="item.name"
                                            xs3
                                            d-flex
                                    >
                                        <v-card flat tile class="d-flex" :elevation="1">
                                            <v-img
                                                    :src="`${item.url}/${item.name}`"
                                                    aspect-ratio="1"
                                                    class="grey lighten-2"
                                            >
                                                <template v-slot:placeholder>
                                                    <v-layout
                                                            fill-height
                                                            align-center
                                                            justify-center
                                                            ma-0
                                                    >
                                                        <v-progress-circular indeterminate
                                                                             color="grey lighten-5"></v-progress-circular>
                                                    </v-layout>
                                                </template>
                                                <v-container fill-height fluid pa-0 ma-0>
                                                    <v-layout fill-height>
                                                        <v-flex xs12 align-end flexbox pa-2>
                                                            <v-tooltip bottom>
                                                                <template v-slot:activator="{ on }">
                                                                    <v-btn
                                                                            :disabled="loading"
                                                                            small
                                                                            color="primary"
                                                                            fab
                                                                            @click="setTitleImage(key,item.path)"
                                                                    >
                                                                        <v-icon medium dark
                                                                                v-on="on">
                                                                            add_photo_alternate
                                                                        </v-icon>
                                                                    </v-btn>
                                                                </template>
                                                                <span>Set as like cover image</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-img>
                                        </v-card>
                                    </v-flex>


                                    <v-flex xs12>
                                        <v-card :elevation="2" class="pa-3 headline font-weight-bold" color="primary"
                                                dark>Cover image
                                        </v-card>

                                    </v-flex>
                                    <v-flex
                                            xs3
                                            d-flex
                                    >

                                        <v-card :elevation="1" flat tile class="d-flex">
                                            <v-img
                                                    :src="titleImageUrl"
                                                    aspect-ratio="1"
                                                    ref="images"
                                                    class="grey lighten-2"
                                            >
                                                <template v-slot:placeholder>
                                                    <v-layout
                                                            fill-height
                                                            align-center
                                                            justify-center
                                                            ma-0
                                                    >
                                                        <v-progress-circular indeterminate
                                                                             color="grey lighten-5"></v-progress-circular>
                                                    </v-layout>
                                                </template>
                                            </v-img>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-layout>
                    <v-btn dark @click="e1 = 1" color="amber darken-2">Back</v-btn>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>

        <v-snackbar
                v-model="snackbar.status"
                :color="snackbar.color"
                :timeout="4000"
        >
            {{ snackbar.message }}
            <v-btn
                    dark
                    flat
                    @click="snackbar.status = false"
            >
                {{$t('btns.close')}}
            </v-btn>
        </v-snackbar>
    </v-flex>
</template>

<script>
    import Form from 'vform'
    export default {
        name: "image-upload",
        props: {
            itemID: {
                type: Number,
                required: true
            },
            uploadUrl: {
                type: String,
                default: 'image/upload'
            },
            removeUrl: {
                type: String,
                default: 'image/remove'
            },
            type: {
                type: String,
                default: 'sight'
            },
            minHeight: {
                type: Number,
                default: 500
            },
            minWidth: {
                type: Number,
                default: 400
            }

        }
        ,
        async mounted() {
            if(this.itemID < 1) return
           await this.loadData()
        },
        data() {
            return {
                e1: 0,
                files: [],
                loading: false,
                uploades: [],
                smallSize: false,
                titleImageUrl:'',
                snackbar:{
                    status:false,
                    timeout:4000,
                    color:'success',
                    message:'Successfully changed',
                },

            }
        }
        ,
        methods: {
            async loadData(val = null){
                try{
                    let id = val || this.itemID
                    let data = await this.$axios.get(`image/collect/${this.type}/${id}`)
                    this.uploades = data.data.splice(0)
                }catch (e) {
                    this.uploades = []
                    console.log('Filed to load images ...')
                }
                this.titleImageUrl = this.titleImgUrl();
            },
            handleFileUploads() {
                let filesArray = Array.from(this.$refs.files.files);

                filesArray = filesArray.map((item) => {
                    let previewUrl = URL.createObjectURL(item)
                    return {file: item, uploaded: false, small: false, previewUrl: previewUrl}
                });

                this.files = this.files.concat(filesArray.filter(item => {
                    return this.files.findIndex(obj => obj.file.name === item.file.name) < 0;
                }));

            }
            ,
            selectFiles() {
                this.$refs.files.click()
            }
            ,

            async submitFiles() {
                this.smallSize = false;

                if (!this.$refs.images) return
                // Check image size
                this.$refs.images.map((obj, index) => {
                    let img = obj.image;
                    if (img.height < this.minHeight || img.width < this.minWidth) {
                        this.files[index].small = true
                        this.smallSize = true
                    }
                })

                if (this.files < 1 || this.smallSize) return
                this.loading = true

                /*
                  Initialize the form data and iterate files
                */
                try {
                    for (let i = this.files.length - 1; i >= 0; i--) {
                        let formData = new FormData();
                        let file = this.files[i].file;
                        formData.append('file', file);
                        formData.append('type', this.type);
                        formData.append('id', this.itemID);
                        await this.upload(formData, i)
                        this.files[i].uploaded = true
                    }
                    this.loading = false
                    // Clear reviewed files
                    this.files = [];
                } catch (e) {

                    this.loading = false
                }

            }
            ,
            // Remove Uploaded Image
            async removeImage(key, name) {

                let form = new Form({
                    path: name,
                });

                this.loading = true
                form.delete(this.removeUrl).then(res => {
                    this.uploades.splice(key, 1)
                    this.loading = false
                }).catch(res => {
                    console.log(res)
                    this.loading = false
                });
            }
            ,
            // Remove File from array
            removeFile(key) {
                this.files.splice(key, 1)
            }
            ,
            // Return true if item is selected
            includes(array, name) {
                return array.some(item => item.name === name)
            }
            ,

            async setTitleImage(key, path) {
                this.titleImageUrl = ''
                this.loading = true
                let form = new Form({
                    id: this.itemID,
                    type: this.type,
                    path: path
                })
                try {
                    await form.post('/image/set/title').then(res => {
                        this.snackbar.status = true
                    })
                    this.loading = false
                } catch (e) {
                    this.loading = false
                    console.log(e)
                }
                this.titleImageUrl = this.titleImgUrl()
            },
            // Upload data to server
            async upload(formData, key) {
                var self = this;
                await this.$axios.post(this.uploadUrl,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function (res) {
                    self.uploades.push(res.data.image)
                })
                    .catch(function (v) {
                        console.log(v)
                        console.log('Erorr on upload ...');
                    });
            },
            titleImgUrl() {
                return `${this.$axios.defaults.baseURL}/image/title/${this.type}/${this.itemID}?${Math.random()}`;
            }
        },
    }
</script>

<style scoped>

</style>
