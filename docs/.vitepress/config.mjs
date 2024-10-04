import {defineConfig} from 'vitepress';
// https://vitepress.dev/reference/site-config
export default defineConfig({
    title: 'Laravel-Synapse',
    description: "AI agents for all!",
    lastUpdated: true,
    themeConfig: {
        // https://vitepress.dev/reference/default-theme-config
        nav: [
            { text: 'Home', link: '/' },
            {
                text: '0.0.0',
                items: [
                    {
                        text: 'Changelog',
                        link: 'https://github.com/use-the-fork/laravel-synapse/',
                    },
                ],
            },
        ],

        sidebar: [
            {
                text: 'Introduction',
                items: [{ text: 'Getting Started', link: '/' }],
            },
            {
                text: 'Agents',
                items: [
                    { text: 'Introduction', link: '/agents' },
                    { text: 'Working With Agents', link: '/agents/working-with-agents' },
                    { text: 'Querying Models', link: '/eloquent/querying-models' },
                    { text: 'Saving Models', link: '/eloquent/saving-models' },
                    { text: 'Deleting Models', link: '/eloquent/deleting-models' },
                ],
            }
        ],

        socialLinks: [
            {
                icon: 'github',
                link: 'https://github.com/use-the-fork/laravel-synapse',
            },
        ],
    },
});
