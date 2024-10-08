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
                    { text: 'Agent Lifecycle', link: '/agents/agent-lifecycle' },
                    { text: 'Prompts', link: '/agents/prompts' },
                    { text: 'Agent Traits', link: '/agents/agent-traits' },
                ],
            },
            {
                text: 'Traits',
                items: [
                    { text: 'Introduction', link: '/traits' },
                    { text: 'Validates Output Schema', link: '/traits/validates-output-schema' },
                    { text: 'Hooks', link: '/traits/hook-trait' },
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
