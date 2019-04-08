<template>
    <div>
    <div class="quill-editor"
         :content="content"
         v-quill:myQuillEditor="editorOption">
    </div>
    <input v-show="false" id="inputUpload" type="file" @change="uploadFunction($event)" accept="image/x-png,image/gif,image/jpeg" >
        <v-btn @click="onEditorGetContent" :color="saved ? 'orange':'green'" dark><v-icon left dark>{{saved ? 'edit':'save' }}</v-icon>{{ saved ? 'Edit' :'Save' }}</v-btn>
    </div>
</template>

<script>
    export default {
        name: "QuillEditor",
        props:['content'],
        async asyncData ({ $axios }) {
            if ($axios.baseUrl) {
                return { apiUrl: $axios.baseUrl}
            }
            return {apiUrl:''}
        },
        data () {
            return {
                saved:false,
                apiUrl:'',
                selectedFile : '',
                editorOption: {
                    modules: {
                        toolbar: {
                            container: [
                                ['bold', 'italic', 'underline', 'strike'],
                                [{'align': []}],
                                ['blockquote'],
                                [{'header': 1}, {'header': 2}],
                                [{'list': 'ordered'}, {'list': 'bullet'}],
                                [{'script': 'sub'}, {'script': 'super'}],
                                [{'indent': '-1'}, {'indent': '+1'}],
                                [{'direction': 'rtl'}],
                                [{'size': ['small', false, 'large', 'huge']}],
                                [{'header': [1, 2, 3, 4, 5, 6, false]}],
                                [{'font': []}],
                                [{'color': []}, {'background': []}],
                                ['clean'],
                                ['link', 'image', 'video'],
                            ],
                            handlers: {
                                'image': function(){
                                    document.getElementById('inputUpload').click()
                                }
                            },
                        },
                        syntax: {
                            highlight: text => hljs.highlightAuto(text).value
                        },
                        // imageDrop: true,
                        imageResize: {
                            displayStyles: {
                                backgroundColor: 'black',
                                border: 'none',
                                color: 'white'
                            },
                        }
                    }
                }
            }
        },
        mounted() {
            // console.log('app init, my quill insrance object is:')
        },
        methods: {
            onEditorGetContent(){
                this.$emit('get-content', this.myQuillEditor.root.innerHTML)
                this.saved = !this.saved
            },

            async uploadFunction(e){
                this.selectedFile = e.target.files[0];
                let form = new FormData();
                form.append("file", this.selectedFile);
                form.append("name", this.selectedFile.name);
                //upload image to server
                await this.$axios.post('/post/store/image', form,{
                    'headers': {
                        'Content-Type': "multipart/form-data"
                    }
                }).then(r => {
                    const range = this.myQuillEditor.getSelection();
                    this.myQuillEditor.insertEmbed(range.index, "image", `${this.$axios.defaults.baseURL}/post/get/image/${r.data.name}`);
                }).catch(e => {
                    console.log('error');
                })

            }
        }
    }
</script>

<style scoped>
    .quill-editor{
        min-height: 200px;
    }
    .quill-code {
        border: none;
        height: auto;
    > code {
        width: 100%;
        margin: 0;
        padding: 1rem;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0;
        height: 10rem;
        overflow-y: auto;
        resize: vertical;
    }
    }

</style>
