<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <div class="quill-editor"
                     :content="content"
                     @change="onEditorChange($event)"
                     @blur="onEditorBlur($event)"
                     @focus="onEditorFocus($event)"
                     @ready="onEditorReady($event)"
                     v-quill:myQuillEditor="editorOption">
                </div>
                <v-btn
                color="green"
                @click="onEditorSave"
                >
                    Save
                </v-btn>
            </v-flex>
        </v-layout>
    </v-container>

</template>

<script>
    export default {
        name: "adminMarkerIndex",
        layout:"admin",
        data () {
            return {
                content: '<p>Some content goes here ...</p>',
                editorOption: {
                    // some quill options
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'align': [] }],
                            ['blockquote', 'code-block'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'script': 'sub' }, { 'script': 'super' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            [{ 'direction': 'rtl' }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'font': [] }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['clean'],
                            ['link', 'image', 'video']
                        ],
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
            console.log('app init, my quill insrance object is:')

        },
        methods: {
            onEditorSave(){
                console.log(this.myQuillEditor)
            },
            onEditorBlur(editor) {
                // console.log('editor blur!', editor)
            },
            onEditorFocus(editor) {
                // console.log('editor focus!', editor)
            },
            onEditorReady(editor) {
                // console.log('editor ready!', editor)
            },
            onEditorChange({ editor, html, text }) {
                // console.log('editor change!', editor, html, text)
                // this.content = html
            }
        }

    }
</script>
<style lang="scss" scoped>
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
