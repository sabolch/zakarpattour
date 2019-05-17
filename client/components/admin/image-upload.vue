<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-container grid-list-md fluid pa-2 ma-0>
                <v-layout row wrap>
                    <v-flex v-if="uploades.length > 0" xs12>
                        <v-card :elevation="2" color="green darken-2" dark class="pa-3 headline font-weight-bold">Uploaded Images</v-card>
                    </v-flex>
                    <v-flex
                            v-for="(item,key) in uploades"
                            :key="item.url"
                            xs3
                            d-flex
                    >
                        <v-card flat tile class="d-flex" :elevation="1">
                            <v-img
                                    :src="item.url"
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
                                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                    </v-layout>
                                </template>
                                <v-container fill-height fluid pa-0 ma-0>
                                    <v-layout fill-height>
                                        <v-flex xs12 align-end flexbox>
                                            <v-btn
                                                    small
                                                    fab
                                                    color="red darken-2"
                                                    @click="removeImage(key,item.path)"
                                            >
                                                <v-icon class="white--text">block</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-img>
                        </v-card>
                    </v-flex>


                    <v-flex v-if="files.length > 0" xs12>
                        <v-card :elevation="2"  class="pa-3 headline font-weight-bold" color="primary" dark>Review Images</v-card>
                        <v-alert
                                :value="smallSize"
                                type="error"
                        >
                            Small size images selected! Please select minimum {{`${this.minHeight}x${this.minWidth}`}} pixels or larger.
                        </v-alert>
                    </v-flex>
                    <v-flex
                            v-for="(item,key) in files"
                            :key="item.name"
                            xs3
                            d-flex
                    >
                        <v-card :elevation="1"  flat tile class="d-flex">
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
                                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
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
                                                <v-icon class="white--text">block</v-icon>
                                            </v-btn>

                                        </v-flex>
                                        <v-spacer></v-spacer>
                                        <v-flex xs6 class="text-xs-right pa-2">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-icon v-if="item.small" large color="red" v-on="on">
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
                    <v-icon right dark>file_copy</v-icon>
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
                <input hidden type="file" id="files" ref="files" multiple v-on:change="handleFileUploads()"
                       accept="image/x-png,image/gif,image/jpeg"/>
            </v-container>
        </v-flex>
    </v-layout>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "image-upload",
        props: {
            itemID: {
                type: Number,
                default: 0,
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
                default: 640
            },
            minWidth: {
                type: Number,
                default: 480
            }

        }
        ,
        async mounted() {
            let data = await this.$axios.get(`image/collect/${this.type}/${this.itemID}`)
            this.uploades = data.data.splice(0)

        },
        data() {
            return {
                files: [],
                loading: false,
                uploades: [],
                smallSize:false

            }
        }
        ,
        methods: {
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

                if(!this.$refs.images) return
                // Check image size
                this.$refs.images.map((obj,index)=>{
                    let  img = obj.image;
                    if(img.height < this.minHeight || img.width < this.minWidth){
                        this.files[index].small = true
                        this.smallSize  = true
                    }
                })

                if (this.files < 1 || this.smallSize ) return
                this.loading = true

                /*
                  Initialize the form data and iterate files
                */
                try {
                    for (let i = this.files.length - 1; i >= 0; i--) {
                        let formData = new FormData();
                        let file = this.files[i].file;
                        formData.append('file', file);
                        formData.append('type', 'sight');
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
            }
        },
    }
</script>

<style scoped>

</style>
