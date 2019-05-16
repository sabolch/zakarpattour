<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-container grid-list-md fluid>
                <v-layout row wrap>
                    <v-flex v-if="uploades.length > 0" xs12 class="headline"><v-card class="pa-3">Uploaded Images</v-card></v-flex>
                    <v-flex
                            v-for="(item,key) in uploades"
                            :key="item.url"
                            xs3
                            d-flex
                    >
                        <v-card flat tile class="d-flex">
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


                    <v-flex v-if="files.length > 0" xs12 class="headline"><v-card class="pa-3">Review Images</v-card></v-flex>
                    <v-flex
                            v-for="(item,key) in files"
                            :key="item.name"
                            xs3
                            d-flex
                    >
                        <v-card flat tile class="d-flex">
                            <v-img
                                    :src="`${previewURL(item)}`"
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
                                                    v-if="!item.uploaded"
                                                    small
                                                    fab
                                                    color="red darken-2"
                                                    @click="removeFile(key)"
                                            >
                                                <v-icon class="white--text">block</v-icon>
                                            </v-btn>
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
            <v-progress-linear :indeterminate="loading"></v-progress-linear>

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
            <input hidden type="file" id="files" ref="files" multiple v-on:change="handleFileUploads()"  accept="image/x-png,image/gif,image/jpeg"/>
        </v-flex>
    </v-layout>
</template>

<script>
    import Form from 'vform'

    export default {
        name: "image-upload",
        data() {
            return {
                files: [],
                loading: false,
                uploades:[]
            }
        },
        methods: {
            handleFileUploads() {
                let filesArray =  Array.from(this.$refs.files.files);

                filesArray = filesArray.map( item => {
                    return {file:item, uploaded:false}
                });

                this.files =  this.files.concat(filesArray.filter(item => {
                    return this.files.findIndex(obj => obj.file.name === item.file.name) < 0;
                }));

                console.log(this.files)
            },
            selectFiles() {
                this.$refs.files.click()
            },

            async submitFiles() {
                if(this.files < 1 ) return
                this.loading = true
                /*
                  Initialize the form data
                */

                /*
                  Iteate over any file sent over appending the files
                  to the form data.
                */



                for (var i = 0; i < this.files.length; i++) {
                    let formData = new FormData();
                    let file = this.files[i].file;
                    formData.append('file', file);
                    formData.append('type','sight');
                    await this.upload(formData)
                    this.files[i].uploaded = true
                }

                this.files = [];
                /*
                  Make the request to the POST /multiple-files URL
                */


            },
            async removeImage(key,name){

               let form =  new Form({
                    path: name,
                });

                this.loading = true
                form.delete('image/remove').then( res => {
                        console.log(res)
                        this.loading = false
                        this.uploades.splice(key,1)
                    }).catch(res => {
                        console.log(res)
                        this.loading = false
                    });

            },

            previewURL(file){
                return URL.createObjectURL(file.file)
            },
            removeFile( key ){
                this.files.splice(key,1)
            },
            includes(array, name) {
                return array.some(item => item.name === name)
            },
            async upload(formData){
                var self = this;
                await  this.$axios.post('image/upload',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function (res) {
                    console.log(res.data.image)
                    self.uploades.push(res.data.image)

                    self.loading = false
                    console.log('Successfully uploaded!!');
                })
                    .catch(function (v) {
                        console.log(v)
                        self.loading = false

                        console.log('Erorr on upload ...');
                    });
            }
        }
    }
</script>

<style scoped>

</style>
