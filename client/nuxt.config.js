require('dotenv').config()
const webpack = require('webpack')

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
        mapApiKey: process.env.GOOGLE_MAP_API_KEY || 'AIzaSyBZcUbHJ5REVnm-q98yKqSmpbWkKtmLSuE',
        recaptchaKey: process.env.GOOGLE_RECAPTCHA_PUBLIC_KEY || '6LfeIXYUAAAAACI0h2MIPpDZiJ9a-uAZwrVMsxJ2',

        // Socials
        githubAuth: !!process.env.GITHUB_CLIENT_ID,
        facebookAuth: !!process.env.FACEBOOK_CLIENT_ID,
        googleAuth: !!process.env.GOOGLE_CLIENT_ID,
        twitterAuth: !!process.env.TWITTER_CLIENT_ID,
        instagramAuth: !!process.env.INSTAGRAM_CLIENT_ID
    },
    head: {
        title: process.env.APP_NAME,
        titleTemplate: '%s - ' + process.env.APP_NAME,
        meta: [
            {charset: 'utf-8'},
            {
                name: 'viewport',
                content: 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui'
            },
            {name: 'mobile-web-app-capable', content: 'yes'},
            {hid: 'description', name: 'description', content: 'Zakarpattour'}
        ],
        link: [
            {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'},
            // {
            //     rel: 'stylesheet',
            //     href: 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons'
            // },
            {rel: 'stylesheet', href: '/css/mapicons.min.css'},
            {rel: 'stylesheet', href: '/css/sbicon.min.css'},
        ],
        script: [{
            src: `https://maps.googleapis.com/maps/api/js?key=${process.env.GOOGLE_MAP_API_KEY}&libraries=places`,
        }],


    },

    loading: {color: '#ff000c'},

    router: {
        middleware: ['locale', 'check-auth', 'check-admin'],
    },

    css: [
        'quill/dist/quill.snow.css',
        'quill/dist/quill.core.css'
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
        {src: '~plugins/quill', ssr: false}
        // '~plugins/fontawesome',
        // '~plugins/nuxt-client-init',
        // { src: '~plugins/bootstrap', ssr: false }
    ],

    modules: [
        '@nuxtjs/router',
        '~/modules/spa',
        ['@nuxtjs/axios', {baseURL: process.env.APP_URL}],
        '@nuxtjs/vuetify',
        ['nuxt-validate', {
            lang: 'en',
        }],
        ['@nuxtjs/moment', {locales: ['hu', 'uk', 'en-gb'], defaultLocale: 'en-gb'}],
        ['@nuxtjs/recaptcha', {
            /* reCAPTCHA options */
            hideBadge: false,
            siteKey: process.env.GOOGLE_RECAPTCHA_PUBLIC_KEY,
            version: 2
        }
        ]
    ],

    build: {
        extractCSS: true,
        plugins: [
            new webpack.ProvidePlugin({
                'window.Quill': 'quill/dist/quill.js',
                'Quill': 'quill/dist/quill.js'
            })
        ]
    }

}
