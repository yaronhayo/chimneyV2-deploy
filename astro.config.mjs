// @ts-check
import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  site: 'https://www.1stclasschimney.com',
  output: 'static',
  integrations: [sitemap()],
  build: {
    assets: 'assets',
  },
  vite: {
    css: {
      devSourcemap: true,
    },
  },
});
