import {defineConfig} from 'vitepress';
// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: '🧠 Synapse',
  description: 'AI agents for all!',
  lastUpdated: true,
  base: '/synapse/',
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      {
        text: '0.1.0',
        items: [
          {
            text: 'Changelog',
            link: 'https://github.com/use-the-fork/synapse/tree/v0.1.0',
          },
        ],
      },
    ],

    sidebar: [
      {
        text: 'Introduction',
        items: [
          { text: 'Getting Started', link: '/' },
          {
            text: 'Artisan Agent Tutorial',
            link: '/tutorials/artisan-agent.md',
          },
        ],
      },
        {
            text: 'Prebuilt Agents',
            items: [
                { text: 'Introduction', link: '/prebuilt-agents/' },
                {
                    text: 'Multi Query Retriever',
                    link: '/prebuilt-agents/multi-query-retriever-agent',
                },
                {
                    text: 'Contextual Retrieval Preprocessing',
                    link: '/prebuilt-agents/contextual-retrieval-preprocessing-agent',
                },
                {
                    text: 'Chat Rephrase',
                    link: '/prebuilt-agents/chat-rephrase-agent',
                },
            ],
        },
      {
        text: 'Agents',
        items: [
          { text: 'Introduction', link: '/agents/' },
          { text: 'Lifecycle', link: '/agents/agent-lifecycle' },
          { text: 'Integrations', link: '/agents/integrations' },
          { text: 'Prompts', link: '/agents/prompts' },
          { text: 'Traits', link: '/agents/agent-traits' },
        ],
      },
      {
        text: 'Prompts',
        items: [
          { text: 'Introduction', link: '/prompts/index/' },
          { text: 'Parts', link: '/prompts/parts' },
          { text: 'Agents', link: '/prompts/agents' },
        ],
      },
      {
        text: 'Tools',
        items: [
          { text: 'Introduction', link: '/tools/' },
          { text: 'Anatomy Of A Tool', link: '/tools/anatomy-of-a-tool' },
          { text: 'Packaged Tools', link: '/tools/packaged-tools' },
        ],
      },
      {
        text: 'Memory',
        items: [
          { text: 'Introduction', link: '/memory/' },
          { text: 'Collection Memory', link: '/memory/collection' },
          { text: 'Database Memory', link: '/memory/database' },
          {
            text: 'Conversation Summary Memory',
            link: '/memory/conversation-summary',
          },
        ],
      },
      {
        text: 'Traits',
        items: [
          { text: 'Introduction', link: '/traits/' },
          {
            text: 'Validates Output Schema',
            link: '/traits/validates-output-schema',
          },
          { text: 'Events', link: '/traits/events-trait' },
          { text: 'Logs', link: '/traits/log-trait' },
          { text: 'Hooks', link: '/traits/hook-trait' },
        ],
      },
    ],

    socialLinks: [
      {
        icon: 'github',
        link: 'https://github.com/use-the-fork/synapse',
      },
    ],
  },
});
