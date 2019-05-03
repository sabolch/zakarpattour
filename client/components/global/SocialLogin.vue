<template>
    <v-btn icon :class="iconClass" @click="login">
        <v-icon medium>{{ `sb-${provider}`}}</v-icon>
    </v-btn>

</template>

<script>
    export default {
        name: 'SocialLogin',
        props: {
            provider: {
                type: String,
                default: 'google',
                required: true,
                validator: value => {
                    return ['google', 'twitter', 'facebook', 'instagram', 'github'].includes(value)
                }
            },
        },
        computed: {
            // twitterAuth: () => 'https://api.twitter.com/oauth/authorize',
            OAuth: () => {
                switch (this.provider) {
                    case 'twitter':
                        return process.env.twitterAuth
                    case 'instagram':
                        return process.env.instagramAuth
                    case 'facebook':
                        return process.env.facebookAuth
                    case 'github' :
                        return process.env.githubAuth
                    default :
                        return process.env.googleAuth
                }
            },
            iconClass () {
                switch (this.provider) {
                    case 'twitter':
                        return ['blue--text', 'text--darken-1']
                    case 'instagram':
                        return ['purple--text']
                    case 'facebook':
                        return ['blue--text', 'text--darken-4']
                    case 'github' :
                        return ['dark--text']
                    default :
                        return ['red--text', 'text--darken-1']

                }
            },
            url: () => `${process.env.apiUrl}/oauth/${this.provider}`
        },

        mounted() {
            window.addEventListener('message', this.onMessage, false)
        },

        beforeDestroy() {
            window.removeEventListener('message', this.onMessage)
        },

        methods: {
            async login() {
                const newWindow = openWindow('', this.$t('login'))

                const url = await this.$store.dispatch('auth/fetchOauthUrl', {
                    provider: this.provider
                })

                newWindow.location.href = url
            },

            /**
             * @param {MessageEvent} e
             */
            onMessage(e) {
                if (e.origin !== process.env.apiUrl) {
                    return
                }

                this.$store.dispatch('auth/saveToken', {
                    token: e.data.token
                })

                this.$router.push({name: 'settings.profile'})
            }
        }
    }

    /**
     * @param  {Object} options
     * @return {Window}
     */
    function openWindow(url, title, options = {}) {
        if (typeof url === 'object') {
            options = url
            url = ''
        }

        options = {url, title, width: 600, height: 720, ...options}

        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screen.left
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screen.top
        const width = window.innerWidth || document.documentElement.clientWidth || window.screen.width
        const height = window.innerHeight || document.documentElement.clientHeight || window.screen.height

        options.left = ((width / 2) - (options.width / 2)) + dualScreenLeft
        options.top = ((height / 2) - (options.height / 2)) + dualScreenTop

        const optionsStr = Object.keys(options).reduce((acc, key) => {
            acc.push(`${key}=${options[key]}`)
            return acc
        }, []).join(',')

        const newWindow = window.open(url, title, optionsStr)

        if (window.focus) {
            newWindow.focus()
        }

        return newWindow
    }
</script>
