import { createSSRApp, h } from 'vue'
import { renderToString } from '@vue/server-renderer'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { createPinia } from 'pinia'
import { defineAsyncComponent } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index'
import { Ziggy } from './ziggy.js'
import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'
import { Icon } from '@iconify/vue'

const AdminLayout = defineAsyncComponent(() =>
    import('./Layout/Admin/Layout.vue')
)
const FrontendLayout = defineAsyncComponent(() =>
    import('./Layout/Frontend/Layout.vue')
)
const FrontendAuthLayout = defineAsyncComponent(() =>
    import('./Layout/Frontend/Auth/AuthLayout.vue')
)

createServer(page =>
  createInertiaApp({
    page,
    render: renderToString,
    resolve: async (name) => {
      const pages = import.meta.glob('./Pages/**/*.vue', { eager: false })
      let page = await pages[`./Pages/${name}.vue`]()

      if (name.startsWith('Admin/')) {
        page.default.layout = AdminLayout
      } else if (name.startsWith('Frontend/')) {
        if (name.startsWith('Frontend/Auth')) {
          page.default.layout = FrontendAuthLayout
        } else {
          page.default.layout = FrontendLayout
        }
      }

      return page
    },
    setup({ App, props, plugin }) {
      return createSSRApp({ render: () => h(App, props) })
        .use(plugin)
        .use(createPinia())
        .use(ZiggyVue, Ziggy)
        .use(PrimeVue, {
          theme: {
            preset: Aura,
            options: {
              darkModeSelector: "light",
            },
          },
        })
        .component('Link', Link)
        .component('Head', Head)
        .component('Icon', Icon)
    }
  })
)