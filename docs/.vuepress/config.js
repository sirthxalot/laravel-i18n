import { backToTopPlugin } from '@vuepress/plugin-back-to-top'
import { containerPlugin } from '@vuepress/plugin-container'
import { externalLinkIconPlugin } from '@vuepress/plugin-external-link-icon'
import { mediumZoomPlugin } from '@vuepress/plugin-medium-zoom'
import { searchPlugin } from '@vuepress/plugin-search'
import { defaultTheme } from '@vuepress/theme-default'

module.exports = {
    title: 'Laravel I18n',
    description: 'Jump start your next Laravel web application for a worldwide audience.',

    base: '/laravel-i18n/',
    host: '0.0.0.0',
    port: 8080,

    plugins: [
        backToTopPlugin(),
        containerPlugin({}),
        externalLinkIconPlugin(),
        mediumZoomPlugin(),
        searchPlugin(),
    ],

    theme: defaultTheme({
        logo: '/img/logo.svg',
        navbar: [
            {
                text: 'Ecosystem',
                children: [
                    {
                        text: 'Software',
                        children: [
                            {
                                text: 'Source Code',
                                link: 'https://github.com/sirthxalot/laravel-i18n'
                            },
                            {
                                text: 'Support Questions',
                                link: 'https://github.com/sirthxalot/test-github-features/discussions'
                            },
                            {
                                text: 'Issue Tracking',
                                link: 'https://github.com/sirthxalot/laravel-i18n/issues'
                            },
                        ]
                    },
                    {
                        text: 'Get Involved',
                        children: [
                            {
                                text: 'Code of Conduct',
                                link: '/contribute/code-of-conduct/'
                            },
                            {
                                text: 'Contribution Guideline',
                                link: '/contribute/guide/'
                            },
                        ]
                    }
                ]
            },
            {
                text: 'Guide',
                children: [
                    {
                        text: '1.x',
                        link: '/v/1.x/guide/start/'
                    }
                ],
            },
            {
                text: 'License',
                link: '/license/'
            },
        ],
        sidebar: {
            '/v/1.x/guide/': [
                {
                    text: 'Getting Started',
                    children: [
                        {
                            text: 'Introduction',
                            link: '/v/1.x/guide/start/'
                        },
                        {
                            text: 'Installation',
                            link: '/v/1.x/guide/start/install/'
                        },
                        {
                            text: 'Configuration',
                            link: '/v/1.x/guide/start/config/'
                        },
                        {
                            text: 'Artisan Commands',
                            link: '/v/1.x/guide/start/artisan/'
                        }
                    ]
                },
                {
                    text: 'Fundamentals',
                    children: [
                        {
                            text: 'Internationalization (i18n)',
                            link: '/v/1.x/guide/fundamentals/i18n/'
                        },
                        {
                            text: 'Translation Drivers',
                            link: '/v/1.x/guide/fundamentals/drivers/',
                        },
                        {
                            text: 'Translating Languages',
                            link: '/v/1.x/guide/fundamentals/language/name',
                        },
                    ]
                },
                {
                    text: 'Basic Usage',
                    children: [
                        {
                            text: 'Translation Service',
                            link: '/v/1.x/guide/usage/i18n/',
                        },
                        {
                            text: 'HTTP Middleware',
                            link: '/v/1.x/guide/usage/http/middleware/set-locale-from-session',
                            children: [
                                {
                                    text: 'Set Locale From Session',
                                    link: '/v/1.x/guide/usage/http/middleware/set-locale-from-session',
                                },
                                {
                                    text: 'Set Locale From GET Parameter',
                                    link: '/v/1.x/guide/usage/http/middleware/set-locale-from-get-parameter',
                                }
                            ]
                        },
                        {
                            text: 'HTTP Form Requests',
                            link: '/v/1.x/guide/usage/validation/form/request/add/language/',
                            children: [
                                {
                                    text: 'Add Language Request',
                                    link: '/v/1.x/guide/usage/validation/form/request/add/language/',
                                },
                                {
                                    text: 'Add Translation Request',
                                    link: '/v/1.x/guide/usage/validation/form/request/add/translation/',
                                },
                                {
                                    text: 'Update Translation Request',
                                    link: '/v/1.x/guide/usage/validation/form/request/update/translation/',
                                },
                            ]
                        }

                    ]
                },
                {
                    text: 'Digging Deeper',
                    children: [
                        {
                            text: 'Helper Functions',
                            link: '/v/1.x/guide/advanced/helpers/'
                        },
                        {
                            text: 'Events',
                            link: '/v/1.x/guide/advanced/events/'
                        },
                        {
                            text: 'Exceptions',
                            link: '/v/1.x/guide/advanced/exceptions/'
                        },
                    ]
                },
            ]
        }
    }),
}
