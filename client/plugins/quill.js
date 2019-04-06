// import Vue from 'vue'
// import VueQuillEditor from 'vue-quill-editor/dist/ssr'
//
// import Quill from 'quill'
//
//
// Vue.use(VueQuillEditor)
import Vue from 'vue'
import Quill from 'quill'
import VueQuillEditor from 'vue-quill-editor/dist/ssr'

import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'

import ImageResize from 'quill-image-resize-module'

Quill.register('modules/imageResize', ImageResize)

Vue.use(VueQuillEditor)