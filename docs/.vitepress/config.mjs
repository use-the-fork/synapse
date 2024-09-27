import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Laravel Synapse",
  description: "🦜🔗 Langchain like agents for laravel applications!",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
      nav: [
          { text: 'Home', link: '/' },
          { text: 'Features', link: '/features' }
      ],

      sidebar: [
          {
              text: 'Introduction',
              items: [
                  { text: 'Who am I', link: '/who-am-i' },
                  { text: 'Getting Started', link: '/getting-started' },
                  { text: 'Onboarding', link: '/onboarding' },
                  { text: 'Features', link: '/features' },
                  { text: 'Models', link: '/models' }
              ]
          },
          {
              text: 'Core Concepts',
              items: [
                  { text: 'Tools', link: '/tools' },
                  { text: 'Build', link: '/build' }
              ]
          },
          {
              text: 'Contributing',
              items: [
                  { text: 'How to contribute', link: '/how-to-contribute' },
                  { text: 'License', link: '/license' },
                  { text: 'Donation', link: '/donation' }
              ]
          }
      ],

      socialLinks: [
          { icon: 'github', link: 'https://github.com/bootstrapguru/dexor' }
      ]
  }
})