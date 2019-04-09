<template>
    <div>
        <!--<div v-html="VQContent"></div>-->
    <div class="quill-editor"
         v-model="VQContent"
         :content="content"
         @change="onEditorChange($event)"
         v-quill:myQuillEditor="editorOption">
    </div>
    <input v-show="false" id="inputUpload" type="file" @change="uploadFunction($event)" accept="image/x-png,image/gif,image/jpeg" >
    </div>
</template>

<script>
    export default {
        name: "QuillEditor",
        props:['value','content'],
        async asyncData ({ $axios }) {
            if ($axios.baseUrl) {
                return { apiUrl: $axios.baseUrl}
            }
            return {apiUrl:''}
        },
        watch: {
            VQContent: {
                handler: function(value) {
                    this.$emit('input', value)
                }
            }
        },
        data () {
            return {
                alert:true,
                saved:false,
                apiUrl:'',
                selectedFile : '',
                VQContent:'',
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
        },
        methods: {
            onEditorChange({ editor, html, text }) {
                if(this.saved) {
                    console.log("message")
                }
            },
            onEditorGetContent(){
                let content = this.myQuillEditor.root.innerHTML
                // console.log(this.myQuillEditor.container.innerHTML)
                this.$emit('input', content)
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
