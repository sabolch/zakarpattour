require('dotenv').config()

const polyfills = [
  'Promise',
  'Object.assign',
  'Object.values',
  'Array.prototype.find',
  'Array.prototype.findIndex',
  'Array.prototype.includes',
  'String.prototype.includes',
  'String.prototype.startsWith',
  'String.prototype.endsWith'
]

module.exports = {
  mode: 'universal',

  srcDir: __dirname,

  env: {
    apiUrl: process.env.APP_URL || 'http://localhost:8000',
    appName: process.env.APP_NAME || 'Zakarpattour',
    appLocale: process.env.APP_LOCALE || 'en',
    githubAuth: !!process.env.GITHUB_CLIENT_ID
  },
  head: {
    title: process.env.APP_NAME,
    titleTemplate: '%s - ' + process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui' },
      { name: 'mobile-web-app-capable', content: 'yes' },
      { hid: 'description', name: 'description', content: 'Nuxt.js project' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' },
      { rel: 'stylesheet', href: 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.1/css/flag-icon.css' },
      { rel: 'stylesheet', href: '/css/mapicons.min.css' },
      { rel: 'stylesheet', href: '/css/sbicon.min.css' },
    ],

  script: [
      { src: `https://cdn.polyfill.io/v2/polyfill.min.js?features=${polyfills.join(',')}` },
      { src: `/js/markerclusterer.js` },
      { src: `https://maps.googleapis.com/maps/api/js?key=AIzaSyBZcUbHJ5REVnm-q98yKqSmpbWkKtmLSuE&libraries=places` },
      { src: `https://www.google.com/recaptcha/api.js`,render:`explicit` , async:true, defer:true },
    ]
  },

  loading: { color: '#ff000c' },

  router: {
    middleware: ['locale', 'check-auth']
  },

  css: [
    // { src: '~assets/sass/app.scss', lang: 'scss' }

  ],
  vuetify: {
    theme: {
      primary: '#3f51b5',
      secondary: '#b0bec5',
      accent: '#8c9eff',
      error: '#b71c1c'
    }
  },

  plugins: [
    '~components/global',
    '~plugins/i18n',
    '~plugins/vform',
    '~plugins/axios',
    // '~plugins/fontawesome',
    // '~plugins/nuxt-client-init',
    // { src: '~plugins/bootstrap', ssr: false }
  ],

  modules: [
    '@nuxtjs/router',
    '~/modules/spa',
    ['@nuxtjs/axios',{baseURL:process.env.APP_URL}],
    '@nuxtjs/vuetify',
    ['nuxt-validate', {
      lang: 'en',
    }],
    ['@nuxtjs/moment', { locales: ['hu','uk','en-gb'], defaultLocale: 'en-gb' }],
  ],

  build: {
    extractCSS: true
  }
}
